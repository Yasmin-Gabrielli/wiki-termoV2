// assets/js/api.js

const URL_API = 'api/rotas.php';

// Objeto global que contém todas as funções de chamada ao banco de dados
const API = {
    // Busca todas as matérias
    getMaterias: async () => {
        const resposta = await fetch(`${URL_API}?acao=get_materias`);
        return await resposta.json();
    },

    // Busca todas as turmas (e seus RMs)
    getTurmas: async () => {
        const resposta = await fetch(`${URL_API}?acao=get_turmas`);
        return await resposta.json();
    },

    // Busca todos os termos
    getTermos: async () => {
        const resposta = await fetch(`${URL_API}?acao=get_termos`);
        return await resposta.json();
    },

    // Envia um novo termo (Aluno)
    addTermo: async (dados) => {
        const resposta = await fetch(`${URL_API}?acao=add_termo`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        });
        return await resposta.json();
    },

    // Adiciona uma nova turma (Professor)
    addTurma: async (nome, rm) => {
        const resposta = await fetch(`${URL_API}?acao=add_turma`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nome, rm })
        });
        return await resposta.json();
    },

    // Adiciona uma nova matéria (Professor)
    addMateria: async (id, nome, icone) => {
        const resposta = await fetch(`${URL_API}?acao=add_materia`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, nome, icone })
        });
        return await resposta.json();
    },

    // Atualiza o status do termo (Professor aprovar/excluir/averiguar)
    atualizarStatusTermo: async (id, status) => {
        const resposta = await fetch(`${URL_API}?acao=atualizar_status_termo`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, status })
        });
        return await resposta.json();
    },

    
    editarTermo: async (id, titulo, descricao, imagem) => {
        const resposta = await fetch(`${URL_API}?acao=editar_termo`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, titulo, descricao, imagem })
        });
        return await resposta.json();
    },
    // Exclui uma matéria (Professor)
    excluirMateria: async (id) => {
        const resposta = await fetch(`${URL_API}?acao=excluir_materia`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        return await resposta.json();
    },
    // Edita uma matéria existente (Professor)
    editarMateria: async (id, nome, icone) => {
        const resposta = await fetch(`${URL_API}?acao=editar_materia`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, nome, icone })
        });
        return await resposta.json();
    },
    // Salva a nova ordem das matérias (Professor)
    reordenarMaterias: async (ids) => {
        const resposta = await fetch(`${URL_API}?acao=reordenar_materias`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids })
        });
        return await resposta.json();

    
    },
    // Exclui uma turma (Professor)
    excluirTurma: async (rm) => {
        const resposta = await fetch(`${URL_API}?acao=excluir_turma`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ rm })
        });
        return await resposta.json();
    },

    // Edita uma turma existente (Professor)
    editarTurma: async (rm_antigo, nome, novo_rm) => {
        const resposta = await fetch(`${URL_API}?acao=editar_turma`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ rm_antigo, nome, novo_rm })
        });
        return await resposta.json();
    },
};