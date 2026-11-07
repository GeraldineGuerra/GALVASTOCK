# GalvaStock - Actividad 4: Prototipo funcional

Prototipo académico desarrollado con PHP, MySQL, HTML y CSS, pensado para ejecutarse localmente con XAMPP.

## Funcionalidades incluidas
- Registro, consulta y edición de productos.
- Registro de saldo inicial.
- Entradas y devoluciones aptas que aumentan existencias.
- Devoluciones no aptas que quedan registradas sin aumentar stock.
- Despachos que descuentan existencias y bloquean cantidades superiores al stock disponible.
- Ajustes/correcciones con motivo obligatorio.
- Traslados de ubicación.
- Historial de movimientos con stock anterior y posterior.

## Requisitos
- XAMPP con Apache, MySQL y PHP.
- Navegador web.
- Editor de código (opcional: Visual Studio Code).

## Instalación
1. Descarga y descomprime la carpeta `GALVASTOCK_ACTIVIDAD_4`.
2. Copia la carpeta dentro de `C:\xampp\htdocs\`.
3. Abre el Panel de Control de XAMPP e inicia Apache y MySQL.
4. En el navegador abre `http://localhost/phpmyadmin`.
5. Selecciona la pestaña **Importar** y carga el archivo `database/galvastock.sql`.
6. Verifica que se haya creado la base de datos `galvastock` y sus tablas.
7. Si tu MySQL tiene una contraseña para `root`, abre `config/conexion.php` y actualiza la variable `$pass`.
8. Abre `http://localhost/GALVASTOCK_ACTIVIDAD_4/`.

## Prueba rápida sugerida
1. Registra un producto con código `GAL-001`, nombre `Tubo galvanizado`, tipo `Galvanizado`, existencia inicial `20` y ubicación `Zona A - Estante 01`.
2. Registra una entrada de 5 unidades: la existencia debe quedar en 25.
3. Registra un despacho de 7 unidades: la existencia debe quedar en 18.
4. Intenta despachar 19 unidades: el sistema debe rechazar la operación y conservar 18.
5. Registra una devolución apta de 2 unidades: la existencia debe quedar en 20.
6. Registra una devolución no apta de 1 unidad: la existencia debe permanecer en 20.
7. Registra un traslado a otra ubicación y verifica el historial.
8. Revisa el historial de movimientos para comprobar el stock anterior y posterior.

## Reglas relevantes
- No se permiten cantidades iguales a cero o negativas.
- No se permiten despachos superiores al stock disponible.
- Los movimientos requieren una observación.
- La existencia se modifica desde los movimientos o despachos, no desde el formulario de edición.
- El prototipo es académico y debe validarse con datos de prueba; no está conectado a una empresa real.

## Estructura
- `config/`: conexión a MySQL.
- `database/`: script SQL de creación de la base de datos.
- `includes/`: encabezado y pie de página.
- `productos/`: registro, consulta y edición.
- `despachos/`: registro y consulta de despachos.
- `movimientos/`: entradas, devoluciones, ajustes, traslados e historial.
- `assets/`: estilos visuales.
