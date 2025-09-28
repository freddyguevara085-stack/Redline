# Guía de colaboración

Este repositorio usa GitHub Flow.

## Flujo básico
1. Crea una rama desde `main`:
   - `feature/…` para nuevas funcionalidades
   - `fix/…` para correcciones
2. Haz commits pequeños y claros.
3. Empuja la rama y abre Pull Request (PR) contra `main`.
4. Pide revisión. Usa comentarios y sugerencias.
5. Haz squash/merge tras la aprobación y que las pruebas pasen.

## Estándares
- Mensajes de commit descriptivos (es/en).
- Evitar subir secretos; usar `.env` local.
- Ejecutar `phpunit` y `npm run prod` antes de merge si cambia backend/frontend.

## Plantilla corta de PR
- Resumen del cambio
- Motivación/contexto
- Screenshots (si UI)
- Cómo probarlo
- Checklist: [ ] tests pasan, [ ] docs actualizadas
