# Forja de Héroes · Kit de partida (UD2)

Este kit contiene **solo la base del proyecto**. El entorno es el Docker LAMP que montaste en la práctica inicial (carpeta `docker-lamp`, con los servicios `db`, `www` y `phpmyadmin`): no hace falta crear otro.

## Contenido

```
forja-heroes/
├── php/forja.ini          ← R8 · tus directivas (empieza vacío)
├── src/
│   ├── inc/heroe.php      ← R1 · constantes y datos (solo PHP)
│   ├── ficha.php          ← R1-R7 · cálculos + página
│   ├── diagnostico.php    ← R9 · panel de directivas
│   └── css/estilo.css     ← ya hecho
├── capturas/              ← tus capturas de pantalla
└── MEMORIA.md             ← R10 · memoria
```

## Integrarlo en tu Docker LAMP

1. **Código.** Copia la carpeta `forja-heroes` dentro de `docker-lamp/www/`. Como `www/` se monta en `/var/www/html`, la ficha se abre en
   `http://localhost/forja-heroes/src/ficha.php`.

2. **Directivas.** Abre `docker-lamp/docker-compose.yml` y, en el servicio **`www`**, añade una línea al bloque `volumes`:

   ```yaml
       www:
           ...
           volumes:
               - ./www:/var/www/html:ro
               - ./www/forja-heroes/php/forja.ini:/usr/local/etc/php/conf.d/zz-forja.ini:ro
   ```

   Respeta la sangría: deben ser espacios, no tabuladores. Todo `.ini` de `conf.d` se carga al arrancar PHP, y el prefijo `zz-` hace que el tuyo se cargue el último y prevalezca. Ojo: el Dockerfile del LAMP parte de `php.ini-production`, así que `display_errors` empieza desactivado hasta que tu `forja.ini` lo active.

3. **Arrancar.** Desde la carpeta `docker-lamp`, ejecuta `docker compose up -d`: así se recrea el contenedor con el volumen nuevo.
   Cada vez que modifiques `forja.ini`, ejecuta `docker compose restart www`.

4. **Comprobar.** Abre `diagnostico.php`: en «Ficheros .ini adicionales» debe aparecer `zz-forja.ini`, y las directivas deben tener los valores que pusiste.

## Si Docker te da problemas

Puedes probar el proyecto con el servidor integrado de PHP, ejecutando esto desde la carpeta `forja-heroes`:

```bash
php -c php/forja.ini -S localhost:8080 -t src
```

## Entrega

Un zip `apellido1_apellido2_nombre_UD2.zip` con **solo** la carpeta `forja-heroes`. No incluyas tu `docker-compose.yml` ni la configuración del LAMP.
