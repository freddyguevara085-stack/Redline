# Redline v5

Plataforma cultural desarrollada en Laravel 8 para compartir y preservar historias, noticias, eventos y recursos multimedia. El proyecto integra formularios accesibles, portadas optimizadas y soporte para videos (archivos locales o enlaces de YouTube/Vimeo) tanto en la sección de Historias como en la Biblioteca.

## Características principales

- Gestión completa de historias con portada, video embebido y descripción accesible.
- Biblioteca multimedia con soporte para archivos descargables, enlaces externos, galerías de imágenes y videos.
- Comentarios moderados disponibles solo para usuarios autenticados.
- Panel administrativo en Blade + Tailwind, fácil de mantener y extender.
- Pruebas funcionales para validar procesos clave (carga de portadas, validaciones de video y enlaces, vistas públicas).

## Requerimientos

- PHP 8.1 o superior  
- Composer 2+  
- MySQL/MariaDB (configurable en `.env`)  
- Node.js 16+ y npm  
- Extensión `fileinfo` activa y permisos de escritura en `storage/` y `bootstrap/cache/`

## Puesta en marcha local

1. Crear en **phpMyAdmin** una base de datos vacía llamada **`redline`** con cotejamiento `utf8mb4_unicode_ci`.
2. Clonar el repositorio y entrar al directorio `redline-v5`.
3. Copiar el archivo de entorno:  
   ```bash
   cp .env.example .env
   ```
4. Configurar credenciales de base de datos y correo en `.env`.
5. Instalar dependencias PHP y JS:  
   ```bash
   composer install
   npm install
   ```
6. Generar la clave de la aplicación:  
   ```bash
   php artisan key:generate
   ```
7. Ejecutar migraciones y seeders iniciales:  
   ```bash
   php artisan migrate --seed
   ```
8. Crear el enlace simbólico de almacenamiento (solo una vez):  
   ```bash
   php artisan storage:link
   ```
9. Compilar assets en modo desarrollo:  
   ```bash
   npm run dev
   ```
10. Levantar el servidor local:  
   ```bash
   php artisan serve
   ```

## Pruebas automatizadas

El proyecto incluye pruebas que validan los flujos multimedia principales.  
Ejecuta:

```bash
php vendor/bin/phpunit
```

## Evidencia de control de versiones (Git / GitHub)

- Remoto configurado: `origin` → https://github.com/freddyguevara085-stack/Redline.git  
- Ramas:  
  - `main` (rama principal)  
- Últimos commits:

```
* af33ee4 (HEAD -> main, origin/main) Añade licencia MIT
* 7edd334 Primer commit
```

Buenas prácticas aplicadas:  
- `.env` y carpetas generadas (`vendor/`, `storage/`) ignoradas con `.gitignore`.  
- Mensajes de commit descriptivos.  
- Documentación en `README` y `docs/` para facilitar colaboración.

## Guía rápida para usuarios

Consulta `docs/USER_GUIDE.md` para instrucciones de uso paso a paso desde la interfaz.

## Compilación de assets para producción

Para generar los archivos minificados:

```bash
npm run prod
```

Esto colocará los archivos optimizados en `public/css` y `public/js`.

## Diseño de la interfaz (3 pantallas)

- **Historias** (`/historia`): listado y detalle con portada + video.  
- **Biblioteca** (`/biblioteca`): recursos en formato archivo, enlace y video.  
- **Juegos** (`/juegos`): acceso a quizzes con puntajes y ranking.  

Cada pantalla mantiene consistencia visual con Blade y Tailwind.

## Funcionalidades clave

- CRUD de Historias con portada optimizada y videos (archivo o YouTube/Vimeo).  
- Biblioteca multimedia con descargas, enlaces externos y videos embebidos.  
- Comentarios moderados para usuarios registrados.  
- Juegos de preguntas con puntajes y ranking.  
- Panel de administración para manejar contenidos principales.

## Diagramación de la base de datos

Revisa `docs/DB_DIAGRAM.md` para un esquema de tablas y relaciones (incluye ejemplo en Mermaid).

## Checklist de despliegue

- Ajustar `.env` con valores de producción (`APP_ENV=production`, `APP_DEBUG=false`, credenciales reales de BD y correo, drivers de cache/cola adecuados).  
- Instalar dependencias sin paquetes de desarrollo:  
  ```bash
  composer install --optimize-autoloader --no-dev
  npm ci
  npm run prod
  ```
- Limpiar y cachear configuración/rutas/vistas:  
  ```bash
  php artisan config:clear
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
- Migrar base de datos en el servidor:  
  ```bash
  php artisan migrate --force
  ```
- Revisar enlace `storage:link` y permisos de `storage/` y `bootstrap/cache/`.  
- Configurar supervisores/cron si se usan colas (`php artisan queue:work`) o el scheduler (`php artisan schedule:run`).  
- Hacer una prueba rápida: subir portada, agregar un video/enlace en Biblioteca e Historia, y verificar que todo cargue correctamente.

## Licencia

Este proyecto está bajo licencia MIT. Consulta el archivo `LICENSE` para más detalles.  
