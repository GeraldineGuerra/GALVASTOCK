
<?php
require_once '../config/conexion.php';

$pid = (int)($_POST['producto_id'] ?? 0);
$qty = (int)($_POST['cantidad'] ?? 0);
$client = trim($_POST['cliente'] ?? '');
$obs = trim($_POST['observacion'] ?? '');
if ($obs === '') {
    $obs = 'Sin observaciones';
}
if (!$pid || $qty <= 0 || !$client) {
    exit('Datos inválidos.');
}

try {
    $pdo->beginTransaction();

    // Consultar el producto y bloquearlo durante la operación
    $st = $pdo->prepare("SELECT * FROM productos WHERE id = ? FOR UPDATE");
    $st->execute([$pid]);
    $p = $st->fetch();

    if (!$p) {
        throw new Exception('Producto no encontrado.');
    }

    if ($qty > (int)$p['stock']) {
        throw new Exception(
            'No es posible despachar más unidades de las disponibles. Stock actual: ' . $p['stock']
        );
    }

    // Calcular y actualizar existencias
    $before = (int)$p['stock'];
    $after = $before - $qty;

    $u = $pdo->prepare("UPDATE productos SET stock = ? WHERE id = ?");
    $u->execute([$after, $pid]);

    // Registrar el despacho
    $d = $pdo->prepare(
        "INSERT INTO despachos (producto_id, cliente, producto, cantidad, estado, fecha, observacion)
         VALUES (?, ?, ?, ?, 'completado', CURDATE(), ?)"
    );

    $d->execute([
        $pid,
        $client,
        $p['nombre'],
        $qty,
        $obs
    ]);

    $did = (int)$pdo->lastInsertId();

    // Registrar el movimiento de inventario
    $m = $pdo->prepare(
        "INSERT INTO movimientos
         (producto_id, tipo, cantidad, stock_antes, stock_despues, referencia, observacion)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $m->execute([
        $pid,
        'salida_despacho',
        $qty,
        $before,
        $after,
        'Despacho #' . $did,
        $obs
    ]);

    $pdo->commit();

    header('Location: index.php?ok=1');
    exit;

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    exit(htmlspecialchars($e->getMessage()));
}
