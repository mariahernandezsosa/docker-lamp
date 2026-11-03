<?php
/**
 * =====================================================================
 *  inc/heroe.php · Reglas del juego y datos del héroe          [CE 2.c]
 * =====================================================================
 *  Autor/a: María Hernández · Variante: B
 *
 *  Este fichero contiene SOLO PHP. Recuerda la regla sobre la etiqueta
 *  de cierre en este tipo de ficheros.
 */

// TODO 1. Directiva strict_types (primera instrucción del fichero).
declare(strict_types=1);

// TODO 2. Constantes con const: CLASE_HEROE, XP_POR_NIVEL, VIDA_BASE,
//         MULT_VIDA, BLOQUES_BARRA y NOMBRE_RIVAL.
//         PODER_RIVAL, con define().
//         (Valores: tabla de tu variante en el enunciado.)
const XP_POR_NIVEL = 400;
const VIDA_BASE = 50;
const BLOQUES_BARRA = 16;
const CLASE_HEROE = 'Hechicero';
const MULT_VIDA = 1;
const NOMBRE_RIVAL = 'Dragón de Obsidiana';
define('PODER_RIVAL', 650);

// TODO 3. Datos del héroe. Las estadísticas se escriben como TEXTO,
//         entre comillas, tal como aparecen en el enunciado:
//         $nombreHeroe, $apodo (null), $lema (''), $fuerzaTxt,
//         $destrezaTxt, $inteligenciaTxt, $constitucionTxt,
//         $experienciaTxt, $vidaActualTxt, $oroTxt, $esVeterano (bool).
$nombreHeroe = 'Aldric <el Arcano>';
$apodo = null;
$lema = null;
$fuerzaTxt = '8';
$destrezaTxt = '11';
$inteligenciaTxt = '19';
$constitucionTxt = '10';
$experienciaTxt = '3120';
$vidaActualTxt = '41';
$oroTxt = '987.4';
$esVeterano = false;
