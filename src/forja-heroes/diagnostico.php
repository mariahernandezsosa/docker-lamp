<?php
declare(strict_types=1);

// Cambia la zona horaria temporalmente y luego la restaura.
$zonaOriginal  = ini_get('date.timezone');
$horaAntes     = date('H:i:s (T, P)');
$valorDevuelto = ini_set('date.timezone', 'America/Vancouver');
$zonaNueva     = ini_get('date.timezone');
$horaDespues   = date('H:i:s (T, P)');
ini_restore('date.timezone');
$zonaRestaurada = ini_get('date.timezone');
$horaRestaurada = date('H:i:s (T, P)');

// Lee las directivas del servidor.
$dirDisplayErrors = ini_get('display_errors');
$dirErrorReporting = ini_get('error_reporting');
$dirTimezone      = ini_get('date.timezone');
$dirShortOpenTag  = ini_get('short_open_tag');
$dirExposePhp     = ini_get('expose_php');
$dirMemoryLimit   = ini_get('memory_limit');

$esErrorReportingAll = error_reporting() === E_ALL;
$textoErrorReporting = $esErrorReportingAll ? 'Sí, es E_ALL' : 'No, no es E_ALL';

// Obtiene los ficheros de configuración cargados.
$iniPrincipal = php_ini_loaded_file() ?: '(ninguno: PHP usa sus valores por defecto)';
$iniExtra     = php_ini_scanned_files() ?: '(ninguno)';

// Intenta cambiar short_open_tag durante la ejecución.
$resultadoShortTag = ini_set('short_open_tag', '1');
$shortTagDespues   = ini_get('short_open_tag');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diagnóstico</title>
</head>
<body>
    <h2>Directivas del servidor (ini_get)</h2>
    <table>
        <tr><th>Directiva</th><th>Valor</th></tr>
        <tr><td>display_errors</td><td><?= htmlspecialchars(var_export($dirDisplayErrors, true)) ?></td></tr>
        <tr><td>error_reporting</td><td><?= htmlspecialchars(var_export($dirErrorReporting, true)) ?></td></tr>
        <tr><td>date.timezone</td><td><?= htmlspecialchars(var_export($dirTimezone, true)) ?></td></tr>
        <tr><td>short_open_tag</td><td><?= htmlspecialchars(var_export($dirShortOpenTag, true)) ?></td></tr>
        <tr><td>expose_php</td><td><?= htmlspecialchars(var_export($dirExposePhp, true)) ?></td></tr>
        <tr><td>memory_limit</td><td><?= htmlspecialchars(var_export($dirMemoryLimit, true)) ?></td></tr>
    </table>
    <p>¿error_reporting() === E_ALL? <strong><?= $textoErrorReporting ?></strong></p>

    <h2>Ficheros .ini cargados</h2>
    <p>Fichero principal: <code><?= htmlspecialchars($iniPrincipal) ?></code></p>
    <p>Ficheros adicionales:</p>
    <pre><?= htmlspecialchars($iniExtra) ?></pre>

    <h2>Experimento con date.timezone</h2>
    <table>
        <tr><th>Paso</th><th>Zona</th><th>Hora</th></tr>
        <tr><td>Antes</td><td><?= htmlspecialchars((string) $zonaOriginal) ?></td><td><?= $horaAntes ?></td></tr>
        <tr><td>Después de ini_set</td><td><?= htmlspecialchars((string) $zonaNueva) ?></td><td><?= $horaDespues ?></td></tr>
        <tr><td>Tras ini_restore</td><td><?= htmlspecialchars((string) $zonaRestaurada) ?></td><td><?= $horaRestaurada ?></td></tr>
    </table>
    <p>Valor devuelto por ini_set: <code><?= htmlspecialchars(var_export($valorDevuelto, true)) ?></code></p>

    <h2>Intento de cambiar short_open_tag</h2>
    <p>Resultado de ini_set: <code><?= var_export($resultadoShortTag, true) ?></code></p>
    <p>Valor después del intento: <code><?= htmlspecialchars(var_export($shortTagDespues, true)) ?></code></p>
</body>
</html>