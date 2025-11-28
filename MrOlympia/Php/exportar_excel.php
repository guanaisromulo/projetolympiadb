<?php
require_once "conexao.php";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=inscritos.xls");

echo "ID\tNome\tEmail\tSexo\tAltura\tPeso\tCategoria\tData\n";

$sql = "SELECT u.id, u.nome, u.email, u.genero, u.altura, u.peso, u.categoria, u.data_criado FROM usuarios u ORDER BY u.id DESC";
$res = $conn->query($sql);
while ($r = $res->fetch_assoc()) {
    $sexo = ($r['genero'] === null ? '' : ($r['genero']==1 ? 'Feminino' : 'Masculino'));
    echo "{$r['id']}\t{$r['nome']}\t{$r['email']}\t{$sexo}\t{$r['altura']}\t{$r['peso']}\t{$r['categoria']}\t{$r['data_criado']}\n";
}
