<?php
// ATENÇÃO: Corrigido o caminho para incluir o conexao.php que está na subpasta PHP
include 'PHP/conexao.php'; 
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Mr. Olympia - Lista de Inscritos</title>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;600;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-dark">
  <header class="site-header">
    <div class="container header-inner">
      <a href="index.html" class="brand">
        <div class="logo-icon">🏆</div>
        <div class="brand-text">
          <span class="brand-title">Mr. Olympia</span>
          <small class="brand-sub">Pro Bodybuilding Championship</small>
        </div>
      </a>

      <nav class="nav">
        <a href="index.html" class="nav-link">Home</a>
        <a href="inscricao.html" class="nav-link btn-outline">Inscrição</a>
        <a href="login.html" class="nav-link">Entrar</a>
        <a href="inscritos.php" class="nav-link">Ver Inscritos</a>
      </nav>
    </div>
  </header>

  <main>
    <section class="list-section container">
      <h2>Atletas Inscritos</h2>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Email</th>
              <th>Gênero</th>
              <th>Altura (cm)</th>
              <th>Peso (kg)</th>
              <th>Categoria</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Se a conexão foi bem-sucedida, executa a consulta
            if (isset($conn)) {
                $tabela = 'inscritos'; // Usando a tabela 'inscritos' (conforme seu phpMyAdmin)
                $sql = "SELECT id, nome, email, genero, altura, peso, categoria FROM $tabela ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    // Loop para criar uma linha (<tr>) para cada atleta encontrado
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['genero']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['altura']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['peso']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhum atleta inscrito encontrado.</td></tr>";
                }
                
                // Fecha a conexão
                $conn->close();
            } else {
                echo "<tr><td colspan='7'>Erro: Conexão com o banco de dados não estabelecida. Verifique o conexao.php.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
  
  <footer class="site-footer">
    <div class="container footer-inner">
      <div>© <span id="year"></span> Mr. Olympia — Todos os direitos reservados.</div>
      <div class="footer-right">
        <small>Design: tema escuro • Cores: preto • vermelho • dourado</small>
      </div>
    </div>
  </footer>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>
</body>
</html>