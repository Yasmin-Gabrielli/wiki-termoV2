// Variáveis Globais
let termosAdmin = [];
let materiasAdmin = [];
let statusFiltroAdmin = 'pendente'; // A página começa mostrando a Fila de Aprovação

// Inicialização
document.addEventListener("DOMContentLoaded", async () => {
    carregarTudo();
});

async function carregarTudo() {
    await carregarTermosAdmin();
    await carregarTurmasAdmin();
    await carregarMateriasAdmin();
}

// ==========================================
// LÓGICA DAS ABAS E TERMOS
// ==========================================

// Função chamada ao clicar nas abas
function mudarAbaAdmin(status, elemento) {
    statusFiltroAdmin = status;
    
    // Remove a cor azul de todas as abas
    document.querySelectorAll('.aba-admin').forEach(btn => {
        btn.classList.remove('active', 'text-dark', 'border-bottom', 'border-primary', 'border-3');
        btn.classList.add('text-muted');
    });
    
    // Adiciona a cor azul apenas na aba clicada
    elemento.classList.remove('text-muted');
    elemento.classList.add('active', 'text-dark', 'border-bottom', 'border-primary', 'border-3');
    
    // Recarrega a tabela mostrando apenas os itens da aba atual
    renderizarTabelaAdmin();
}

// Busca todos os termos do banco de dados
async function carregarTermosAdmin() {
    termosAdmin = await API.getTermos(); 
    renderizarTabelaAdmin(); 
}

// Desenha a tabela com base na aba selecionada
function renderizarTabelaAdmin() {
    const tbody = document.getElementById('tabela-termos');
    tbody.innerHTML = '';

    // Filtra para mostrar APENAS os termos da aba atual
    const termosFiltrados = termosAdmin.filter(t => t.status === statusFiltroAdmin);

    if (termosFiltrados.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">Nenhum termo nesta lista.</td></tr>`;
        return;
    }

    termosFiltrados.forEach(t => {
        let corStatus = 'secondary';
        let textoStatus = t.status;
        
        if (t.status === 'aprovado') {
            corStatus = 'success';
            textoStatus = 'Publicado';
        } else if (t.status === 'pendente') {
            corStatus = 'warning';
        } else if (t.status === 'averiguacao') {
            corStatus = 'danger';
            textoStatus = 'Em averiguação';
        }

        // Botões de Ação
        let botoes = `<button onclick="abrirModalVisualizacao(${t.id})" class="btn btn-sm btn-info me-1 text-white" title="Visualizar Artigo"><i class="bi bi-eye"></i> Visualizar</button>`;
        botoes += `<button onclick="abrirModalEdicao(${t.id})" class="btn btn-sm btn-primary me-1" title="Editar"><i class="bi bi-pencil"></i></button>`;
        
        if (t.status !== 'aprovado') {
            botoes += `<button onclick="alterarStatus(${t.id}, 'aprovado')" class="btn btn-sm btn-success me-1" title="Aprovar e Publicar"><i class="bi bi-check-lg"></i></button>`;
        }
        if (t.status !== 'averiguacao') {
            botoes += `<button onclick="alterarStatus(${t.id}, 'averiguacao')" class="btn btn-sm btn-warning me-1" title="Mover para Averiguação"><i class="bi bi-flag-fill text-dark"></i></button>`;
        }
        botoes += `<button onclick="alterarStatus(${t.id}, 'excluir')" class="btn btn-sm btn-danger" title="Excluir Definitivamente"><i class="bi bi-trash"></i></button>`;

        tbody.innerHTML += `
            <tr>
                <td class="text-start fw-bold">${t.titulo}</td>
                <td><span class="badge bg-light text-dark border">${t.nome_materia}</span></td>
                <td>${t.autor} <br><small class="text-muted">(${t.nome_turma})</small></td>
                <td><span class="badge bg-${corStatus} text-uppercase">${textoStatus}</span></td>
                <td class="text-center text-nowrap">${botoes}</td>
            </tr>
        `;
    });
}

// ==========================================
// MODAIS E AÇÕES DOS TERMOS
// ==========================================

function abrirModalVisualizacao(id) {
    const termo = termosAdmin.find(t => t.id === id);
    if (!termo) return;

    document.getElementById('vis-materia').textContent = termo.nome_materia;
    document.getElementById('vis-titulo').textContent = termo.titulo;
    document.getElementById('vis-autor').textContent = `${termo.autor} (${termo.nome_turma})`;
    document.getElementById('vis-descricao').textContent = termo.descricao;
    
    const imgElement = document.getElementById('vis-imagem');
    if (termo.imagem) {
        imgElement.src = termo.imagem;
        imgElement.style.display = 'block';
    } else {
        imgElement.style.display = 'none';
    }

    new bootstrap.Modal(document.getElementById('modalVisualizarTermo')).show();
}

