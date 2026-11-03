# Memoria · Reto UD2 «Forja de Héroes»

**Alumno/a:** TODO · **Variante:** TODO · **Fecha:** TODO

> Las capturas van en la carpeta `capturas/` y se enlazan así: `![descripción](capturas/nombre.png)`

## 1. Comprobación de resultados (CE 2.e)

Rellena la tabla con los valores que muestra TU ficha y compáralos con la tabla de comprobación del enunciado.

| Dato | Valor esperado (enunciado) | Valor de mi ficha | ¿Coincide? |
|---|---|---|---|
| Nivel | 8 | 8 | Sí |
| Vida máxima | 130 (vida 41 / 130) | 41 / 130 | Sí |
| % de vida | 31,5 % | 31,5 % | Sí |
| Daño | 73,0 | 73,0 | Sí |
| Crítico especial | 210 pm | 210 pm | Sí |
| Poder | 584 | 584 | Sí |
| Resultado frente al rival | −1 → Desventaja | −1 → Desventaja: mejor retirarse | Sí |

## 2. El código fuente y el documento resultante (CE 2.e · CE 2.c)

- Captura de `ficha.php` en el editor (parte de cálculos) y captura del **Ctrl+U** de la página.
![desc](<capturas\captura resultado.png>)
![desc](<capturas\captura ficha ctrl u.png>)

- Explica con tus palabras **tres diferencias** entre ambas.
1. Las etiquetas PHP desaparecen -> en el editor hay bloques <?php ... ?> y <?= ... ?>. En Ctrl+U no queda ninguno: el servidor los ejecuta y el navegador solo recibe HTML puro.
2. Las variables se sustituyen por sus valores -> En el código aparece <?= $nivel ?> y en Ctrl+U aparece 8. Los cálculos (intdiv, %, **, ternarios...) no se ven, solo llega el resultado.
3. Los comentarios y las líneas de PHP no llegan al navegador -> El require_once, losbdeclare y los comentarios del bloque de cálculos no aparecen en el código fuente de la página. Los comentarios HTML (<!-- -->) sí llegan.

- ¿Qué ha pasado con el nombre del héroe en el Ctrl+U? ¿Por qué?
En el editor el dato es Aldric <el Arcano>, pero en Ctrl+U aparece como Aldric &lt;el Arcano&gt;. Es el efecto de htmlspecialchars(), que convierte < en &lt; y > en &gt;. Así el navegador pinta los símbolos como texto. Sin esa función, el navegador interpretaría <el Arcano> como una etiqueta HTML desconocida y el nombre se mostraría incompleto.

## 3. Experimento: cambio un dato y predigo el efecto (CE 2.e)

1. Dato que cambio en `inc/heroe.php` (valor antiguo → valor nuevo):

$experienciaTxt de 3120 a 3600.


2. **Predicción** (antes de recargar): qué valores de la ficha cambiarán y cuánto valdrán: 

| Valor | Antes | Predicción |
|---|---|---|
| Nivel | 8 | 10 |
| XP en el nivel | 320 / 80 | 0 / 400 |
| Vida máxima | 130 | 150 |
| % de vida | 31,5 % | 27,3 % |
| Daño | 73,0 | 82,0 |
| Crítico especial | 210 pm | 190 pm |
| Poder | 584 | 820 |
| Resultado frente al rival | −1 → Desventaja | 1 → Ventaja |

3. **Resultado** real (captura):
![resultado](<capturas\resultado cambio dato.png>)

4. ¿Acertaste? Si no, ¿por qué?

Sí, ya que la ficha se recalcula sola porque PHP no guarda nada entre peticiones.


## 4. Experimento con strict_types (CE 2.f)

1. Quita el `(int)` del cálculo de los bloques de la barra de vida. Recarga. Captura del error.
![error](<capturas\error int.png>)

2. Explica qué dice el mensaje (tipo de error, función, argumento, línea).

Es un Fatal error: Uncaught TypeError: str_repeat(): Argument #2 ($times) must be of type int, float given in .../ficha.php:NN. Indica el tipo de error (TypeError), la función (str_repeat), el argumento (el segundo, $times, que esperaba int y recibió float) y la línea donde se llamó. round() siempre devuelve float, aunque el valor sea 6.0.


3. Comenta la línea `declare(strict_types=1);` (manteniendo el cambio anterior) y recarga. ¿Qué pasa? ¿Por qué?

La página funciona y la barra se pinta. Sin modo estricto PHP aplica coerción de tipos: convierte el float 6.0 en int 6 sin avisar, porque no pierde información. Con strict_types=1 PHP exige el tipo exacto y lanza el error.


4. Deja el código como estaba. ¿Qué opción prefieres para un proyecto real y por qué?

Para un proyecto real es mejor strict_types=1, porque los errores de tipo saltan en el momento y en la línea donde se producen, en vez de dar resultados extraños más adelante. Obliga a escribir las conversiones de forma explícita y el código se entiende mejor.


## 5. Directivas (CE 2.f)

- Captura de `diagnostico.php`.
![diagnostico](<capturas\captura diagnostico.png>)


| Directiva | Qué hace | Por qué |
|---|---|---|
| `display_errors` | Muestra los errores en la página | Activa en desarrollo para ver los fallos |
| `error_reporting` | Nivel de errores que se notifican (`E_ALL`) | Para ver todos los avisos y no dejar pasar ninguno |
| `date.timezone` | Zona horaria por defecto (`Europe/Madrid`) | Para que `date()` dé la hora de España y no UTC |
| `short_open_tag` | Permite o no `<?` como etiqueta de apertura | Desactivada para evitar conflictos con XML y usar solo `<?php` y `<?=` |
| `expose_php` | Añade la cabecera `X-Powered-By: PHP/versión` | Desactivada para no dar pistas del servidor a un atacante |
| `memory_limit` | Memoria máxima por script | Limita el consumo de cada petición |

- ¿Qué valor pondrías en `display_errors` en un servidor de producción? ¿Por qué?

Off, junto con log_errors = On para que los errores se guarden en un fichero de log. Un mensaje de error en pantalla enseña rutas del servidor, nombres de ficheros y a veces datos sensibles, y eso ayuda a un atacante. El visitante tampoco necesita verlos. El desarrollador los revisa en el log.


## 6. Uso de IA (obligatorio declararlo)

¿Has usado alguna IA? ¿Para qué? Copia una respuesta que te diera y explica si era correcta o qué tuviste que corregir.

Sí, he usado Claude para entender el panel de diagnóstico (`ini_set`, `ini_restore`, `ini_get`, `php_ini_scanned_files`), para depurar el aviso `Invalid date.timezone value 'Vancouver'` y para redactar esta memoria a partir de mi código.

**Ejemplo de respuesta:** le pregunté por qué `ini_set` me devolvía `'UTC'` si mi `forja.ini` tenía `date.timezone = Europe/Madrid`. Respondió que `ini_set` devuelve el valor anterior, así que `'UTC'` significaba que PHP no estaba leyendo mi `forja.ini` (falta de montaje del volumen o de reinicio del contenedor).

**¿Era correcta?** La parte conceptual sí, lo comprobé en la documentación de PHP (`ini_set` devuelve el valor anterior) y con mi propia captura. Pero las instrucciones de montaje del fichero no encajaban con mi kit: asumía un entorno Nginx + PHP-FPM con `forja.ini` en `conf.d/forja.ini`, mientras que mi entorno es el Docker LAMP del `LEEME.md`, con el servicio `www`, el fichero montado como `zz-forja.ini` y el reinicio con `docker compose restart www`. Tuve que adaptarlo yo.