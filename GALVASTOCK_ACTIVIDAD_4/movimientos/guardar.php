
<?php
require_once '../config/conexion.php';

$pid = (int)($_POST['producto_id'] ?? 0);
$type = $_POST['tipo'] ?? '';
$qty = (int)($_POST['cantidad'] ?? 0);
$dest = trim($_POST['ubicacion_destino'] ?? '');
$obs = trim($_POST['observacion'] ?? '');

$allowed = [
    'entrada',
    'devolucion_apta',
    'devolucion_no_apta',
    'ajuste',
    'traslado'
];

if (
    $pid <= 0 ||
    !in_array($type, $allowed, true) ||
    $qty <= 0 ||
    !$obs
) {
    exit('Datos inválidos. Verifique la información.');
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
    $after = $before;
    $movementQty = $qty;
    $origin = $p['ubicacion'];
    $destination = null;

    switch ($type) {

        case 'entrada':
        case 'devolucion_apta':
            $after = $before + $qty;
            break;

        case 'devolucion_no_apta':
            $after = $before;
            break;

        case 'ajuste':
            $after = $qty;

            if ($after === $before) {
                throw new Exception(
                    'La nueva existencia debe ser diferente al stock actual.'
                );
            }

            $movementQty = abs($after - $before);
            break;

        case 'traslado':
            if (!$dest) {
                throw new Exception(
                    'Indique la nueva ubicación.'
                );
            }

            if ($dest === $origin) {
                throw new Exception(
                    'La nueva ubicación debe ser diferente a la actual.'
                );
            }

            $destination = $dest;
            break;
    }

    if ($type === 'traslado') {

        $u = $pdo->prepare(
            "UPDATE productos SET ubicacion = ? WHERE id = ?"
        );
        $u->execute([$dest, $pid]);

    } else {

        $u = $pdo->prepare(
            "UPDATE productos SET stock = ? WHERE id = ?"
        );
        $u->execute([$after, $pid]);
    }

    $m = $pdo->prepare(
        "INSERT INTO movimientos
        (producto_id, tipo, cantidad, stock_antes, stock_despues,
         ubicacion_origen, ubicacion_destino, observacion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $m->execute([
        $pid,
        $type,
        $movementQty,
        $before,
        $after,
        $origin,
        $destination,
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
