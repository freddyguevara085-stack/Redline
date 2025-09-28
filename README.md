# Redline v5

Plataforma cultural construida sobre Laravel 8 destinada a difundir historias, noticias, eventos y recursos multimedia. El proyecto incorpora formularios accesibles, manejo de portadas optimizadas y soporte para videos (archivo local o enlaces de YouTube/Vimeo) tanto en la sección de Historias como en la Biblioteca.

## Características principales

- Gestión completa de historias con portada, video embebido y descripción accesible.
- Biblioteca multimedia con soporte para archivos descargables, enlaces externos, galerías de imágenes y videos.
- Comentarios moderados por usuarios autenticados.
- Panel administrativo basado en Blade y Tailwind.
- Pruebas funcionales para validar los flujos críticos (subida de portadas, validaciones de video y enlaces, vistas públicas).

## Requerimientos

- PHP 8.1+
- Composer 2+
- MySQL/MariaDB (configurable vía `.env`)
- Node.js 16+ y npm
- Extensión `fileinfo` habilitada y acceso de escritura a `storage/` y `bootstrap/cache/`

## Puesta en marcha local

1. Clonar el repositorio y entrar al directorio `redline-v5`.
2. Copiar el archivo de entorno:
	```bash
	cp .env.example .env
	```
3. Configurar credenciales de base de datos y mail en `.env`.
4. Instalar dependencias PHP y JS:
	```bash
	composer install
	npm install
	```
5. Generar la clave de la aplicación:
	```bash
	php artisan key:generate
	```
6. Ejecutar migraciones y seeders iniciales:
	```bash
	php artisan migrate --seed
	```
7. Crear el enlace simbólico de almacenamiento (solo una vez):
	```bash
	php artisan storage:link
	```
8. Compilar assets en modo desarrollo:
	```bash
	npm run dev
	```
9. Levantar el servidor local:
	```bash
	php artisan serve
	```

## Pruebas automatizadas

El proyecto incluye pruebas de características enfocadas en los flujos multimedia.

```bash
php vendor/bin/phpunit
```

## Evidencia de control de versiones (Git / GitHub)

- Remoto configurado: `origin` → https://github.com/freddyguevara085-stack/Redline.git
- Ramas:
	- `main` (rama por defecto)
- Historial reciente (últimos commits):

```
* af33ee4 (HEAD -> main, origin/main) Añade licencia MIT
* 7edd334 Primer commit
```

Buenas prácticas aplicadas:
- Se excluyen secretos como `.env` y directorios generados (`vendor/`, `storage/`) mediante `.gitignore`.
- Commits con mensajes descriptivos.
- README y documentación en `docs/` para facilitar colaboración.

## Guía de Usuario Rápida

Consulta `docs/USER_GUIDE.md` para una guía paso a paso de uso desde la interfaz.

## Construcción de assets para producción

La compilación optimizada utiliza Laravel Mix y el script `prod`:

```bash
npm run prod
```

Esto generará los archivos minificados en `public/css` y `public/js`.

## Diseño de la interfaz (3 pantallas)

La aplicación incluye navegación entre páginas principales a través del menú:
- Historias (`/historia`): listado y detalle con portada y video.
- Biblioteca (`/biblioteca`): recursos tipo archivo, enlace y video.
- Juegos (`/juegos`): acceso al juego/quiz y ranking.

Cada pantalla mantiene una estructura coherente basada en Blade y Tailwind.

## Funcionalidades del reto

- Gestión de Historias con portada optimizada y video (archivo o URL YouTube/Vimeo).
- Biblioteca multimedia con archivos descargables, enlaces externos y videos embebidos.
- Comentarios moderados para usuarios autenticados.
- Juegos tipo quiz con preguntas, opciones, puntaje y ranking.
- Panel administrativo para CRUDs principales.

## Diagramación de la base de datos

Consulta `docs/DB_DIAGRAM.md` para una descripción de tablas y relaciones y un ejemplo en Mermaid.

## Video demo

Graba un recorrido corto (1–3 min) mostrando:
- Alta de una historia con portada y video.
- Creación de un recurso en Biblioteca.
- Juego rápido y visualización de ranking.

Sugerencias:
- Usa herramientas como OBS, Xbox Game Bar (Windows) o QuickTime (macOS).
- Sube el video a un servicio (YouTube no listado o Drive) y vincúlalo aquí cuando esté listo.

## Checklist de despliegue

- Actualizar `.env` con valores de producción (`APP_ENV=production`, `APP_DEBUG=false`, credenciales de BD y mail reales, drivers de cache/cola adecuados).
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
- Ejecutar migraciones en el servidor:
  ```bash
  php artisan migrate --force
  ```
- Verificar enlace `storage:link` y permisos de `storage/` y `bootstrap/cache/`.
- Configurar supervisores/cron en caso de utilizar colas (`php artisan queue:work`) o el programador (`php artisan schedule:run`).
- Realizar una prueba manual rápida: subir portada, agregar video/enlace en Biblioteca e Historia, revisar accesibilidad del contenido generado.

## Licencia

Este proyecto se distribuye bajo la licencia MIT. Consulta el archivo `LICENSE` para el texto completo.
