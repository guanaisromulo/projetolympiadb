<?php
// Inclui o arquivo de conexão. O nome da variável é $conn.
include "conexao.php";

// 1. Recebe e sanitiza os dados do formulário
// (Os campos "nome", "email", etc., devem ser os mesmos nomes no seu HTML)
$nome      = $_POST['nome'] ?? null;
$email     = $_POST['email'] ?? null;
$senha     = $_POST['senha'] ?? null;
$genero    = $_POST['genero'] ?? null; // Nome usado na tabela SQL
$altura    = $_POST['altura'] ?? null;
$peso      = $_POST['peso'] ?? null;
$categoria = $_POST['categoria'] ?? null;

// Verifica se a variável de conexão existe (garantia contra erros)
if (!$conn) {
    die("Erro interno: A variável de conexão (\$conn) não foi definida.");
}

// 2. Hash da Senha para Segurança
$senhaHash = null;
if (!empty($senha)) {
    // Usamos password_hash para maior segurança. Seu login.php deve ser ajustado para usar essa função.
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT); 
} else {
    // Se a senha estiver vazia, você pode redirecionar ou dar um erro.
    // Para simplificar, vamos assumir que o formulário é obrigatório.
    die("Erro: Senha não fornecida.");
}

// 3. Prepara a Inserção de Dados (Prepared Statement)
// A ordem dos campos DEVE bater com a ordem na tabela 'inscritos'
// Certifique-se que você adicionou as colunas 'email' e 'senha' na tabela 'inscritos'!
$stmt = $conn->prepare("INSERT INTO inscritos (nome, email, senha, genero, altura, peso, categoria) VALUES (?, ?, ?, ?, ?, ?, ?)");

// Tipos de dados (s=string, d=double/float, i=integer)
// sssdis é o correto se altura e peso forem strings.
// sssdidis (nome, email, senha, genero, altura, peso, categoria)
$stmt->bind_param("ssssdid", 
    $nome, 
    $email, 
    $senhaHash, 
    $genero, 
    $altura, 
    $peso, 
    $categoria
);

// 4. Executa e Trata o Resultado
if ($stmt->execute()) {
    $mensagem = "Inscrição realizada com sucesso!";
    $redirecionar = '../index.html';
} else {
    // Código 1062 é o erro de chave duplicada (se o email for UNIQUE)
    if ($conn->errno === 1062) {
        $mensagem = "Erro: Este e-mail já está cadastrado.";
        $redirecionar = '../inscricao.html';
    } else {
        $mensagem = "Erro ao salvar: " . $conn->error;
        $redirecionar = '../inscricao.html';
    }
}

// 5. Fecha as Conexões
$stmt->close();
$conn->close();

// 6. Retorna o Feedback ao Usuário via JavaScript
?>
<script>
    alert('<?php echo $mensagem; ?>');
    window.location.href = '<?php echo $redirecionar; ?>';
</script>
<?php
// Fim do arquivo salvar_inscricao.php
?>