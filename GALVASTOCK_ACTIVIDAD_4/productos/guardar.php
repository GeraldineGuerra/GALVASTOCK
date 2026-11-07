<?php
require_once '../config/conexion.php';
$id=(int)($_POST['id']??0);$codigo=trim($_POST['codigo']??'');$nombre=trim($_POST['nombre']??'');$tipo=trim($_POST['tipo_terminado']??'');$unidad=$_POST['unidad']??'unidad';$ubicacion=trim($_POST['ubicacion']??'');$stock=(int)($_POST['stock']??0);
if(!$codigo||!$nombre||!$tipo||!$ubicacion||$stock<0)exit('Datos inválidos. Verifique los campos.');
try{
 if($id){$st=$pdo->prepare("UPDATE productos SET codigo=?,nombre=?,tipo_terminado=?,unidad=?,ubicacion=? WHERE id=?");$st->execute([$codigo,$nombre,$tipo,$unidad,$ubicacion,$id]);}
 else{$pdo->beginTransaction();$st=$pdo->prepare("INSERT INTO productos(codigo,nombre,tipo_terminado,unidad,stock,ubicacion) VALUES(?,?,?,?,?,?)");$st->execute([$codigo,$nombre,$tipo,$unidad,$stock,$ubicacion]);$pid=(int)$pdo->lastInsertId();if($stock>0){$m=$pdo->prepare("INSERT INTO movimientos(producto_id,tipo,cantidad,stock_antes,stock_despues,ubicacion_origen,ubicacion_destino,observacion) VALUES(?,?,?,?,?,?,?,?)");$m->execute([$pid,'saldo_inicial',$stock,0,$stock,'',$ubicacion,'Registro inicial del producto']);}$pdo->commit();}
 header('Location: index.php?ok=1');exit;
}catch(PDOException $e){if($pdo->inTransaction())$pdo->rollBack();if($e->getCode()==='23000')exit('El código del producto ya existe.');throw $e;}
