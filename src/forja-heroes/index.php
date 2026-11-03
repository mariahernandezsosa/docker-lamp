<?php

/**
 * =====================================================================
 *  ficha.php · Forja de Héroes · UD2 · Reto evaluable
 * =====================================================================
 *  Autor/a: María · Variante: TODO
 *
 *  Estructura OBLIGATORIA (como en el paso 09 del caso guiado):
 *   1) directiva strict_types
 *   2) carga de inc/heroe.php con require_once y __DIR__
 *   3) CÁLCULOS: aquí arriba, sin HTML
 *   4) PRESENTACIÓN: HTML con los valores incrustados
 *
 *  PROHIBIDO en este reto: if, else, switch, match, bucles, arrays propios
 *  y funciones propias (llegan en la UD3 y la UD4). Usa operadores,
 *  el ternario y los operadores ?? y ?:.
 */

// TODO 1. strict_types
declare(strict_types=1);

// TODO 2. require_once del fichero de datos
require_once __DIR__ . '/inc/heroe.php';

// ---------------------------------------------------------------------
// TODO 3. CÁLCULOS (sigue el orden del enunciado: R1 a R8)
// ---------------------------------------------------------------------
// R1 · Conversión de tipos
$fuerza = (int) $fuerzaTxt;
$destreza = (int) $destrezaTxt;
$inteligencia = (int) $inteligenciaTxt;
$constitucion = (int) $constitucionTxt;
$experiencia = (int) $experienciaTxt;
$vidaActual = (int) $vidaActualTxt;
$oro = (float) $oroTxt;

// R2 · Progresión (nivel, xpEnNivel, xpParaSubir, pctNivel)
$nivel = intdiv($experiencia, XP_POR_NIVEL) + 1;
$xpEnNivel = $experiencia % XP_POR_NIVEL;
$xpParaSubir = XP_POR_NIVEL - $xpEnNivel;
$pctNivel = ($xpEnNivel / XP_POR_NIVEL) * 100;

// R3 · Vida (vidaMax, pctVida)
$vidaMax = VIDA_BASE + ($constitucion * $nivel * MULT_VIDA);
$pctVida = ($vidaActual / $vidaMax) * 100;


// R4 · Combate (daño, estadística especial, poder, comparación con el rival)
$danio = ($inteligencia * 3) + (($nivel ** 2) / 4);
$estadisticaEspecial = ($inteligencia * 10) + ($experiencia % 100);
$poder = (int) round($danio * $nivel);
$comparacion = $poder <=> PODER_RIVAL;
$veredicto = ($comparacion === 1)
    ? 'Ventaja: ¡a la carga!'
    : (($comparacion === 0) ? 'Empate: combate igualado' : 'Desventaja: mejor retirarse');

// R5 · Estado y decisiones
$estado = ($vidaActual <= 0)
    ? 'K.O'
    : (($pctVida < 35) ? 'En peligro' : 'En pie');
$puedeAscender = ($nivel >= 5 && $pctVida >= 50) ? 'Sí' : 'No';
$necesitaPocion = ($pctVida < 40 || !$esVeterano) ? 'Sí' : 'No';
$insignia = $esVeterano ? 'VETERANO' : 'NOVATO';

// R6 · Barras de progreso
$bloquesVidaLlenos = (int) ceil(($pctVida / 100) * BLOQUES_BARRA);
$barraVida = str_repeat('█', $bloquesVidaLlenos) . str_repeat('░', BLOQUES_BARRA - $bloquesVidaLlenos);
$bloquesXpLlenos = (int) round(($pctNivel / 100) * BLOQUES_BARRA);
$barraXp = str_repeat('█', $bloquesXpLlenos) . str_repeat('░', BLOQUES_BARRA - $bloquesXpLlenos);

// R7 · Textos (htmlspecialchars, ?? , ?: , number_format, .= , heredoc)
$nombreFormateado = htmlspecialchars($nombreHeroe, ENT_QUOTES, 'UTF-8');
$apodoTexto = $apodo ?? 'Sin apodo';
$lemaTexto = $lema ?: 'Sin lema (todavía)';
$oroFormateado = number_format($oro, 2, ',', '.') . ' mo';
$danioFormateado = number_format($danio, 1, ',', '.');
$pctVidaFormateado = number_format($pctVida, 1, ',', '.') . ' %';
$registroCombate = date('H:i') . ' - ';
$registroCombate .= $nombreFormateado . ' (Nivel ' . $nivel . ') se enfrenta a ' . NOMBRE_RIVAL . '. ';
$registroCombate .= 'Resultado: ' . $veredicto;
$cronica = $nombreFormateado . ' es ' . CLASE_HEROE . ' de nivel ' . $nivel . ".\n"
    . 'Le faltan ' . $xpParaSubir . " puntos de experiencia para subir de nivel.\n"
    . 'Estado actual: ' . $estado . '.';