function abrirModalEdicao(id) {
    const termo = termosAdmin.find(t => t.id === id);
    if (!termo) return;

    document.getElementById('edit-id').value = termo.id;
    document.getElementById('edit-titulo').value = termo.titulo;
    document.getElementById('edit-descricao').value = termo.descricao;
    
    // Limpa o input do ficheiro para não manter lixo de edições anteriores
    document.getElementById('edit-imagem').value = ''; 
    
    // Mostra a imagem atual como pré-visualização, se existir
    const imgPreview = document.getElementById('edit-imagem-preview');
    if (termo.imagem) {
        imgPreview.src = termo.imagem;
        imgPreview.style.display = 'inline-block';
    } else {
        imgPreview.style.display = 'none';
    }
    
    new bootstrap.Modal(document.getElementById('modalEditarTermo')).show();
}

async function salvarEdicao() {
    const id = document.getElementById('edit-id').value;
    const titulo = document.getElementById('edit-titulo').value;
    const descricao = document.getElementById('edit-descricao').value;
    const fileInput = document.getElementById('edit-imagem');
    
    if(!titulo || !descricao) return alert("Preencha todos os campos obrigatórios!");
    
    // Verifica se o professor selecionou uma imagem nova
    if (fileInput.files.length > 0) {
        const reader = new FileReader();
        reader.readAsDataURL(fileInput.files[0]);
        reader.onload = async function () {
            const imagemBase64 = reader.result;
            await API.editarTermo(id, titulo, descricao, imagemBase64); // Envia COM imagem
            concluirEdicao();
        };
    } else {
        await API.editarTermo(id, titulo, descricao, null); // Envia SEM imagem
        concluirEdicao();
    }
}

// Função auxiliar para fechar o modal e atualizar a tabela
function concluirEdicao() {
    bootstrap.Modal.getInstance(document.getElementById('modalEditarTermo')).hide();
    carregarTermosAdmin(); 
}

async function alterarStatus(id, status) {
    if (status === 'excluir') {
        if (!confirm("Tem a certeza que deseja EXCLUIR DEFINITIVAMENTE este termo? Essa ação não pode ser desfeita.")) return;
    } else if (status === 'averiguacao') {
        if (!confirm("Deseja mover este termo para AVERIGUAÇÃO?\n\nEle não aparecerá para os alunos e ficará retido para você tomar as devidas providências com o autor.")) return;
    }
    
    await API.atualizarStatusTermo(id, status);
    carregarTermosAdmin(); 
}

// ==========================================
// TURMAS
// ==========================================

