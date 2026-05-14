<?php
include("conexao.php");

$nome  = trim($_POST['nome']  ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($nome) || empty($email) || empty($senha)) {
    echo "<script>alert('Preencha todos os campos.');window.history.back();</script>";
    exit;
}

// Verificar duplicata
$chk = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
$chk->bind_param("s", $email);
$chk->execute();
$chk->store_result();

if ($chk->num_rows > 0) {
    $chk->close();
    echo "<script>alert('E-mail já cadastrado!');window.location.href='../cadastro.php';</script>";
    exit;
}
$chk->close();

$hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $hash);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    echo "<script>alert('Cadastro realizado! Faça login.');window.location.href='../login.php';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar: " . addslashes($stmt->error) . "');window.history.back();</script>";
}
