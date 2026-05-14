<?php
/**
 * buscar_status.php
 * Endpoint público de polling usado pelo frontend do cliente.
 * GET ?id=42
 */
include 'conexao.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido']);
    exit;
}

$stmt = $conn->prepare("
    SELECT id, status, mesa, tempo_estimado, observacao, data_pedido, atualizado_em
    FROM pedidos
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(404);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Pedido não encontrado']);
    exit;
}

// Busca itens
$itens = [];
$ri = $conn->prepare("SELECT nome, preco, quantidade FROM itens_pedido WHERE pedido_id = ? ORDER BY id");
$ri->bind_param('i', $id);
$ri->execute();
$res = $ri->get_result();
while ($item = $res->fetch_assoc()) $itens[] = $item;
$ri->close();
$conn->close();

echo json_encode([
    'sucesso'        => true,
    'id'             => (int)$row['id'],
    'status'         => $row['status'],
    'mesa'           => $row['mesa'],
    'tempo_estimado' => $row['tempo_estimado'],
    'observacao'     => $row['observacao'],
    'data_pedido'    => $row['data_pedido'],
    'atualizado_em'  => $row['atualizado_em'],
    'itens'          => $itens,
]);
