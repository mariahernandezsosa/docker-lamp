# Memoria · Tarea de refuerzo UD3 «Forja de Héroes II»

**Alumno/a:** María Luisa Hernández Sosa · **Variante:** A · **Fecha:** 3/11/26

> Esta tarea *lleva nota**: sirve para comprobar que dominas lo que necesitarás en la UD5 (sesiones y login) y para preparar la prueba de diagnóstico del RA 3. Las capturas van en `capturas/` y se enlazan así: `![descripción](capturas/nombre.png)`

## 1. Comprobación de resultados

| Dato | Valor esperado (enunciado) | Valor de mi página | ¿Coincide? |
|---|---|---|---|
| Poder total de la party | 1243 | 1243 | Sí |
| Daño por ronda | 205,6 (golpe 206) | 205,6 (golpe 206) | Sí |
| Líder | Aldric | Aldric | Sí |
| Vida al salir de las salas | 305 (de 774) | 305 (de 774) | Sí |
| Resultado frente al jefe (y ronda) | Victoria en la ronda 5 | Victoria en la ronda 5 | Sí |
| Oro final | 2.260,75 mo | 2.260,75 mo | Sí |
| Valor del inventario | 151,20 mo | 151,20 mo | Sí |
| Salas en el mapa | 7 `S` (coincide con las 7 salas) | 7 `S` · mapa coherente | Sí |

![Página completa](<capturas\resultado completo.png>)


## 2. Bucles y documento resultante

- Captura del `foreach` que pinta la tabla de la party en tu editor y del **Ctrl+U** de esa tabla.

![foreach editor](<capturas\tabla party editor.png>)

![foreach tabla](<capturas\tabla party.png>)

- En el código hay **una** fila `<tr>`; en el Ctrl+U, ¿cuántas? ¿Por qué?

Porque la party tiene 4 héroes y el foreach ejecuta el bloque una vez por cada uno, generando una fila nueva en cada vuelta. Por eso el HTML crece o se hace más pequeño solo si cambian los datos.

- ¿Aparece en el Ctrl+U algo de `endforeach` o de `if`? ¿Por qué?

No aparece, porque PHP se ejecuta en el servidor, donde se procesan las instrucciones if y endforeac. Una vez ejecutado el código, el servidor envía al navegador únicamente el HTML generado. Por eso, del if solo se aparece el resultado. El navegador no recibe ni puede ver el código PHP original.


## 3. Experimento: predigo el efecto de un cambio

Añade esta sala **al principio** de `$salas` en tu fichero de datos:

```php
['nombre' => 'Derrumbe', 'tipo' => 'combate', 'valor' => 2400],
```

1. **Predicción** (antes de recargar): ¿cuántas salas se explorarán? ¿Qué resultado tendrá el jefe? ¿Qué dirá el aviso del mapa?

Se explorará solo 1 de las 8 salas, ya que la party pierde toda la vida en el primer combate.

El resultado contra el jefe será una derrota, porque la party cae antes de llegar hasta él. No habrá combate ni se conseguirá la Llave de hueso.

El aviso del mapa no tendrá sentido, porque el mapa seguirá mostrando 7 S, aunque realmente hay 8 salas (count($salas) = 8).

2. **Resultado** real (captura).

![Resultado con la sala Derrumbe](<capturas\resultado derrumbe.png>)


3. ¿Qué sentencia ha cortado el recorrido? ¿Qué pasa con la tabla de rondas y por qué?

El break de después del switch, dentro del if ($vidaParty <= 0). Al no estar dentro del switch, sino dentro del foreach ($salas as $n => $sala), provoca que el recorrido de las salas se interrumpa.

La tabla no se muestra porque la party llega sin vida al enfrentamiento con el jefe. Por tanto, se ejecuta la rama «Derrota» y el while no llega a ejecutarse. Como consecuencia, $rondas permanece vacío y la condición if (count($rondas) > 0) impide que se genere la tabla en el HTML.


4. Deja los datos como estaban.


## 4. Experimento: match frente a switch

1. Cambia la clase de un héroe a `'Bardo'` y recarga. Captura del error. ¿Qué tipo de error es y por qué se produce?

![error al usar bardo](<capturas\error bardo.png>)

Primero aparece un Warning: Undefined array key "Bardo", porque el cálculo de vidaMax consulta MULT_VIDA[$heroe['clase']] y esa clase no existe en la constante.

2. ¿Qué posición le asigna el `switch` a ese Bardo? ¿Por qué el `switch` no falla y el `match` sí?

Le asigna la posición 'Reserva', ya que ninguna de las opciones case coincide y se ejecuta el bloque default. El switch no da ningún error cuando no encuentra una coincidencia, solo ejecuta default (si existe). Match genera un error si no encuentra ningún brazo que corresponda al valor recibido y tampoco hay una opción que contemple ese caso.

3. Deja los datos como estaban. ¿Qué comportamiento prefieres en un programa real y por qué?

Prefiero el comportamiento de match en un programa real, porque una clase desconocida indica que hay un dato incorrecto o falta código por implementar. Con match, el error se detecta inmediatamente. En cambio, switch asignaría automáticamente 'Reserva', ocultando el problema y pudiendo provocar errores más adelante. Es mejor detectar el fallo desde el principio.


## 5. Experimento: continue dentro de switch

1. En el caso `'vacía'`, cambia `continue 2;` por `continue;` y recarga. ¿Qué aviso aparece?

![Aviso al usar continue](<capturas\error continue.png>)

Aparece un Warning de PHP que dice que `"continue" targeting switch is equivalent to "break"` y pregunta si se quería escribir `"continue 2"`.

2. ¿Qué cambia en el diario o en el recuento de salas? Explica a qué bucle afecta cada versión.

Con mis datos, el resultado no cambia, el diario sigue teniendo 7 líneas y las salas exploradas continúan siendo 7 de 7. Lo único que aparece nuevo es el aviso.

Pero cada versión funciona de forma diferente:

- `continue 2;` sale del `switch` y pasa directamente a la siguiente vuelta del `foreach`, saltándose el resto del código del bucle, incluida la comprobación de vida.
- `continue;` dentro de un `switch` se comporta como `break;`: sale únicamente del `switch` y permite que el `foreach` continúe ejecutando el código posterior, incluida la comprobación de vida.

Con mis datos no se aprecia ninguna diferencia porque la sala vacía no modifica la vida y la party siempre llega con vida. El resultado cambiaría si el código posterior al `switch` realizara alguna acción diferente para las salas vacías.

3. Déjalo como estaba.

## 6. Uso de IA (obligatorio declararlo)

¿Has usado alguna IA? ¿Para qué? Copia una respuesta que te diera y explica si era correcta o qué tuviste que corregir.

Sí, he usado Claude para entender la estructura de la tarea y repasar continue 2 dentro de un switch. Los valores los comprobé ejecutando la página y puedo explicar cada bucle y decisión del código.

Ejemplo: la IA me dijo que con la clase 'Bardo' el match lanzaría UnhandledMatchError y el switch asignaría 'Reserva'. Era correcto pero incompleto: antes del error aparece también un Warning: Undefined array key "Bardo" por MULT_VIDA. Lo comprobé al recargar y lo corregí en la sección 4.