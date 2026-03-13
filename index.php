<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiki Escolar | Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="d-flex vh-100" id="wrapper">
        
        <div class="bg-dark text-white flex-shrink-0" id="sidebar-wrapper" style="width: 260px;">
            <div class="sidebar-heading text-center py-4">
                <i class="bi bi-book-half me-2"></i><strong>Wiki Termo</strong>
            </div>
            <div class="list-group list-group-flush p-3" id="menu-disciplinas">
                <p class="text-secondary small fw-bold mb-2">DISCIPLINAS</p>
                <a href="#" onclick="mudarCategoria('todas', this)" class="list-group-item list-group-item-action bg-dark text-white border-0 rounded mb-2 btn-filtro active">
                    <i class="bi bi-grid-fill me-2"></i> Ver Tudo
                </a>
                <div id="lista-materias-sidebar"></div>
                <hr class="text-secondary">
                <button class="btn btn-primary w-100 py-2 mt-3" data-bs-toggle="modal" data-bs-target="#modalTermo">
                    <i class="bi bi-plus-circle me-1"></i> Sugerir Termo
                </button>
            </div>
        </div>

        <div id="page-content-wrapper" class="flex-grow-1 bg-light overflow-y-auto" style="min-width: 0;">
            
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 sticky-top">
                <div class="d-flex align-items-center w-100">
                    <h2 class="fw-bold mb-0">Explorar Termos</h2>
                    <div class="ms-auto">
                        <a href="login.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-shield-lock me-1"></i> Acesso Professor</a>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 py-4">
                <div class="row mb-4 mt-2">
                    <div class="col-12 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="pesquisa-termo" class="form-control border-start-0 ps-0" placeholder="Pesquisar termo...">
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-1 mb-4" id="filtro-alfabeto">
                    </div>

                <div class="row g-4" id="grid-termos">
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTermo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Sugerir Novo Termo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-novo-termo">
                        <div class="row mb-3">
                            <div class="col"><input type="text" id="form-titulo" class="form-control" placeholder="Título do Termo" required></div>
                            <div class="col"><select id="form-categoria" class="form-select" required></select></div>
                        </div>
                        <div class="mb-3"><textarea id="form-descricao" class="form-control" rows="4" placeholder="Descrição..." required></textarea></div>
                        <div class="row mb-3">
                            <div class="col"><input type="text" id="form-autor" class="form-control" placeholder="Seu Nome" required></div>
                            <div class="col">
                            <select id="form-turma-rm" class="form-select" required>
                                <option value="" disabled selected>Selecione sua Turma...</option>
                            </select>
</div>
                        </div>
                        <div class="mb-3"><input type="file" id="form-imagem" class="form-control" accept="image/*"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btn-enviar-termo">Enviar para Revisão</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLerTermo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-2">
                    <span id="ler-categoria" class="badge bg-primary mb-3 fs-6">Categoria</span>
                    <h2 class="fw-bold mb-2" id="ler-titulo">Título</h2>
                    <p class="text-muted small mb-4 border-bottom pb-3">
                        <i class="bi bi-person-circle me-1"></i> Contribuição de: <strong id="ler-autor">Autor</strong> (<span id="ler-turma">Turma</span>)
                    </p>
                    <img id="ler-imagem" src="" class="img-fluid rounded mb-4 w-100 object-fit-cover shadow-sm" style="max-height: 400px; display:none;">
                    <div class="fs-5 text-dark" id="ler-descricao" style="line-height: 1.7; white-space: pre-wrap;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/api.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>