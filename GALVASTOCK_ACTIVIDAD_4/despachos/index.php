<?php
require_once '../config/conexion.php';$pageTitle='Despachos';
$products=$pdo->query("SELECT id,codigo,nombre,stock,unidad FROM productos ORDER BY nombre")->fetchAll();
$rows=$pdo->query("SELECT d.*,p.codigo,p.nombre FROM despachos d LEFT JOIN productos p ON p.id=d.producto_id ORDER BY d.id DESC")->fetchAll();
include '../includes/header.php';
?>
<div class="page-head"><div><p class="eyebrow">SALIDAS</p><h1>Despachos</h1><p>Registra entregas y descuenta automáticamente las existencias.</p></div></div>
<?php if(isset($_GET['ok'])): ?><div class="alert success">Despacho registrado y existencias actualizadas.</div><?php endif; ?>
<section class="panel form-panel"><h2>Registrar despacho</h2><form method="post" action="guardar.php"><div class="form-grid">
<label>Cliente o destinatario<input name="cliente" required maxlength="120"></label>
<label>Producto<select name="producto_id" required><option value="">Selecciona un producto</option><?php foreach($products as $p): ?><option value="<?=$p['id']?>"><?=htmlspecialchars($p['codigo'].' - '.$p['nombre'].' (stock: '.$p['stock'].')')?></option><?php endforeach; ?></select></label>
<label>Cantidad a despachar<input type="number" name="cantidad" min="1" step="1" required></label>
<label>Observación<input name="observacion" maxlength="255"></label>
</div><button class="btn" type="submit">Confirmar despacho</button></form></section>
<section class="panel"><h2>Historial de despachos</h2><div class="table-wrap"><table><thead><tr><th>Fecha</th><th>Referencia</th><th>Cliente</th><th>Producto</th><th>Cantidad</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?=htmlspecialchars($r['fecha'])?></td><td>#<?=$r['id']?></td><td><?=htmlspecialchars($r['cliente'])?></td><td><?=htmlspecialchars($r['codigo'].' - '.$r['nombre'])?></td><td><?=$r['cantidad']?></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="5" class="empty">Aún no hay despachos.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php include '../includes/footer.php'; ?>
