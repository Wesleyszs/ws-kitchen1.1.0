<?php
$host  = "127.0.0.1";
$user  = "root";
$pass  = "";
$db    = "ws_kitchen";
$porta = 3307;

$conn = new mysqli($host, $user, $pass, $db, $porta);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["sucesso" => false, "mensagem" => "Falha na conexão: " . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
