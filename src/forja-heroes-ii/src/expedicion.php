<?php
/**
 * =====================================================================
 *  expedicion.php · Forja de Héroes II · La party y la mazmorra
 * =====================================================================
 *  0613 DWES · UD3 · Tarea de refuerzo (sin nota)
 *  Autor/a: María Hernández · Variante: A
 *
 *  Estructura OBLIGATORIA (como en el paso 09 del caso guiado):
 *   1) strict_types y carga de inc/reglas.php y del fichero de datos
 *   2) CÁLCULOS: aquí arriba, sin HTML
 *   3) PRESENTACIÓN: HTML con sintaxis alternativa
 *      (foreach (...): ... endforeach;   if (...): ... else: ... endif;)
 *
 *  AHORA SÍ puedes usar if, switch, match, bucles y arrays.
 *  Todavía NO: funciones propias ni formularios (UD4).
 *
 *  R8 · Comenta cada bloque explicando QUÉ hace y POR QUÉ.
 *       Usa al menos una vez //, # y /* ... *\/.
 */

declare(strict_types=1);

require_once __DIR__ . '/inc/reglas.php';
require_once __DIR__ . '/inc/datos-' . VARIANTE . '.php';

// =====================================================================
// R2 · LA PARTY
// =====================================================================
// TODO 2.1  Recorre $party con  foreach ($party as $i => $heroe)  y calcula
//           para cada héroe: nivel, vidaMax (usa MULT_VIDA[clase]), pctVida.
    foreach ($party as $i => $heroe) {
        $nivel   = intdiv($heroe['xp'], XP_POR_NIVEL) + 1;
        $vidaMax = VIDA_BASE + $heroe['constitucion'] * $nivel * MULT_VIDA[$heroe['clase']];
        $pctVida = $heroe['vida'] / $vidaMax * 100;

// TODO 2.2  (R3) Daño con match sobre la clase. SIN default (lee el enunciado).
    $danio = match ($heroe['clase']) {
    'Guerrera'  => $heroe['fuerza'] ** 2 / 10 + $nivel * 2,
    'Hechicero' => $heroe['inteligencia'] * 3 + $nivel ** 2 / 4,
    'Pícara'    => $heroe['destreza'] * 2 + $nivel ** 1.5,
    'Clérigo'   => ($heroe['inteligencia'] + $heroe['constitucion']) * 1.5 + $nivel,
};

// TODO 2.3  (R3) Estado con if / elseif / else: K.O. · En peligro · Herido · En pie
    if ($heroe['vida'] <= 0) {
        $estado = 'K.O.';
    }
    elseif ($pctVida < 35) {
        $estado = 'En peligro';
    }
    elseif ($pctVida < 70) {
        $estado = 'Herido';
    }
    else {
        $estado = 'En pie';
    }

// TODO 2.4  (R3) Posición con switch: Guerrera y Clérigo → 'Primera línea'
//           (casos agrupados), Pícara → 'Flanco', Hechicero → 'Retaguardia',
//           default → 'Reserva'.
    switch ($heroe['clase']) {
        case 'Guerrera':
        case 'Clérigo':
            $posicion = 'Primera línea';
            break;
        case 'Pícara':
            $posicion = 'Flanco';
            break;
        case 'Hechicero':
            $posicion = 'Retaguardia';
            break;
        default:
            $posicion = 'Reserva';
    }

// TODO 2.5  Barra de vida del héroe con un bucle for (prohibido str_repeat).
    $llenos = (int) round($pctVida / 100 * BLOQUES_BARRA);
    $barra  = '';
    for ($b = 0; $b < BLOQUES_BARRA; $b++) {
        if ($b < $llenos) {
            $barra .= '█';
        } 
        else {
            $barra .= '░';
        }
    }

// TODO 2.6  Guarda todo en la matriz: $party[$i]['nivel'] = ...; etc.
//           Claves: nivel, vidaMax, pctVida, danio, poder, estado, posicion, barra
    $party[$i]['nivel']    = $nivel;
    $party[$i]['vidaMax']  = $vidaMax;
    $party[$i]['pctVida']  = $pctVida;
    $party[$i]['danio']    = $danio;
    $party[$i]['poder']    = (int) round($danio * $nivel);
    $party[$i]['estado']   = $estado;
    $party[$i]['posicion'] = $posicion;
    $party[$i]['barra']    = $barra;
    }

