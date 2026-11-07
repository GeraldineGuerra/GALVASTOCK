
<?php
require_once '../config/conexion.php';

$pageTitle = 'Entradas';

$products = $pdo->query(
    "SELECT id, codigo, nombre, stock, unidad
     FROM productos ORDER BY nombre"
)->fetchAll();

$rows = $pdo->query(
    "SELECT e.*, p.codigo, p.nombre
     FROM entradas e
     INNER JOIN productos p ON p.id = e.producto_id
     ORDER BY e.id DESC"
)->fetchAll();

include '../includes/header.php';
?>

<div class="page-head">
    <div>
        <p class="eyebrow">INVENTARIO</p>
        <h1>Entradas</h1>
        <p>Registra el ingreso de productos al inventario.</p>
    </div>
</div>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert success">
        Entrada registrada y existencias actualizadas.
    </div>
<?php endif; ?>

<section class="panel form-panel">
    <h2>Registrar entrada</h2>

    <form method="post" action="guardar.php">
        <div class="form-grid">

            <label>Producto
                <select name="producto_id" required>
                    <option value="">Selecciona un producto</option>

                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars($p['codigo'].' - '.$p['nombre'].' (stock: '.$p['stock'].')') ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </label>

            <label>Cantidad recibida
                <input type="number" name="cantidad" min="1" step="1" required>
            </label>

            <label>Observación
                <input type="text" name="observacion" maxlength="255">
            </label>

        </div>

        <button class="btn" type="submit">Registrar entrada</button>
    </form>
</section>

<section class="panel">
    <h2>Historial de entradas</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Referencia</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Observación</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['fecha']) ?></td>
                        <td>#<?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['codigo'].' - '.$r['nombre']) ?></td>
                        <td><?= $r['cantidad'] ?></td>
                        <td><?= htmlspecialchars($r['observacion'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="5" class="empty">Aún no hay entradas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
