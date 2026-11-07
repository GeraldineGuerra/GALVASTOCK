<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$pageTitle = $pageTitle ?? 'GalvaStock';
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle) ?> | GalvaStock</title>
<link rel="stylesheet" href="/GALVASTOCK_ACTIVIDAD_4/assets/estilos.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="/GALVASTOCK_ACTIVIDAD_4/dashboard.php"><span class="brand-mark">G</span><span>GalvaStock<small>Inventario de terminados galvánicos</small></span></a>
  
<nav>
    <a href="/GALVASTOCK_ACTIVIDAD_4/dashboard.php">Inicio</a>
    <a href="/GALVASTOCK_ACTIVIDAD_4/productos/index.php">Productos</a>
    <a href="/GALVASTOCK_ACTIVIDAD_4/entradas/index.php">Entradas</a>
    <a href="/GALVASTOCK_ACTIVIDAD_4/despachos/index.php">Despachos</a>
    <a href="/GALVASTOCK_ACTIVIDAD_4/salidas/index.php">Salidas</a>
    <a href="/GALVASTOCK_ACTIVIDAD_4/movimientos/index.php">Movimientos</a>
    <a href="/GALVASTOCK_ACTIVIDAD_4/reportes/index.php">Reportes</a>
</nav>


</header>
<main class="container">
