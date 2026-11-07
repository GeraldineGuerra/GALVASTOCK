
<?php
require_once '../config/conexion.php';

$pid = (int)($_POST['producto_id'] ?? 0);
$qty = (int)($_POST['cantidad'] ?? 0);
$obs = trim($_POST['observacion'] ?? '');

if ($obs === '') {
    $obs = 'Sin observaciones';
}

if ($pid <= 0 || $qty <= 0) {
    exit('Datos inválidos.');
}

try {
    $pdo->beginTransaction();

    $st = $pdo->prepare(
        "SELECT * FROM productos WHERE id = ? FOR UPDATE"
    );
    $st->execute([$pid]);
    $p = $st->fetch();

    if (!$p) {
        throw new Exception('Producto no encontrado.');
    }

    $before = (int)$p['stock'];
    $after = $before + $qty;

    $u = $pdo->prepare(
        "UPDATE productos SET stock = ? WHERE id = ?"
    );
    $u->execute([$after, $pid]);

    $e = $pdo->prepare(
        "INSERT INTO entradas (producto_id, cantidad, observacion)
         VALUES (?, ?, ?)"
    );
    $e->execute([$pid, $qty, $obs]);

    $eid = (int)$pdo->lastInsertId();

    $m = $pdo->prepare(
        "INSERT INTO movimientos
        (producto_id, tipo, cantidad, stock_antes, stock_despues, referencia, observacion)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $m->execute([
        $pid,
        'entrada',
        $qty,
        $before,
        $after,
        'Entrada #' . $eid,
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
