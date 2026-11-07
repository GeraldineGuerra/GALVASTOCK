
<?php
require_once '../config/conexion.php';

$pageTitle = 'Reportes';

// Total de productos registrados
$totalProductos = $pdo->query(
    "SELECT COUNT(*) FROM productos"
)->fetchColumn();

// Total de unidades disponibles
$totalStock = $pdo->query(
    "SELECT COALESCE(SUM(stock), 0) FROM productos"
)->fetchColumn();

// Total de entradas
$totalEntradas = $pdo->query(
    "SELECT COALESCE(SUM(cantidad), 0) FROM entradas"
)->fetchColumn();

// Total de salidas
$totalSalidas = $pdo->query(
    "SELECT COALESCE(SUM(cantidad), 0) FROM salidas"
)->fetchColumn();

// Productos con stock bajo (5 unidades o menos)
$stockBajo = $pdo->query(
    "SELECT codigo, nombre, stock, unidad, ubicacion
     FROM productos
     WHERE stock <= 5
     ORDER BY stock ASC"
)->fetchAll();

// Últimos movimientos
$movimientos = $pdo->query(
    "SELECT m.*, p.codigo, p.nombre
     FROM movimientos m
     INNER JOIN productos p ON p.id = m.producto_id
     ORDER BY m.id DESC
     LIMIT 10"
)->fetchAll();

include '../includes/header.php';
?>

<div class="page-head">
    <div>
        <p class="eyebrow">CONSULTAS Y ESTADÍSTICAS</p>
        <h1>Reportes de inventario</h1>
        <p>Consulta el estado general y los movimientos del inventario.</p>
    </div>
</div>

<section class="grid">
    <article class="panel">
        <h2>Total de productos</h2>
        <h1><?= $totalProductos ?></h1>
    </article>

    <article class="panel">
        <h2>Existencias disponibles</h2>
        <h1><?= $totalStock ?></h1>
    </article>

    <article class="panel">
        <h2>Unidades ingresadas</h2>
        <h1><?= $totalEntradas ?></h1>
    </article>

    <article class="panel">
        <h2>Unidades retiradas</h2>
        <h1><?= $totalSalidas ?></h1>
    </article>
</section>

<section class="panel">
    <h2>Productos con stock bajo</h2>
    <p>Productos que tienen 5 unidades o menos disponibles.</p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Stock</th>
                    <th>Unidad</th>
                    <th>Ubicación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stockBajo as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['codigo']) ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= $p['stock'] ?></td>
                        <td><?= htmlspecialchars($p['unidad']) ?></td>
                        <td><?= htmlspecialchars($p['ubicacion'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$stockBajo): ?>
                    <tr>
                        <td colspan="5" class="empty">
                            No hay productos con stock bajo.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="panel">
    <h2>Últimos 10 movimientos</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Stock anterior</th>
                    <th>Stock actual</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movimientos as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['fecha']) ?></td>
                        <td><?= htmlspecialchars($m['codigo'].' - '.$m['nombre']) ?></td>
                        <td><?= htmlspecialchars(str_replace('_', ' ', $m['tipo'])) ?></td>
                        <td><?= $m['cantidad'] ?></td>
                        <td><?= $m['stock_antes'] ?></td>
                        <td><?= $m['stock_despues'] ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$movimientos): ?>
                    <tr>
                        <td colspan="6" class="empty">
                            No hay movimientos registrados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
