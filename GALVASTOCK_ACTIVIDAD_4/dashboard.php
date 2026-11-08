<?php

require_once 'config/conexion.php';

$pageTitle = 'Panel principal';

$total = (int) $pdo->query("
    SELECT COUNT(*)
    FROM productos
")->fetchColumn();

$stock = (int) $pdo->query("
    SELECT COALESCE(SUM(stock), 0)
    FROM productos
")->fetchColumn();

$dispatches = (int) $pdo->query("
    SELECT COUNT(*)
    FROM despachos
")->fetchColumn();

$low = $pdo->query("
    SELECT codigo, nombre, stock, ubicacion
    FROM productos
    WHERE stock <= 5
    ORDER BY stock ASC
    LIMIT 5
")->fetchAll();

$recent = $pdo->query("
    SELECT
        m.fecha,
        m.tipo,
        m.cantidad,
        m.stock_despues,
        p.codigo,
        p.nombre
    FROM movimientos m
    JOIN productos p ON p.id = m.producto_id
    ORDER BY m.id DESC
    LIMIT 7
")->fetchAll();

include 'includes/header.php';

?>

<section class="hero">

    <div>
        <p class="eyebrow">PANEL DE CONTROL</p>

        <h1>Inventario bajo control.</h1>

        <p>
            Consulta productos, existencias, ubicaciones y movimientos
            desde un solo lugar.
        </p>
    </div>

    <a class="btn btn-light" href="productos/formulario.php">
        + Registrar producto
    </a>

</section>

<div class="stats">

    <article class="stat">
        <span>Productos registrados</span>
        <strong><?= $total ?></strong>
        <small>Referencias activas</small>
    </article>

    <article class="stat">
        <span>Unidades en inventario</span>
        <strong><?= $stock ?></strong>
        <small>Existencias actuales</small>
    </article>

    <article class="stat">
        <span>Despachos registrados</span>
        <strong><?= $dispatches ?></strong>
        <small>Operaciones guardadas</small>
    </article>

</div>

<div class="two-col">

    <section class="panel">

        <div class="panel-head">
            <h2>Movimientos recientes</h2>
            <a href="movimientos/index.php">Ver historial</a>
        </div>

        <?php if (!$recent): ?>

            <p class="empty">
                Todavía no hay movimientos registrados.
            </p>

        <?php else: ?>

            <div class="table-wrap">

                <table>

                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Movimiento</th>
                            <th>Cantidad</th>
                            <th>Stock final</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($recent as $r): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($r['fecha']) ?>
                                </td>

                                <td>
                                    <b>
                                        <?= htmlspecialchars($r['codigo']) ?>
                                    </b>

                                    <small>
                                        <?= htmlspecialchars($r['nombre']) ?>
                                    </small>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        str_replace('_', ' ', $r['tipo'])
                                    ) ?>
                                </td>

                                <td>
                                    <?= (int) $r['cantidad'] ?>
                                </td>

                                <td>
                                    <?= (int) $r['stock_despues'] ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>

    <section class="panel">

        <div class="panel-head">
            <h2>Existencias por revisar</h2>
            <span class="tag">Umbral: 5 unidades</span>
        </div>

        <?php if (!$low): ?>

            <p class="empty">
                No hay productos en nivel bajo.
            </p>

        <?php else: ?>

            <?php foreach ($low as $p): ?>

                <div class="low-item">

                    <div>
                        <b>
                            <?= htmlspecialchars($p['nombre']) ?>
                        </b>

                        <small>
                            <?= htmlspecialchars($p['codigo']) ?>
                            ·
                            <?= htmlspecialchars($p['ubicacion']) ?>
                        </small>
                    </div>

                    <strong>
                        <?= (int) $p['stock'] ?>
                    </strong>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </section>

</div>

<?php include 'includes/footer.php'; ?>