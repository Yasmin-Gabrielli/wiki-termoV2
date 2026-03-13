let categoriaAtual = 'todas';
let todosTermos = [];
let letraAtual = ''; // Guarda a letra selecionada

// Bloco principal que roda quando a página carrega
document.addEventListener("DOMContentLoaded", async () => {
    gerarAlfabeto();
    await carregarMaterias();
    await carregarTurmasAluno(); 
    await carregarTermos();

    // Evento de pesquisa
    document.getElementById('pesquisa-termo').addEventListener('input', (e) => {
        renderizarGrid(e.target.value.toLowerCase());
    });

    // Evento de envio de novo termo
    document.getElementById('btn-enviar-termo').addEventListener('click', enviarTermo);
});

async function carregarMaterias() {
    const materias = await API.getMaterias();
    const sidebar = document.getElementById('lista-materias-sidebar');
    const select = document.getElementById('form-categoria');
    
    sidebar.innerHTML = ''; select.innerHTML = '';
    
    materias.forEach(m => {
        // Preenche sidebar
        sidebar.innerHTML += `
            <a href="#" onclick="mudarCategoria('${m.id}', this)" class="list-group-item list-group-item-action bg-dark text-white border-0 rounded mb-2 btn-filtro">
                <i class="bi ${m.icone} me-2"></i> ${m.nome}
            </a>`;
        // Preenche select do form
        select.innerHTML += `<option value="${m.id}">${m.nome}</option>`;
    });
}

async function carregarTurmasAluno() {
    const turmas = await API.getTurmas();
    const selectRm = document.getElementById('form-turma-rm');
    
    // Garante que a primeira opção seja o texto padrão
    selectRm.innerHTML = '<option value="" disabled selected>Selecione sua Turma...</option>';
    
    // Preenche com as turmas do banco de dados
    turmas.forEach(t => {
        // O "value" é o RM (que vai pro banco) e o texto visível é o Nome + RM
        selectRm.innerHTML += `<option value="${t.rm}">${t.nome} (RM: ${t.rm})</option>`;
    });
}

async function carregarTermos() {
    const data = await API.getTermos();
    // O aluno só vê os termos aprovados
    todosTermos = data.filter(t => t.status === 'aprovado');
    renderizarGrid();
}

function mudarCategoria(id, elemento) {
    categoriaAtual = id;
    document.querySelectorAll('.btn-filtro').forEach(el => el.classList.remove('active'));
    elemento.classList.add('active');
    renderizarGrid();
}

function renderizarGrid(filtroTexto = '') {
    const grid = document.getElementById('grid-termos');
    grid.innerHTML = '';

    const termosFiltrados = todosTermos.filter(t => {
        const bateCategoria = categoriaAtual === 'todas' || t.id_materia === categoriaAtual;
        const bateTexto = t.titulo.toLowerCase().includes(filtroTexto) || t.descricao.toLowerCase().includes(filtroTexto);
        const bateLetra = letraAtual === '' || t.titulo.toUpperCase().startsWith(letraAtual);
        return bateCategoria && bateTexto && bateLetra;
    });

    if (termosFiltrados.length === 0) {
        grid.innerHTML = '<p class="text-muted">Nenhum termo encontrado.</p>';
        return;
    }

    termosFiltrados.forEach(t => {
        grid.innerHTML += `
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0" style="cursor: pointer;" onclick="abrirLeitura(${t.id})">
                    <img src="${t.imagem || 'https://via.placeholder.com/300x180?text=Sem+Imagem'}" class="card-img-top" alt="Imagem">
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">${t.nome_materia}</span>
                        <h5 class="card-title fw-bold">${t.titulo}</h5>
                        <p class="card-text text-muted small text-truncate">${t.descricao}</p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <small class="text-primary fw-bold">Ler artigo completo <i class="bi bi-arrow-right"></i></small>
                    </div>
                </div>
            </div>`;
    });
}

async function enviarTermo() {
    const titulo = document.getElementById('form-titulo').value;
    const id_materia = document.getElementById('form-categoria').value;
    const descricao = document.getElementById('form-descricao').value;
    const autor = document.getElementById('form-autor').value;
    const rm_turma = document.getElementById('form-turma-rm').value;
    const fileInput = document.getElementById('form-imagem');

    if(!titulo || !descricao || !autor || !rm_turma) return alert("Preencha todos os campos!");

    // Converte a imagem para Base64 antes de enviar
    let imagemBase64 = '';
    if (fileInput.files.length > 0) {
        const reader = new FileReader();
        reader.readAsDataURL(fileInput.files[0]);
        reader.onload = async function () {
            imagemBase64 = reader.result;
            finalizarEnvio(titulo, id_materia, descricao, autor, rm_turma, imagemBase64);
        };
    } else {
        finalizarEnvio(titulo, id_materia, descricao, autor, rm_turma, '');
    }
}

async function finalizarEnvio(titulo, id_materia, descricao, autor, rm_turma, imagem) {
    const resposta = await API.addTermo({ titulo, id_materia, descricao, autor, rm_turma, imagem });
    if(resposta.sucesso) {
        alert("Termo enviado com sucesso! Aguarde a aprovação do professor.");
        document.getElementById('form-novo-termo').reset();
        bootstrap.Modal.getInstance(document.getElementById('modalTermo')).hide();
    } else {
        alert("Erro: " + resposta.erro);
    }
}

function abrirLeitura(id) {
    const termo = todosTermos.find(t => t.id === id);
    if (!termo) return;

    document.getElementById('ler-categoria').textContent = termo.nome_materia;
    document.getElementById('ler-titulo').textContent = termo.titulo;
    document.getElementById('ler-autor').textContent = termo.autor;
    document.getElementById('ler-turma').textContent = termo.nome_turma;
    document.getElementById('ler-descricao').textContent = termo.descricao;
    
    const imgElement = document.getElementById('ler-imagem');
    if (termo.imagem) {
        imgElement.src = termo.imagem;
        imgElement.style.display = 'block';
    } else {
        imgElement.style.display = 'none';
    }

    new bootstrap.Modal(document.getElementById('modalLerTermo')).show();
}

// Gera os botões A-Z na tela inicial
function gerarAlfabeto() {
    const container = document.getElementById('filtro-alfabeto');
    if (!container) return;
    
    let html = `<button class="btn btn-sm btn-outline-secondary active btn-letra" onclick="filtrarPorLetra('', this)">Todas</button>`;
    const alfabeto = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
    
    alfabeto.forEach(letra => {
        html += `<button class="btn btn-sm btn-outline-secondary btn-letra" onclick="filtrarPorLetra('${letra}', this)">${letra}</button>`;
    });
    
    container.innerHTML = html;
}

function filtrarPorLetra(letra, elemento) {
    letraAtual = letra;
    document.querySelectorAll('.btn-letra').forEach(btn => btn.classList.remove('active'));
    elemento.classList.add('active');
    renderizarGrid();
}