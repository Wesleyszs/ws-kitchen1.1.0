<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
include("conexao.php");

header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"), true);

if (!$dados || empty($dados['itens'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Carrinho vazio"]);
    exit;
}

$itens       = $dados['itens'];
$total       = (float)($dados['total'] ?? 0);
$mesa        = isset($dados['mesa']) && $dados['mesa'] !== '' ? (int)$dados['mesa'] : null;
$observacao  = trim($dados['observacao'] ?? '');
$nomeAnonimo = trim($dados['nome_anonimo'] ?? '');
$usuario_id  = $_SESSION['cliente_id'] ?? null;

// Verifica se a coluna 'itens' existe (compatibilidade com bancos antigos)
$colunas = [];
$res = $conn->query("SHOW COLUMNS FROM pedidos");
while ($col = $res->fetch_assoc()) $colunas[] = $col['Field'];
$temColunaItens    = in_array('itens', $colunas);
$temNomeAnonimo    = in_array('nome_anonimo', $colunas);
$temObservacao     = in_array('observacao', $colunas);

// Monta INSERT dinamicamente conforme colunas existentes
$cols   = "usuario_id, total, mesa, status";
$vals   = "?, ?, ?, 'Pendente'";
$types  = "ids";   // i=usuario_id, d=total, s=mesa (aceita NULL como string)
$params = [&$usuario_id, &$total, &$mesa];

if ($temNomeAnonimo) { $cols .= ", nome_anonimo"; $vals .= ", ?"; $types .= "s"; $params[] = &$nomeAnonimo; }
if ($temObservacao)  { $cols .= ", observacao";   $vals .= ", ?"; $types .= "s"; $params[] = &$observacao; }

if ($temColunaItens) {
    $itensJson = json_encode(array_map(fn($i) => [
        'nome'       => $i['nome'],
        'preco'      => (float)$i['preco'],
        'quantidade' => (int)$i['quantidade'],
    ], $itens));
    $cols .= ", itens"; $vals .= ", ?"; $types .= "s"; $params[] = &$itensJson;
}

$stmt = $conn->prepare("INSERT INTO pedidos ($cols) VALUES ($vals)");
if (!$stmt) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro prepare pedido: " . $conn->error]);
    exit;
}

// bind dinâmico
array_unshift($params, $types);
call_user_func_array([$stmt, 'bind_param'], $params);

if (!$stmt->execute()) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro execute pedido: " . $stmt->error]);
    exit;
}

$pedido_id = $stmt->insert_id;
$stmt->close();

// Detecta nome correto da tabela de itens
$tabItens = 'itens_pedido';
$r1 = $conn->query("SHOW TABLES LIKE 'itens_pedido'");
$r2 = $conn->query("SHOW TABLES LIKE 'itens_pedidos'");
if ((!$r1 || $r1->num_rows === 0) && $r2 && $r2->num_rows > 0) {
    $tabItens = 'itens_pedidos';
}

$stmtItem = $conn->prepare("INSERT INTO $tabItens (pedido_id, nome, preco, quantidade) VALUES (?, ?, ?, ?)");
if (!$stmtItem) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro prepare itens ($tabItens): " . $conn->error]);
    exit;
}

foreach ($itens as $item) {
    $nome  = $item['nome'];
    $preco = (float)$item['preco'];
    $qtd   = (int)$item['quantidade'];
    $stmtItem->bind_param("isdi", $pedido_id, $nome, $preco, $qtd);
    if (!$stmtItem->execute()) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro item: " . $stmtItem->error]);
        exit;
    }
}

$stmtItem->close();
$conn->close();

echo json_encode(["sucesso" => true, "pedido_id" => $pedido_id]);
