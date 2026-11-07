
<?php
require_once '../config/conexion.php';

$pageTitle = 'Salidas';

$products = $pdo->query(
    "SELECT id, codigo, nombre, stock, unidad
     FROM productos ORDER BY nombre"
)->fetchAll();

$rows = $pdo->query(
    "SELECT s.*, p.codigo, p.nombre
     FROM salidas s
     INNER JOIN productos p ON p.id = s.producto_id
     ORDER BY s.id DESC"
)->fetchAll();

include '../includes/header.php';
?>

<div class="page-head">
    <div>
        <p class="eyebrow">INVENTARIO</p>
        <h1>Salidas</h1>
        <p>Registra retiros de productos del inventario.</p>
    </div>
</div>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert success">
        Salida registrada y existencias actualizadas.
    </div>
<?php endif; ?>

<section class="panel form-panel">
    <h2>Registrar salida</h2>

    <form method="post" action="guardar.php">
        <div class="form-grid">

            <label>Producto
                <select name="producto_id" required>
                    <option value="">Selecciona un producto</option>

                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars(
                                $p['codigo'].' - '.$p['nombre'].' (stock: '.$p['stock'].')'
                            ) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </label>

            <label>Cantidad a retirar
                <input type="number" name="cantidad"
                       min="1" step="1" required>
            </label>

            <label>Destino o motivo
                <input type="text" name="destino"
                       maxlength="150">
            </label>

        </div>

        <button class="btn" type="submit">Registrar salida</button>
    </form>
</section>

<section class="panel">
    <h2>Historial de salidas</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Referencia</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Destino o motivo</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['fecha']) ?></td>
                        <td>#<?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['codigo'].' - '.$r['nombre']) ?></td>
                        <td><?= $r['cantidad'] ?></td>
                        <td><?= htmlspecialchars($r['destino'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="5" class="empty">
                            Aún no hay salidas registradas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