// TODO 2.7  Totales con array_sum + array_column: $vidaParty, $vidaMaxParty,
//           $poderParty, y $nivelMedio (media).
$vidaParty    = array_sum(array_column($party, 'vida'));
$vidaMaxParty = array_sum(array_column($party, 'vidaMax'));
$poderParty   = array_sum(array_column($party, 'poder'));
$nivelMedio   = array_sum(array_column($party, 'nivel')) / count($party);

// TODO 2.8  Segundo foreach: $danioParty, $heroesActivos y $lider, saltando
//           con continue a los héroes K.O. ($lider empieza valiendo null).
$danioParty     = 0.0;
$heroesActivos  = 0;
$lider          = null;
foreach ($party as $heroe) {
    if ($heroe['estado'] === 'K.O.') {
        continue;                       // los K.O. no cuentan
    }
    $danioParty += $heroe['danio'];
    $heroesActivos++;
    if ($lider === null || $heroe['poder'] > $lider['poder']) {
        $lider = $heroe;
    }
}

// TODO 2.9  $hayPicara y $hayClerigo con in_array(..., ..., true).
$clases      = array_column($party, 'clase');
$hayPicara   = in_array('Pícara', $clases, true);
$hayClerigo  = in_array('Clérigo', $clases, true);


// =====================================================================
// R4 · LA EXPEDICIÓN (sala a sala)
// =====================================================================
// TODO 4.1  Inicializa: $oro = $oroInicial, $xpGanada = 0, $diario = [],
//           $salasExploradas = 0.
$oro             = $oroInicial;
$xpGanada        = 0;
$diario          = [];
$salasExploradas = 0;

// TODO 4.2  foreach ($salas as $n => $sala)  con un switch sobre $sala['tipo']:
//             'vacía'    → línea en el diario y pasar a la SIGUIENTE SALA
//                          (cuidado: continue dentro de switch... ¿1 o 2?)
//             'trampa'   → si hay Pícara, se evita; si no, resta 'valor' de vida
//             'combate'  → resta intdiv(valor, 4) de vida y suma 'valor' a la XP
//             'tesoro'   → suma 'valor' al oro y, si la sala trae 'objeto',
//                          añádelo al inventario (+1 unidad)
//             'descanso' → cura 'valor' (el doble si hay Clérigo), sin pasar
//                          de $vidaMaxParty (min)
foreach ($salas as $n => $sala) {
    $salasExploradas++;
    $num = $n + 1; // la sala se numera desde 1

    switch ($sala['tipo']) {
        case 'vacía':
            $diario[] = "Sala $num · {$sala['nombre']}: vacía, la party sigue avanzando.";
            continue 2; // 2 = salir del switch Y pasar a la siguiente sala
        case 'trampa':
            if ($hayPicara) {
                $diario[] = "Sala $num · {$sala['nombre']}: trampa evitada por la Pícara.";
            } else {
                $vidaParty -= $sala['valor'];
                $diario[]   = "Sala $num · {$sala['nombre']}: ¡trampa! −{$sala['valor']} de vida.";
            }
            break;
        case 'combate':
            $perdida    = intdiv($sala['valor'], 4);
            $vidaParty -= $perdida;
            $xpGanada  += $sala['valor'];
            $diario[]   = "Sala $num · {$sala['nombre']}: combate, −$perdida de vida y +{$sala['valor']} XP.";
            break;
        case 'tesoro':
            $oro += $sala['valor'];
            $linea = "Sala $num · {$sala['nombre']}: +{$sala['valor']} mo";
            if (isset($sala['objeto'])) {
                $inventario[$sala['objeto']] = ($inventario[$sala['objeto']] ?? 0) + 1;
                $linea .= " y «{$sala['objeto']}»";
            }
            $diario[] = $linea . '.';
            break;
        case 'descanso':
            $cura       = $hayClerigo ? $sala['valor'] * 2 : $sala['valor'];
            $vidaParty  = min($vidaParty + $cura, $vidaMaxParty);   // sin pasar del máximo
            $diario[]   = "Sala $num · {$sala['nombre']}: descanso, +$cura de vida.";
            break;
    }

// TODO 4.3  Tras el switch: si $vidaParty <= 0 → vida a 0, línea en el diario
//           y break.
    if ($vidaParty <= 0) {
        $vidaParty = 0;
        $diario[]  = "La party ha caído en la sala $num.";
        break;
    }
}

