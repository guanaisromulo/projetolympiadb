<?php
// Arquivo: /projeto/PHP/login.php
session_start();
include "conexao.php"; // Inclui sua conexão com o banco

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// CORREÇÃO: Buscando na tabela 'inscritos' onde os atletas estão cadastrados
$sql = "SELECT id, email, senha FROM inscritos WHERE email = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    $senhaHashArmazenada = $usuario['senha'];
    
    // VERIFICAÇÃO COM HASHE (Obrigatorio para senhas longas)
    if (password_verify($senha, $senhaHashArmazenada)) {
        
        // Login bem-sucedido!
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_email'] = $usuario['email'];
        
        // Redirecionamento de exemplo:
        if ($email === 'admin@admin.com') { 
            header("Location: ../dashboard_admin.html");
        } else {
            header("Location: ../dashboard_usuario.html");
        }
        exit();
        
    } else {
        // Senha não confere
        $mensagem = "Email ou senha incorretos.";
    }
} else {
    // E-mail não encontrado
    $mensagem = "Email ou senha incorretos.";
}

$stmt->close();
$conn->close();

// Redireciona com feedback de erro
?>
<script>
    alert('<?php echo htmlspecialchars($mensagem); ?>');
    window.location.href = '../login.html'; 
</script>
<?php