<?php
// api/rotas.php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'conexao.php';

// Pega a ação enviada pela URL (ex: rotas.php?acao=get_materias)
$acao = $_GET['acao'] ?? '';
// Pega os dados enviados via JSON pelo JS
$dados = json_decode(file_get_contents("php://input"), true);

try {
    switch ($acao) {
        // ==========================================
        // ROTAS PARA MATÉRIAS
        // ==========================================
        case 'get_materias':
            $stmt = $pdo->query("SELECT * FROM materias ORDER BY ordem ASC");
            echo json_encode($stmt->fetchAll());
            break;

        case 'add_materia':
            $stmt = $pdo->query("SELECT MAX(ordem) as max_ordem FROM materias");
            $row = $stmt->fetch();
            $nova_ordem = ($row['max_ordem'] !== null) ? $row['max_ordem'] + 1 : 1;
            
            $stmt = $pdo->prepare("INSERT INTO materias (id, nome, icone, ordem) VALUES (?, ?, ?, ?)");
            $stmt->execute([$dados['id'], $dados['nome'], $dados['icone'] ?? 'bi-book', $nova_ordem]);
            echo json_encode(["sucesso" => true, "mensagem" => "Matéria adicionada!"]);
            break;

        case 'excluir_materia':
            $stmt = $pdo->prepare("DELETE FROM materias WHERE id = ?");
            $stmt->execute([$dados['id']]);
            echo json_encode(["sucesso" => true, "mensagem" => "Matéria excluída!"]);
            break;

        case 'editar_materia':
            $stmt = $pdo->prepare("UPDATE materias SET nome = ?, icone = ? WHERE id = ?");
            $stmt->execute([$dados['nome'], $dados['icone'], $dados['id']]);
            echo json_encode(["sucesso" => true, "mensagem" => "Matéria atualizada!"]);
            break;

        case 'reordenar_materias':
            $ids = $dados['ids'];
            foreach ($ids as $index => $id) {
                $stmt = $pdo->prepare("UPDATE materias SET ordem = ? WHERE id = ?");
                $stmt->execute([$index + 1, $id]);
            }
            echo json_encode(["sucesso" => true, "mensagem" => "Ordem salva com sucesso!"]);
            break;

        // ==========================================
        // ROTAS PARA TURMAS (E RM)
        // ==========================================
        case 'get_turmas':
            $stmt = $pdo->query("SELECT * FROM turmas");
            echo json_encode($stmt->fetchAll());
            break;

        case 'add_turma':
            $stmt = $pdo->prepare("INSERT INTO turmas (nome, rm) VALUES (?, ?)");
            $stmt->execute([$dados['nome'], $dados['rm']]);
            echo json_encode(["sucesso" => true, "mensagem" => "Turma criada!"]);
            break;

        // ==========================================
        // ROTAS PARA TERMOS (WIKI)
        // ==========================================
        case 'get_termos':
            $sql = "SELECT t.*, m.nome as nome_materia, tu.nome as nome_turma 
                    FROM termos t 
                    JOIN materias m ON t.id_materia = m.id
                    JOIN turmas tu ON t.rm_turma = tu.rm
                    ORDER BY t.data_envio DESC";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll());
            break;

        case 'add_termo':
            $checkRM = $pdo->prepare("SELECT * FROM turmas WHERE rm = ?");
            $checkRM->execute([$dados['rm_turma']]);
            if ($checkRM->rowCount() === 0) {
                echo json_encode(["sucesso" => false, "erro" => "RM Inválido! Turma não encontrada."]);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO termos (titulo, id_materia, descricao, autor, rm_turma, imagem) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $dados['titulo'], 
                $dados['id_materia'], 
                $dados['descricao'], 
                $dados['autor'], 
                $dados['rm_turma'], 
                $dados['imagem']
            ]);
            echo json_encode(["sucesso" => true, "mensagem" => "Termo enviado para aprovação!"]);
            break;

        case 'atualizar_status_termo':
            $status = $dados['status']; 
            $id_termo = $dados['id'];

            if ($status === 'excluir') {
                $stmt = $pdo->prepare("DELETE FROM termos WHERE id = ?");
                $stmt->execute([$id_termo]);
                echo json_encode(["sucesso" => true, "mensagem" => "Termo excluído!"]);
            } else {
                $stmt = $pdo->prepare("UPDATE termos SET status = ? WHERE id = ?");
                $stmt->execute([$status, $id_termo]);
                echo json_encode(["sucesso" => true, "mensagem" => "Status atualizado!"]);
            }
            break;

        case 'editar_termo':
            // Verifica se o professor enviou uma nova imagem (Base64)
            if (isset($dados['imagem']) && $dados['imagem'] !== null && $dados['imagem'] !== '') {
                $stmt = $pdo->prepare("UPDATE termos SET titulo = ?, descricao = ?, imagem = ? WHERE id = ?");
                $stmt->execute([$dados['titulo'], $dados['descricao'], $dados['imagem'], $dados['id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE termos SET titulo = ?, descricao = ? WHERE id = ?");
                $stmt->execute([$dados['titulo'], $dados['descricao'], $dados['id']]);
            }
            echo json_encode(["sucesso" => true, "mensagem" => "Termo editado com sucesso!"]);
            break;
        // ==========================================
        // ROTAS PARA TURMAS (E RM)
        // ==========================================
        case 'get_turmas':
            $stmt = $pdo->query("SELECT * FROM turmas");
            echo json_encode($stmt->fetchAll());
            break;

        case 'add_turma':
            $stmt = $pdo->prepare("INSERT INTO turmas (nome, rm) VALUES (?, ?)");
            $stmt->execute([$dados['nome'], $dados['rm']]);
            echo json_encode(["sucesso" => true, "mensagem" => "Turma criada!"]);
            break;

        // --- CÓDIGO NOVO AQUI ---
        case 'excluir_turma':
            $stmt = $pdo->prepare("DELETE FROM turmas WHERE rm = ?");
            $stmt->execute([$dados['rm']]);
            echo json_encode(["sucesso" => true, "mensagem" => "Turma excluída com sucesso!"]);
            break;

        case 'editar_turma':
            // Atualiza o nome e o RM da turma
            $stmt = $pdo->prepare("UPDATE turmas SET nome = ?, rm = ? WHERE rm = ?");
            $stmt->execute([$dados['nome'], $dados['novo_rm'], $dados['rm_antigo']]);
            
            // Se o professor alterar o número do RM, atualizamos também os termos para não perderem o vínculo
            if ($dados['novo_rm'] !== $dados['rm_antigo']) {
                $stmt2 = $pdo->prepare("UPDATE termos SET rm_turma = ? WHERE rm_turma = ?");
                $stmt2->execute([$dados['novo_rm'], $dados['rm_antigo']]);
            }
            
            echo json_encode(["sucesso" => true, "mensagem" => "Turma atualizada!"]);
            break;

        default:
            echo json_encode(["sucesso" => false, "erro" => "Ação inválida."]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["sucesso" => false, "erro" => "Erro interno: " . $e->getMessage()]);
}
?>