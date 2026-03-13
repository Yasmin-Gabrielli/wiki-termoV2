<?php
// api/login_action.php
session_start();
header("Content-Type: application/json; charset=UTF-8");

$dados = json_decode(file_get_contents("php://input"), true);
$codigo_digitado = $dados['codigo'] ?? '';

// O código único que você vai entregar aos professores
$codigo_unico_professores = "PROF-WIKI-2026"; 

if ($codigo_digitado === $codigo_unico_professores) {
    $_SESSION['prof_logado'] = true;
    echo json_encode(["sucesso" => true]);
} else {
    echo json_encode(["sucesso" => false, "erro" => "Código de acesso incorreto."]);
}
?>