async function carregarTurmasAdmin() {
    const turmas = await API.getTurmas();
    const lista = document.getElementById('lista-turmas');
    if(!lista) return;
    lista.innerHTML = '';
    
    turmas.forEach(t => {
        lista.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${t.nome} - <strong class="text-secondary">RM: ${t.rm}</strong></span>
                <div class="d-flex text-nowrap gap-1">
                    <button onclick="abrirModalEdicaoTurma('${t.nome.replace(/'/g, "\\'")}', '${t.rm}')" class="btn btn-sm btn-outline-primary" title="Editar Turma"><i class="bi bi-pencil"></i></button>
                    <button onclick="deletarTurma('${t.rm}')" class="btn btn-sm btn-outline-danger" title="Excluir Turma"><i class="bi bi-trash"></i></button>
                </div>
            </li>
        `;
    });
}

async function addTurma() {
    const nome = document.getElementById('nova-turma').value;
    const rm = document.getElementById('novo-rm').value;
    if(nome && rm) {
        await API.addTurma(nome, rm);
        document.getElementById('nova-turma').value = '';
        document.getElementById('novo-rm').value = '';
        carregarTurmasAdmin();
    }
}

async function deletarTurma(rm) {
    if (confirm("Tem a certeza que deseja excluir esta turma?\n\nAtenção: Os artigos enviados por alunos desta turma poderão ficar sem vínculo!")) {
        await API.excluirTurma(rm);
        carregarTurmasAdmin(); 
    }
}

function abrirModalEdicaoTurma(nome, rm) {
    document.getElementById('edit-turma-rm-antigo').value = rm;
    document.getElementById('edit-turma-nome').value = nome;
    document.getElementById('edit-turma-rm').value = rm;
    
    // Esconde o modal de listar turmas
    bootstrap.Modal.getInstance(document.getElementById('modalTurmas')).hide();
    // Abre o modal de edição
    new bootstrap.Modal(document.getElementById('modalEditarTurma')).show();
}

async function salvarEdicaoTurma() {
    const rm_antigo = document.getElementById('edit-turma-rm-antigo').value;
    const nome = document.getElementById('edit-turma-nome').value;
    const novo_rm = document.getElementById('edit-turma-rm').value;
    
    if(!nome || !novo_rm) return alert("Preencha o nome e o RM da turma!");
    
    await API.editarTurma(rm_antigo, nome, novo_rm);
    
    // Esconde o modal de edição
    bootstrap.Modal.getInstance(document.getElementById('modalEditarTurma')).hide();
    // Reabre o modal principal
    new bootstrap.Modal(document.getElementById('modalTurmas')).show();
    
    carregarTurmasAdmin(); // Recarrega a lista
}

// ==========================================
// MATÉRIAS
// ==========================================

async function carregarMateriasAdmin() {
    materiasAdmin = await API.getMaterias(); 
    const lista = document.getElementById('lista-materias');
    if(!lista) return;
    lista.innerHTML = '';
    
    materiasAdmin.forEach((m, index) => {
        let btnCimaDesativado = index === 0 ? 'disabled' : '';
        let btnBaixoDesativado = index === materiasAdmin.length - 1 ? 'disabled' : '';

        lista.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="bi ${m.icone} text-primary me-2"></i> ${m.nome} <small class="text-muted">(${m.id})</small></span>
                <div class="d-flex text-nowrap gap-1">
                    <button onclick="moverMateria(${index}, -1)" class="btn btn-sm btn-light border" title="Mover para Cima" ${btnCimaDesativado}><i class="bi bi-arrow-up"></i></button>
                    <button onclick="moverMateria(${index}, 1)" class="btn btn-sm btn-light border me-2" title="Mover para Baixo" ${btnBaixoDesativado}><i class="bi bi-arrow-down"></i></button>
                    
                    <button onclick="abrirModalEdicaoMateria('${m.id}', '${m.nome.replace(/'/g, "\\'")}', '${m.icone}')" class="btn btn-sm btn-outline-primary" title="Editar Matéria"><i class="bi bi-pencil"></i></button>
                    <button onclick="deletarMateria('${m.id}')" class="btn btn-sm btn-outline-danger" title="Excluir Matéria"><i class="bi bi-trash"></i></button>
                </div>
            </li>
        `;
    });
}

async function addMateria() {
    const id = document.getElementById('nova-materia-id').value;
    const nome = document.getElementById('nova-materia-nome').value;
    const icone = document.getElementById('nova-materia-icone').value; 
    
    if(id && nome) {
        await API.addMateria(id, nome, icone || 'bi-book'); 
        document.getElementById('nova-materia-id').value = '';
        document.getElementById('nova-materia-nome').value = '';
        document.getElementById('nova-materia-icone').value = 'bi-book'; 
        carregarMateriasAdmin();
    }
}

async function deletarMateria(id) {
    if (confirm("Tem certeza que deseja excluir esta matéria?")) {
        await API.excluirMateria(id);
        carregarMateriasAdmin(); 
    }
}

function abrirModalEdicaoMateria(id, nome, icone) {
    document.getElementById('edit-mat-id').value = id;
    document.getElementById('edit-mat-nome').value = nome;
    document.getElementById('edit-mat-icone').value = icone || 'bi-book';
    
    bootstrap.Modal.getInstance(document.getElementById('modalMaterias')).hide();
    new bootstrap.Modal(document.getElementById('modalEditarMateria')).show();
}

async function salvarEdicaoMateria() {
    const id = document.getElementById('edit-mat-id').value;
    const nome = document.getElementById('edit-mat-nome').value;
    const icone = document.getElementById('edit-mat-icone').value;
    
    if(!nome) return alert("Preencha o nome da matéria!");
    
    await API.editarMateria(id, nome, icone);
    
    bootstrap.Modal.getInstance(document.getElementById('modalEditarMateria')).hide();
    new bootstrap.Modal(document.getElementById('modalMaterias')).show();
    carregarMateriasAdmin(); 
}

async function moverMateria(index, direcao) {
    const novaPosicao = index + direcao;
    
    if (novaPosicao < 0 || novaPosicao >= materiasAdmin.length) return;
    
    const temp = materiasAdmin[index];
    materiasAdmin[index] = materiasAdmin[novaPosicao];
    materiasAdmin[novaPosicao] = temp;
    
    const novaOrdemIds = materiasAdmin.map(m => m.id);
    await API.reordenarMaterias(novaOrdemIds);
    carregarMateriasAdmin();
}