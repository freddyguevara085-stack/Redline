# Redline v5

Plataforma educativa construida con Laravel que centraliza historias, recursos multimedia y mini‑juegos para motivar el aprendizaje colaborativo.

## 🚀 Instalación rápida

Requisitos básicos:

- PHP 8.1 o superior y Composer 2+
- MySQL/MariaDB (configurable vía `.env`)
- Node.js 16+ con npm
- Extensión `fileinfo` habilitada y permisos de escritura en `storage/` y `bootstrap/cache/`

Pasos iniciales:

1. Clona el repositorio y entra al directorio del proyecto.
2. Copia el archivo de entorno y ajusta credenciales:
	```powershell
	copy .env.example .env
	# Edita .env con tus datos de base de datos y mail
	```
3. Instala dependencias:
	```powershell
	composer install
	npm install
	```
4. Genera la clave de la aplicación y ejecuta migraciones + seeders:
	```powershell
	php artisan key:generate
	php artisan migrate --seed
	```
5. Crea el enlace simbólico de almacenamiento (solo la primera vez):
	```powershell
	php artisan storage:link
	```

## ▶️ Ejecución en local

Compila los assets para desarrollo y lanza el servidor embebido:

```powershell
npm run dev
php artisan serve
```

Visita `http://127.0.0.1:8000` para navegar por historias, biblioteca, juegos y ranking.

## ✅ Pruebas

Ejecuta la terminal de pruebas para validar los flujos principales:

```powershell
php artisan test
```

## 🧾 Licencia

Distribuido bajo licencia MIT. Consulta `LICENSE` para más detalles.
