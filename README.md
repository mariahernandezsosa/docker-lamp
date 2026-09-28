# Práctica 1 (UD1) — Entorno Docker

## Descripción

Este proyecto consiste en un entorno de desarrollo web creado con Docker Compose. Está formado por tres contenedores independientes:

- **Nginx:** servidor web que recibe las peticiones HTTP en el puerto 8080.
- **PHP 8.3 (PHP-FPM):** ejecuta el código PHP de la aplicación.
- **MySQL:** almacena los datos y permite la conexión desde PHP mediante PDO.

La aplicación muestra un mensaje de bienvenida y comprueba la conexión con la base de datos MySQL.

## Cómo levantar el proyecto

1. Clonar el repositorio y acceder a la carpeta del proyecto.
2. Ejecutar el siguiente comando en la terminal:

   ```bash
   docker compose up -d
   ```

   Debemos asegurarnos de que Docker Desktop se encuentre activo.

3. Abrir en el navegador: http://localhost:8080

Para detener los contenedores, ejecutar:

```bash
docker compose down
```

## Captura de pantalla

Resultado del proyecto:

<img width="517" height="150" alt="image" src="https://github.com/user-attachments/assets/4d92559e-cd49-4036-801e-ff7565945859" />
<img width="1268" height="519" alt="image" src="https://github.com/user-attachments/assets/a4ddeff0-1565-47ab-97f2-1417c52cf366" />