?>


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha de <?= $nombreFormateado ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <main>
        <!-- TODO: insignia VETERANO/NOVATO, usando la etiqueta LARGA con echo -->
        <span class="insignia"><?php echo $insignia; ?></span>
        <!-- TODO: a partir de aquí, usa la etiqueta CORTA de salida -->
        <h1><?= $nombreFormateado ?></h1>
        <p class="subtitulo"><?= CLASE_HEROE ?></p>

        <h2>Estadísticas</h2>
        <div class="rejilla">
            <div class="stat">
                <div class="etq">Nivel</div>
                <div class="val"><?= $nivel ?></div>
            </div>
            <div class="stat">
                <div class="etq">Vida</div>
                <div class="val"><?= $vidaActual ?> / <?= $vidaMax ?></div>
            </div>
            <div class="stat">
                <div class="etq">Daño</div>
                <div class="val"><?= $danioFormateado ?></div>
            </div>
            <div class="stat">
                <div class="etq">Crítico/Especial</div>
                <div class="val"><?= $estadisticaEspecial ?>%</div>
            </div>
            <div class="stat">
                <div class="etq">Poder</div>
                <div class="val"><?= $poder ?></div>
            </div>
            <div class="stat">
                <div class="etq">Oro</div>
                <div class="val"><?= $oroFormateado ?></div>
            </div>
        </div>
        <table>
            <tr>
                <th>Fuerza</th>
                <th>Destreza</th>
                <th>Inteligencia</th>
                <th>Constitución</th>
            </tr>
            <tr>
                <td><?= $fuerza ?></td>
                <td><?= $destreza ?></td>
                <td><?= $inteligencia ?></td>
                <td><?= $constitucion ?></td>
            </tr>
        </table>

        <h2>Progreso</h2>
        <p class="barra vida">VIDA: <?= $barraVida ?> <?= $pctVidaFormateado ?> · <?= $bloquesVidaLlenos ?> / <?= BLOQUES_BARRA ?></p>
        <p class="barra xp">XP&nbsp;&nbsp; <?= $barraXp ?> · <?= $xpEnNivel ?>/<?= XP_POR_NIVEL ?> (faltan <?= $xpParaSubir ?>)</p>
        <h2>Estado y decisiones</h2>
        <table>
            <tr>
                <td>Estado</td>
                <td><?= $estado ?></td>
            </tr>
            <tr>
                <td>¿Puede ascender de rango? (nivel ≥ 5 y vida ≥ 50 %)</td>
                <td><?= $puedeAscender ?></td>
            </tr>
            <tr>
                <td>¿Necesita poción? (vida &lt; 40 % o no veterano)</td>
                <td><?= $necesitaPocion ?></td>
            </tr>
            <tr>
                <td>Rival: <?= NOMBRE_RIVAL ?> (poder <?= PODER_RIVAL ?>)</td>
                <td><?= $comparacion ?> → <?= $veredicto ?></td>
            </tr>
        </table>

        <h2>Crónica</h2>
        <pre><?= $cronica ?></pre>
        <p><em><?= $registroCombate ?></em></p>

        <h2>Modo depuración</h2>
        <pre>
        experienciaTxt: <?= get_debug_type($experienciaTxt) ?>
        experiencia: <?= get_debug_type($experiencia) ?>
        <?php
        var_dump($oro);
        var_dump($pctVida);
        var_dump($esVeterano);
        var_dump($apodo);
        ?>
        Comparación == : <?= var_export($experienciaTxt == $experiencia, true) ?>
        Comparación ===: <?= var_export($experienciaTxt === $experiencia, true) ?>
    </pre>

        <footer>
            Generado el <?= date('d/m/Y H:i:s') ?> con PHP <?= PHP_VERSION ?> |
            <a href="diagnostico.php">Diagnóstico del servidor</a>
        </footer>
    </main>
</body>

</html>