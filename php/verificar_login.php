<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['cliente_id'])) {
    echo json_encode([
        "logado" => true,
        "id"     => $_SESSION['cliente_id'],
        "nome"   => $_SESSION['cliente_nome'] ?? '',
        "email"  => $_SESSION['cliente_email'] ?? ''
    ]);
} else {
    echo json_encode(["logado" => false]);
}