// TODO 4.4  $salasPendientes = salas totales − exploradas.
$salasPendientes = count($salas) - $salasExploradas;


// =====================================================================
// R5 · EL JEFE
// =====================================================================
// TODO 5.1  $tieneLlave con array_key_exists($jefe['llave'], $inventario).
//           $golpe = daño de la party redondeado a int.
$golpe      = (int) round($danioParty);
$tieneLlave = array_key_exists($jefe['llave'], $inventario);
$vidaJefe   = $jefe['vida'];
$ronda      = 0;
$rondas     = [];
$resultado  = '';
$motivo     = '';

// TODO 5.2  if / elseif / else:
//             party sin vida      → 'Derrota' (no llegó)
//             sin llave           → 'Puerta sellada'
//             en otro caso        → combate con while:
//               mientras jefe > 0 Y party > 0 Y ronda < MAX_RONDAS:
//                 ronda++; el jefe recibe $golpe (mínimo 0);
//                 si el jefe sigue vivo, la party recibe su 'ataque' (mínimo 0);
//                 añade la fila a $rondas[] = ['ronda'=>…, 'vidaJefe'=>…, 'vidaParty'=>…]
//               $resultado con match(true): Victoria · Derrota · Retirada
//               Si es Victoria, suma la recompensa al oro.
if ($vidaParty <= 0) {
    $resultado = 'Derrota';
    $motivo    = 'la party no llegó hasta el jefe';
} elseif (!$tieneLlave) {
    $resultado = 'Puerta sellada';
    $motivo    = "falta «{$jefe['llave']}»";
} else {
    while ($vidaJefe > 0 && $vidaParty > 0 && $ronda < MAX_RONDAS) {
        $ronda++;
        $vidaJefe = max(0, $vidaJefe - $golpe);
        if ($vidaJefe > 0) {
            $vidaParty = max(0, $vidaParty - $jefe['ataque']);
        }
        $rondas[] = ['ronda' => $ronda, 'vidaJefe' => $vidaJefe, 'vidaParty' => $vidaParty];
    }

    $resultado = match (true) {
        $vidaJefe  <= 0 => 'Victoria',
        $vidaParty <= 0 => 'Derrota',
        default         => 'Retirada',
    };

    if ($resultado === 'Victoria') {
        $oro += $jefe['recompensa'];
    }
    $motivo = "$ronda rondas";           // ajusta el texto a tu gusto
}

// =====================================================================
// R4 (cont.) · INVENTARIO FINAL
// =====================================================================
// TODO 4.5  foreach con clave => valor: $valorInventario (cantidad × precio).
$valorInventario = 0.0;
foreach ($inventario as $objeto => $cantidad) {
    $valorInventario += $cantidad * $precios[$objeto];
}

// TODO 4.6  $totalObjetos (array_sum). Copia del inventario ordenada de mayor
//           a menor cantidad CONSERVANDO las claves. $objetoMasAbundante.
$totalObjetos        = array_sum($inventario);
$inventarioOrdenado  = $inventario;
arsort($inventarioOrdenado);
$objetoMasAbundante  = array_key_first($inventarioOrdenado);

