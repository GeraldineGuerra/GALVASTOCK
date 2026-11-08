<?php

require_once '../config/conexion.php';

$pageTitle = 'Productos';

$rows = $pdo->query("
    SELECT *
    FROM productos
    ORDER BY nombre
")->fetchAll();

include '../includes/header.php';

?>

<div class="page-head">

    <div>
        <p class="eyebrow">CATÁLOGO</p>
        <h1>Productos</h1>
        <p>Administra referencias, existencias y ubicación.</p>
    </div>

    <a class="btn" href="formulario.php">
        + Nuevo producto
    </a>

</div>

<?php if (isset($_GET['ok'])): ?>

    <div class="alert success">
        Producto guardado correctamente.
    </div>

<?php endif; ?>

<section class="panel">

    <div class="table-wrap">

        <table>

            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Tipo</th>
                    <th>Stock</th>
                    <th>Ubicación</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($rows as $r): ?>

                    <tr>

                        <td>
                            <b><?= htmlspecialchars($r['codigo']) ?></b>
                        </td>

                        <td>
                            <?= htmlspecialchars($r['nombre']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($r['tipo_terminado']) ?>
                        </td>

                        <td>
                            <span class="stock-pill">
                                <?= $r['stock'] ?>
                            </span>
                        </td>

                        <td>
                            <?= htmlspecialchars($r['ubicacion']) ?>
                        </td>

                        <td>
                            <a
                                class="link"
                                href="formulario.php?id=<?= $r['id'] ?>"
                            >
                                Editar
                            </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

                <?php if (!$rows): ?>

                    <tr>
                        <td colspan="6" class="empty">
                            No hay productos. Registra el primero.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>

<?php include '../includes/footer.php'; ?>