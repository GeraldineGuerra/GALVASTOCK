
<?php
require_once '../config/conexion.php';

$pageTitle = 'Movimientos';

$products = $pdo->query(
    "SELECT id, codigo, nombre, stock, ubicacion
     FROM productos
     ORDER BY nombre"
)->fetchAll();

$rows = $pdo->query(
    "SELECT m.*, p.codigo, p.nombre
     FROM movimientos m
     INNER JOIN productos p ON p.id = m.producto_id
     ORDER BY m.id DESC
     LIMIT 100"
)->fetchAll();

include '../includes/header.php';
?>

<div class="page-head">
    <div>
        <p class="eyebrow">TRAZABILIDAD</p>
        <h1>Movimientos de inventario</h1>
        <p>Registra entradas, devoluciones, ajustes y traslados. Cada operación queda en el historial.</p>
    </div>
</div>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert success">
        Movimiento registrado correctamente.
    </div>
<?php endif; ?>

<section class="panel form-panel">
    <h2>Registrar movimiento</h2>

    <form method="post" action="guardar.php">
        <div class="form-grid">

            <label>Producto
                <select name="producto_id" required>
                    <option value="">Selecciona un producto</option>

                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars(
                                $p['codigo'].' - '.$p['nombre'].' ('.$p['stock'].' disponibles)'
                            ) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Tipo de movimiento
                <select name="tipo" required>
                    <option value="entrada">Entrada</option>
                    <option value="devolucion_apta">Devolución apta</option>
                    <option value="devolucion_no_apta">Devolución no apta</option>
                    <option value="ajuste">Corrección / ajuste</option>
                    <option value="traslado">Traslado de ubicación</option>
                </select>
            </label>

            <label>Cantidad
    <input
        type="text"
        name="cantidad"
        id="cantidad"
        required
        autocomplete="off"
        value=""
        placeholder="Escribe una cantidad"
    >
    <small>
        En ajuste, escribe la nueva existencia total.
        En los demás movimientos, escribe la cantidad correspondiente.
    </small>
</label>

            <label>Nueva ubicación (solo traslado)
                <input
                    type="text"
                    name="ubicacion_destino"
                    placeholder="Ej. Zona B - Estante 01"
                    autocomplete="off"
                >
            </label>

            <label class="wide">Motivo u observación
                <input
                    type="text"
                    name="observacion"
                    required
                    maxlength="255"
                    placeholder="Describe el motivo del movimiento"
                >
            </label>

        </div>

        <p class="hint">
            Las devoluciones no aptas se registran, pero no aumentan el stock disponible.
        </p>

        <button class="btn" type="submit">
            Guardar movimiento
        </button>
    </form>
</section>

<section class="panel">
    <h2>Historial de movimientos</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Antes</th>
                    <th>Después</th>
                    <th>Ubicación anterior</th>
                    <th>Ubicación nueva</th>
                    <th>Observación</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['fecha']) ?></td>
                        <td>
                            <?= htmlspecialchars($r['codigo'].' - '.$r['nombre']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars(str_replace('_', ' ', $r['tipo'])) ?>
                        </td>
                        <td><?= $r['cantidad'] ?></td>
                        <td><?= $r['stock_antes'] ?></td>
                        <td><?= $r['stock_despues'] ?></td>
                        <td><?= htmlspecialchars($r['ubicacion_origen'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['ubicacion_destino'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['observacion'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="9" class="empty">
                            No hay movimientos registrados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
