<?php
session_start();
include("conexao.php");

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo "<script>alert('Preencha todos os campos.');window.history.back();</script>";
    exit;
}

$stmt = $conn->prepare("SELECT id, nome, email, senha FROM clientes WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['cliente_id']    = $usuario['id'];
    $_SESSION['cliente_nome']  = $usuario['nome'];
    $_SESSION['cliente_email'] = $usuario['email'];
    header("Location: ../pedido.php");
    exit;
} else {
    echo "<script>alert('E-mail ou senha incorretos.');window.location.href='../login.php';</script>";
}
