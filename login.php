<?php
session_start();
if (isset($_SESSION['prof_logado']) && $_SESSION['prof_logado'] === true) {
    header("Location: admin.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h4 class="text-center mb-4">Acesso do Professor</h4>
        <div id="msg-erro" class="alert alert-danger d-none"></div>
        <div class="mb-3">
            <label>Código de Acesso Único</label>
            <input type="password" id="codigo" class="form-control" placeholder="Digite o código...">
        </div>
        <button onclick="fazerLogin()" class="btn btn-primary w-100">Entrar</button>
        <div class="text-center mt-3"><a href="index.php" class="text-muted">Voltar ao Início</a></div>
    </div>

    <script>
        async function fazerLogin() {
            const codigo = document.getElementById('codigo').value;
            const res = await fetch('api/login_action.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({codigo})
            });
            const data = await res.json();
            if(data.sucesso) window.location.href = 'admin.php';
            else {
                document.getElementById('msg-erro').textContent = data.erro;
                document.getElementById('msg-erro').classList.remove('d-none');
            }
        }
    </script>
</body>
</html>