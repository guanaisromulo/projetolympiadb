<?php
// register.php
session_start();
require_once "conexao.php";

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$altura = isset($_POST['altura']) ? floatval($_POST['altura']) : null;
$peso = isset($_POST['peso']) ? floatval($_POST['peso']) : null;
$genero = $_POST['genero'] ?? null; // "masculino"/"feminino"
$categoria = $_POST['categoria'] ?? null;

// validações simples
if ($nome === '' || $email === '') {
    echo "erro";
    exit;
}

// evitar duplicação de email
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    echo "duplicado";
    exit;
}
$stmt->close();

// criar usuário com hash de senha (se senha vazia, gera senha aleatória)
if ($senha === '') {
    // senha aleatória curta (pode ser alterada depois)
    $senha = bin2hex(random_bytes(4));
}
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// inserir usuário
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, altura, peso, genero, categoria) VALUES (?, ?, ?, ?, ?, ?, ?)");
$genFlag = ($genero === 'feminino') ? 1 : 0;
$stmt->bind_param("sssddis", $nome, $email, $senhaHash, $altura, $peso, $genFlag, $categoria);
if (!$stmt->execute()) {
    echo "erro";
    exit;
}
$usuario_id = $stmt->insert_id;
$stmt->close();

// criar registro de inscrição (opcional)
$stmt2 = $conn->prepare("INSERT INTO inscritos (usuario_id, nome, genero, altura, peso, categoria) VALUES (?, ?, ?, ?, ?, ?)");
$stmt2->bind_param("issdds", $usuario_id, $nome, $genero, $altura, $peso, $categoria);
$stmt2->execute();
$stmt2->close();

// logar automaticamente
$_SESSION['usuario_id'] = $usuario_id;
$_SESSION['usuario_nome'] = $nome;
$_SESSION['tipo'] = 'usuario';

echo "ok";
?>
