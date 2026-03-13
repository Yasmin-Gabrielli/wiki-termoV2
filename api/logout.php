<?php
// Inicia a sessão para poder manipulá-la
session_start();

// Destrói todas as informações da sessão (faz o logout)
session_destroy();

// Redireciona o usuário de volta para a página inicial dos alunos
header("Location:../login.php"); 
exit;
?>