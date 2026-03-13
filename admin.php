<?php
session_start();
if (!isset($_SESSION['prof_logado']) || $_SESSION['prof_logado'] !== true) {
    header("Location: login.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel do Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark px-4 py-3">
        <a class="navbar-brand" href="#"><i class="bi bi-shield-lock-fill me-2"></i>Wiki Admin</a>
        <div class="ms-auto">
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">Ver Site</a>
            <a href="api/logout.php" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-4">
            <h4>Gerir Termos</h4>
            <div>
                <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalMaterias">Matérias</button>
                <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalTurmas">Turmas & RM</button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
                <ul class="nav nav-tabs border-bottom-0" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold text-dark border-0 border-bottom border-primary border-3 bg-transparent aba-admin" onclick="mudarAbaAdmin('pendente', this)" type="button">
                            Fila de Aprovação
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold text-muted border-0 bg-transparent aba-admin" onclick="mudarAbaAdmin('averiguacao', this)" type="button">
                            <i class="bi bi-shield-exclamation text-warning"></i> Em Averiguação
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold text-muted border-0 bg-transparent aba-admin" onclick="mudarAbaAdmin('aprovado', this)" type="button">
                            <i class="bi bi-check-circle text-success"></i> Publicados
                        </button>
                    </li>
                </ul>
            </div>

            <div class="table-responsive p-3">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-secondary align-middle">
                        <tr class="text-center">
                            <th class="text-start">Título</th>
                            <th>Matéria</th>
                            <th>Autor/Turma</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabela-termos" class="text-center"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTurmas" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content"><div class="modal-body">
            <h5>Adicionar Turma</h5>
            <div class="d-flex gap-2 mb-3">
                <input type="text" id="nova-turma" class="form-control" placeholder="Nome (Ex: 9º Ano)">
                <input type="text" id="novo-rm" class="form-control" placeholder="RM">
                <button onclick="addTurma()" class="btn btn-success">Add</button>
            </div>
            <ul id="lista-turmas" class="list-group"></ul>
        </div></div></div>
    </div>
    <div class="modal fade" id="modalEditarTurma" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Turma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-turma-rm-antigo">
                    <div class="mb-3">
                        <label class="form-label">Nome da Turma</label>
                        <input type="text" id="edit-turma-nome" class="form-control" placeholder="Ex: 9º Ano">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">RM da Turma</label>
                        <input type="text" id="edit-turma-rm" class="form-control" placeholder="RM">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" onclick="salvarEdicaoTurma()">Salvar Alterações</button>
                </div>
            </div>
        </div>
</div>

    <div class="modal fade" id="modalMaterias" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content"><div class="modal-body">
            <h5>Gerenciar Matérias</h5>
            <div class="row g-2 mb-3">
                <div class="col-3"><input type="text" id="nova-materia-id" class="form-control" placeholder="ID (ex: mat)"></div>
                <div class="col-4"><input type="text" id="nova-materia-nome" class="form-control" placeholder="Nome"></div>
                <div class="col-3">
                    <select id="nova-materia-icone" class="form-select">
                        <option value="bi-book">📖 Livro</option>
                        <option value="bi-calculator">🧮 Calculadora</option>
                        <option value="bi-translate">🔤 Idiomas</option>
                        <option value="bi-globe-americas">🌎 Geografia</option>
                        <option value="bi-hourglass-split">⏳ História</option>
                        <option value="bi-heart-pulse">🧬 Biologia</option>
                        <option value="bi-lightning-charge">⚡ Física</option>
                        <option value="bi-palette">🎨 Artes</option>
                        <option value="bi-laptop">💻 Informática</option>
                    </select>
                </div>
                <div class="col-2"><button onclick="addMateria()" class="btn btn-success w-100">Add</button></div>
            </div>
            <ul id="lista-materias" class="list-group"></ul>
        </div></div></div>
    </div>

    <div class="modal fade" id="modalEditarMateria" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Matéria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-mat-id">
                    <div class="mb-3">
                        <label class="form-label">Nome da Matéria</label>
                        <input type="text" id="edit-mat-nome" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ícone</label>
                        <select id="edit-mat-icone" class="form-select">
                            <option value="bi-book">📖 Livro</option>
                            <option value="bi-calculator">🧮 Calculadora</option>
                            <option value="bi-translate">🔤 Idiomas</option>
                            <option value="bi-globe-americas">🌎 Geografia</option>
                            <option value="bi-hourglass-split">⏳ História</option>
                            <option value="bi-heart-pulse">🧬 Biologia</option>
                            <option value="bi-lightning-charge">⚡ Física</option>
                            <option value="bi-palette">🎨 Artes</option>
                            <option value="bi-laptop">💻 Informática</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" onclick="salvarEdicaoMateria()">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>

   <div class="modal fade" id="modalEditarTermo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Termo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" id="edit-titulo" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea id="edit-descricao" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alterar Imagem (Opcional)</label>
                        <input type="file" id="edit-imagem" class="form-control" accept="image/*">
                        <div class="mt-2 text-center">
                            <img id="edit-imagem-preview" src="" class="img-thumbnail shadow-sm" style="max-height: 150px; display: none;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="salvarEdicao()">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalVisualizarTermo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-2">
                    <span id="vis-materia" class="badge bg-secondary mb-3 fs-6">Categoria</span>
                    <h2 class="fw-bold mb-2" id="vis-titulo">Título</h2>
                    <p class="text-muted small mb-4 border-bottom pb-3">
                        <i class="bi bi-person-circle me-1"></i> Autor: <strong id="vis-autor">Nome (Turma)</strong>
                    </p>
                    <img id="vis-imagem" src="" class="img-fluid rounded mb-4 w-100 object-fit-cover shadow-sm" style="max-height: 400px; display:none;">
                    <div class="fs-5 text-dark" id="vis-descricao" style="line-height: 1.7; white-space: pre-wrap;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/api.js"></script>
    <script src="assets/js/admin.js"></script>
    <script>
        function sair() {
            fetch('api/logout.php').then(() => window.location.href = 'index.php');
        }
    </script>
</body>
</html>