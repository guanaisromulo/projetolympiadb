<?php
session_start();
require_once "conexao.php";
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["erro" => "não logado"]);
    exit;
}

$uid = $_SESSION['usuario_id'];
// dados do usuário (última inscrição)
$stmt = $conn->prepare("SELECT u.nome, u.email, u.altura, u.peso, u.genero, u.categoria, i.data_inscricao
                        FROM usuarios u
                        LEFT JOIN inscritos i ON i.usuario_id = u.id
                        WHERE u.id = ?
                        ORDER BY i.id DESC LIMIT 1");
$stmt->bind_param("i", $uid);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

// estatísticas
$total = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
$homens = $conn->query("SELECT COUNT(*) as c FROM usuarios WHERE genero = 0")->fetch_assoc()['c'];
$mulheres = $conn->query("SELECT COUNT(*) as c FROM usuarios WHERE genero = 1")->fetch_assoc()['c'];

echo json_encode([
    "nome" => $user['nome'] ?? ($_SESSION['usuario_nome'] ?? ''),
    "email" => $user['email'] ?? '',
    "altura" => $user['altura'] ?? '',
    "peso" => $user['peso'] ?? '',
    "sexo" => ($user['genero'] === null ? '' : ($user['genero'] == 1 ? 'Feminino' : 'Masculino')),
    "categoria" => $user['categoria'] ?? '',
    "data_inscricao" => $user['data_inscricao'] ?? '',
    "total" => intval($total),
    "homens" => intval($homens),
    "mulheres" => intval($mulheres)
]);
?>
