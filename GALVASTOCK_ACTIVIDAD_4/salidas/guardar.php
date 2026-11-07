
<?php
require_once '../config/conexion.php';

$pid = (int)($_POST['producto_id'] ?? 0);
$qty = (int)($_POST['cantidad'] ?? 0);
$destino = trim($_POST['destino'] ?? '');

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

    if ($qty > (int)$p['stock']) {
        throw new Exception(
            'Stock insuficiente. Existencias actuales: '.$p['stock']
        );
    }

    $before = (int)$p['stock'];
    $after = $before - $qty;

    $u = $pdo->prepare(
        "UPDATE productos SET stock = ? WHERE id = ?"
    );
    $u->execute([$after, $pid]);

    $s = $pdo->prepare(
        "INSERT INTO salidas (producto_id, cantidad, destino)
         VALUES (?, ?, ?)"
    );
    $s->execute([$pid, $qty, $destino]);

    $sid = (int)$pdo->lastInsertId();

    $m = $pdo->prepare(
        "INSERT INTO movimientos
        (producto_id, tipo, cantidad, stock_antes, stock_despues,
         referencia, observacion)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $m->execute([
        $pid,
        'salida',
        $qty,
        $before,
        $after,
        'Salida #'.$sid,
        $destino !== '' ? $destino : 'Sin observaciones'
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

