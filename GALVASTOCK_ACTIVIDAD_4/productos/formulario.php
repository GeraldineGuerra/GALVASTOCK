<?php
require_once '../config/conexion.php';
$id=(int)($_GET['id']??0); $p=['codigo'=>'','nombre'=>'','tipo_terminado'=>'','unidad'=>'unidad','stock'=>0,'ubicacion'=>''];
if($id){$st=$pdo->prepare("SELECT * FROM productos WHERE id=?");$st->execute([$id]);$p=$st->fetch();if(!$p)exit('Producto no encontrado.');}
$pageTitle=$id?'Editar producto':'Registrar producto'; include '../includes/header.php';
?>
<div class="page-head"><div><p class="eyebrow">CATÁLOGO</p><h1><?=$id?'Editar producto':'Registrar producto'?></h1><p>Completa los datos básicos del producto terminado.</p></div><a class="btn btn-outline" href="index.php">Volver</a></div>
<section class="panel form-panel"><form method="post" action="guardar.php">
<input type="hidden" name="id" value="<?=$id?>">
<div class="form-grid">
<label>Código único<input name="codigo" required maxlength="40" value="<?=htmlspecialchars($p['codigo'])?>"></label>
<label>Nombre del producto<input name="nombre" required maxlength="120" value="<?=htmlspecialchars($p['nombre'])?>"></label>
<label>Tipo de terminado<input name="tipo_terminado" required value="<?=htmlspecialchars($p['tipo_terminado'])?>"></label>
<label>Unidad de medida<select name="unidad"><option value="unidad" <?=$p['unidad']==='unidad'?'selected':''?>>Unidad</option><option value="kg" <?=$p['unidad']==='kg'?'selected':''?>>Kilogramo (kg)</option><option value="lote" <?=$p['unidad']==='lote'?'selected':''?>>Lote</option></select></label>
<label>Existencia inicial<input type="number" name="stock" min="0" step="1" required value="<?=$p['stock']?>" <?=$id?'readonly':''?>></label>
<label>Ubicación<input name="ubicacion" required value="<?=htmlspecialchars($p['ubicacion'])?>" placeholder="Ej. Zona A - Estante 02"></label>
</div><p class="hint"><?=$id?'La existencia se modifica desde el módulo Movimientos para conservar el historial.':'La existencia inicial se registrará como saldo de apertura.'?></p>
<button class="btn" type="submit">Guardar producto</button>
</form></section>
<?php include '../includes/footer.php'; ?>
