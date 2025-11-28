<?php
include "conexao.php";

// VERIFICA SE VEIO POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "erro";
    exit;
}

// RECEBENDO OS CAMPOS (SEM ERRO DE NOME)
$nome      = $_POST["nome"]      ?? null;
$idade     = $_POST["idade"]     ?? null;
$genero    = $_POST["genero"]    ?? null;
$altura    = $_POST["altura"]    ?? null;
$peso      = $_POST["peso"]      ?? null;
$categoria = $_POST["categoria"] ?? null;

// SE ALGUM CAMPO ESTIVER VAZIO → ERRO
if (!$nome || !$idade || !$genero || !$altura || !$peso || !$categoria) {
    echo "erro";
    exit;
}

// PREPARA O INSERT
$sql = $conn->prepare("
    INSERT INTO inscricoes (nome, idade, genero, altura, peso, categoria)
    VALUES (?, ?, ?, ?, ?, ?)
");

$sql->bind_param("sissds", $nome, $idade, $genero, $altura, $peso, $categoria);

// EXECUTA
if ($sql->execute()) {
    echo "ok";
} else {
    echo "erro";
}

$sql->close();
$conn->close();
?>
