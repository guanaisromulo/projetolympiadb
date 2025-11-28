<?php
session_start();
require_once "conexao.php";
if (!isset($_SESSION['usuario_id']) || ($_SESSION['tipo'] ?? '') !== 'admin') {
    die("<tr><td colspan='8'>Acesso negado.</td></tr>");
}

$sql = "SELECT u.*, i.data_inscricao FROM usuarios u LEFT JOIN inscritos i ON u.id = i.usuario_id ORDER BY u.id DESC";
$res = $conn->query($sql);

while ($row = $res->fetch_assoc()) {
    $sexo = ($row['genero'] === null) ? '' : (($row['genero'] == 1) ? 'Feminino' : 'Masculino');
    echo "<tr>
            <td>{$row['id']}</td>
            <td>" . htmlspecialchars($row['nome']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>{$sexo}</td>
            <td>" . ($row['altura'] ?? '') . "</td>
            <td>" . ($row['peso'] ?? '') . "</td>
            <td>" . htmlspecialchars($row['categoria'] ?? '') . "</td>
            <td>" . ($row['data_criado'] ?? '') . "</td>
          </tr>";
}
?>
