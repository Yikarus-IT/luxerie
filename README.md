# Luxérie

Prototipo de tienda y administración en Laravel para la marca de cuidado de la piel Luxérie.

## Prototipo actual

- Página principal, catálogo y detalles de producto alimentados desde la base de datos
- Inicio de sesión protegido para administradores
- Administración de productos y categorías
- Controles de inventario y visibilidad
- Interfaz responsiva con Blade y Tailwind
- SQLite para desarrollo local sin configuración; las migraciones son compatibles con MySQL

## Configuración local

```bash
composer install
npm install
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Credenciales del administrador de prueba:

- Correo: `admin@luxerie.test`
- Contraseña: `password`

Estas credenciales son únicamente para desarrollo y deben cambiarse antes de cualquier despliegue.

## Próximas etapas

1. Carga y administración de imágenes
2. Variantes de producto
3. Carrito de sesión y dominio de checkout
4. Pedidos e integración con PayPal
5. Entorno de producción con MySQL y configuración de despliegue