// =====================================================================
// R6 · EL MAPA
// =====================================================================
// TODO 6.1  Dos for anidados (filas y columnas, con count) para contar cuántas
//           veces aparece cada carácter: $conteoCeldas[$celda] = (… ?? 0) + 1;
$conteoCeldas = [];
for ($f = 0; $f < count($mapa); $f++) {
    for ($c = 0; $c < count($mapa[$f]); $c++) {
        $celda = $mapa[$f][$c];
        $conteoCeldas[$celda] = ($conteoCeldas[$celda] ?? 0) + 1;
    }
}

// TODO 6.2  ¿El número de 'S' coincide con count($salas)? → $mapaCoherente
$mapaCoherente = ($conteoCeldas['S'] ?? 0) === count($salas);

// TODO 6.3  Barra de vida de la party con for.
$pctFinal   = $vidaParty / $vidaMaxParty * 100;
$llenosFin  = (int) round($pctFinal / 100 * BLOQUES_BARRA);
$barraFinal = '';
for ($b = 0; $b < BLOQUES_BARRA; $b++) {
    $barraFinal .= ($b < $llenosFin) ? '█' : '░';
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expedición · <?= htmlspecialchars($nombreMazmorra) ?> · Forja de Héroes II</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="insignia">VARIANTE <?= VARIANTE ?></span>
    <h1><?= htmlspecialchars($nombreMazmorra) ?></h1>
    <p class="subtitulo">Informe de expedición · líder: <?php if ($lider !== null): ?>
            <?= htmlspecialchars($lider['nombre']) ?>
        <?php else: ?>
            nadie (toda la party está K.O.)
        <?php endif; ?></p>

    <h2>La party</h2>
    <table>
        <tr><th>Héroe</th><th>Clase</th><th>Nivel</th><th>Vida</th><th>Daño</th><th>Poder</th><th>Posición</th><th>Estado</th></tr>
        <?php foreach ($party as $heroe): ?>
        <tr>
            <td><?= htmlspecialchars($heroe['nombre']) ?></td>
            <td><?= htmlspecialchars($heroe['clase']) ?></td>
            <td><?= $heroe['nivel'] ?></td>
            <td><span class="barra mini"><?= $heroe['barra'] ?></span> <?= $heroe['vida'] ?>/<?= $heroe['vidaMax'] ?></td>
            <td><?= number_format($heroe['danio'], 1, ',', '.') ?></td>
            <td><?= $heroe['poder'] ?></td>
            <td><?= htmlspecialchars($heroe['posicion']) ?></td>
            <?php if ($heroe['estado'] === 'K.O.' || $heroe['estado'] === 'En peligro'): ?>
                <td class="peligro"><?= htmlspecialchars($heroe['estado']) ?></td>
            <?php else: ?>
                <td class="vida"><?= htmlspecialchars($heroe['estado']) ?></td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="rejilla">
        <div class="stat"><div class="etq">Poder total</div><div class="val"><?= $poderParty ?></div></div>
        <div class="stat"><div class="etq">Nivel medio</div><div class="val"><?= number_format($nivelMedio, 1, ',', '.') ?></div></div>
        <div class="stat"><div class="etq">Daño por ronda</div><div class="val"><?= number_format($danioParty, 1, ',', '.') ?></div></div>
        <div class="stat"><div class="etq">Héroes activos</div><div class="val"><?= $heroesActivos ?> / <?= count($party) ?></div></div>
    </div>

    <h2>Diario de la expedición</h2>
    <ol class="diario">
        <?php foreach ($diario as $linea): ?>
            <li><?= htmlspecialchars($linea) ?></li>
        <?php endforeach; ?>
    </ol>
    <p>Salas exploradas: <?= $salasExploradas ?> de <?= count($salas) ?>
        <?php if ($salasPendientes > 0): ?>
            <span class="peligro">(quedan <?= $salasPendientes ?> sin explorar)</span>
        <?php endif; ?>
        · XP ganada: <?= $xpGanada ?></p>

    <h2>El jefe: <?= htmlspecialchars($jefe['nombre']) ?></h2>
    <p>Vida <?= $jefe['vida'] ?> · ataque <?= $jefe['ataque'] ?> · golpe de la party: <?= $golpe ?> por ronda</p>

    <?php if (count($rondas) > 0): ?>
    <table>
        <tr><th>Ronda</th><th>Vida del jefe</th><th>Vida de la party</th></tr>
        <?php foreach ($rondas as $r): ?>
        <tr>
            <td><?= $r['ronda'] ?></td>
            <td><?= $r['vidaJefe'] ?></td>
            <td><?= $r['vidaParty'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    <p class="resultado <?= $resultado === 'Victoria' ? 'vida' : 'peligro' ?>">
        <strong><?= htmlspecialchars($resultado) ?></strong> ·
        <?php if ($resultado === 'Victoria'): ?>
            en la ronda <?= $ronda ?>
        <?php else: ?>
            <?= htmlspecialchars($motivo) ?>
        <?php endif; ?>
    </p>
    <p class="barra vida">PARTY <?= $barraFinal ?> <?= $vidaParty ?>/<?= $vidaMaxParty ?></p>

    <h2>Inventario</h2>
    <table>
        <tr><th>Objeto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
        <?php foreach ($inventarioOrdenado as $objeto => $cantidad): ?>
        <tr>
            <td><?= htmlspecialchars($objeto) ?></td>
            <td><?= $cantidad ?></td>
            <td><?= number_format($precios[$objeto], 2, ',', '.') ?> mo</td>
            <td><?= number_format($cantidad * $precios[$objeto], 2, ',', '.') ?> mo</td>
        </tr>
        <?php endforeach; ?>
    </table>
    <div class="rejilla">
        <div class="stat"><div class="etq">Objetos</div><div class="val"><?= $totalObjetos ?></div></div>
        <div class="stat"><div class="etq">Valor del inventario</div><div class="val"><?= number_format($valorInventario, 2, ',', '.') ?> mo</div></div>
        <div class="stat"><div class="etq">Oro final</div><div class="val"><?= number_format($oro, 2, ',', '.') ?> mo</div></div>
        <div class="stat"><div class="etq">Más abundante</div><div class="val peq"><?= htmlspecialchars((string) $objetoMasAbundante) ?></div></div>
    </div>

    <h2>Mapa de la mazmorra</h2>
    <table class="mapa">
        <?php foreach ($mapa as $fila): ?>
        <tr>
            <?php foreach ($fila as $celda): ?>
                <td class="<?= LEYENDA_MAPA[$celda][0] ?>"><?= $celda ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </table>

    <p>
        <?php foreach ($conteoCeldas as $celda => $veces): ?>
            <span><?= htmlspecialchars((string) $celda) ?> <?= LEYENDA_MAPA[$celda][1] ?>: <strong><?= $veces ?></strong></span>&nbsp;&nbsp;
        <?php endforeach; ?>
    </p>

    <?php if ($mapaCoherente): ?>
        <p class="vida">Mapa coherente: <?= $conteoCeldas['S'] ?> salas en el mapa y <?= count($salas) ?> en los datos.</p>
    <?php else: ?>
        <p class="peligro">Mapa incoherente: hay <?= $conteoCeldas['S'] ?? 0 ?> salas «S» en el mapa y <?= count($salas) ?> en los datos.</p>
    <?php endif; ?>

    <footer>Informe generado el <?= date('d/m/Y H:i') ?> con PHP <?= PHP_VERSION ?> · cada recarga lo recalcula todo desde cero.</footer>
</main>
</body>
</html>