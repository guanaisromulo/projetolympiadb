<?php

session_start();
include "PHP/conexao.php"; 

// Opcional: Proteger para que só o admin veja a lista
// if ($_SESSION['tipo'] !== 'admin') {
//     http_response_code(403);
//     die(json_encode(["error" => "Acesso negado"]));
// }

$stmt = $conn->prepare("SELECT nome, genero, altura, peso, categoria FROM inscritos ORDER BY nome ASC");
$stmt->execute();
$res = $stmt->get_result();

$dados = [];

while($row = $res->fetch_assoc()){
    // Renomeia 'genero' para 'Gênero' se o HTML/JS espera isso
    $dados[] = $row;
}

// Retorna o JSON para o JS
header('Content-Type: application/json');
echo json_encode($dados);

$stmt->close();
$conn->close();
?>