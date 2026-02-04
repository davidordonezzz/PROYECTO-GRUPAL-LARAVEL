# TecnoOutlet – Tienda de Informática (Laravel)

Aplicación web desarrollada con **Laravel** para la venta de **ordenadores, portátiles, componentes y periféricos**.  
Incluye **panel de administración**, **catálogo público**, **carrito y pedidos**, **pasarela de pago en Sandbox** y **gestión de imágenes mediante API externa (Cloudinary)**.

---

## Contenido
- [Tecnologías](#tecnologías)
- [Funcionalidades](#funcionalidades)
- [Modelo de datos](#modelo-de-datos)
- [Instalación y ejecución](#instalación-y-ejecución)
- [Variables de entorno](#variables-de-entorno)
- [Credenciales de prueba](#credenciales-de-prueba)
- [Flujo de trabajo (Jira + Git)](#flujo-de-trabajo-jira--git)
- [Reparto de responsabilidades](#reparto-de-responsabilidades)

---

## Tecnologías
- **Laravel 12**
- **Bootstrap 5** (interfaz)
- **PayPal Sandbox** (pasarela de pago)
- **Cloudinary API** (subida y almacenamiento de imágenes)
- **SQLite / MySQL** (base de datos)

---

## Funcionalidades

### Panel de administración
- Gestión completa de **productos** (CRUD):
  - Listado con **paginación**
  - **Filtros** por marca y categoría
  - Alta, edición y eliminación
  - **Borrado lógico (SoftDelete)** y **borrado definitivo**
  - Subida de **imagen** del producto y guardado de su URL mediante **Cloudinary**
- Gestión de **marcas** y **categorías** (CRUD).

### Parte pública (tienda)
- Catálogo de productos con vista de detalle.
- Carrito por sesión (añadir, quitar, modificar cantidades).
- Creación de pedido (**orders** + **order_items**) y cálculo del total.

### Pago y confirmación
- Pago mediante **PayPal (Sandbox)**.
- Registro de transacción en base de datos (**payments**: id de transacción, estado y fecha/hora).
- Página de resultado (éxito/cancelación).
- Envío de **email automático** tras pago correcto con el resumen del pedido.

---

## Modelo de datos

### Tablas principales (8)
| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios con autenticación y rol admin |
| `brands` | Marcas de productos |
| `categories` | Categorías de productos |
| `products` | Productos (entidad principal) |
| `category_product` | Tabla pivote n:m |
| `orders` | Pedidos de usuarios |
| `order_items` | Líneas de pedido (pivote n:m) |
| `payments` | Registro de pagos PayPal |

### Relaciones
- **1:n**
  - `brands` → `products`
  - `users` → `orders`
- **n:m**
  - `products` ↔ `categories` (pivot `category_product`)
  - `orders` ↔ `products` (pivot `order_items`)

---

## Instalación y ejecución

```bash
# Clonar repositorio
git clone https://github.com/tu-usuario/tecnooutlet.git
cd tecnooutlet

# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Copiar archivo de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Compilar assets
npm run build

# Iniciar servidor de desarrollo
php artisan serve
```

---

## Variables de entorno

Configurar en el archivo `.env` (no subir claves reales al repositorio):

```env
# Base de datos
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tecnooutlet
# DB_USERNAME=root
# DB_PASSWORD=

# PayPal Sandbox
PAYPAL_CLIENT_ID=tu_client_id
PAYPAL_SECRET=tu_secret
PAYPAL_MODE=sandbox

# Cloudinary
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
# O configuración separada:
# CLOUDINARY_CLOUD_NAME=tu_cloud_name
# CLOUDINARY_API_KEY=tu_api_key
# CLOUDINARY_API_SECRET=tu_api_secret

# Email (para verificación y notificaciones)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username
MAIL_PASSWORD=tu_password
```

---

## Credenciales de prueba

Tras ejecutar `php artisan migrate --seed`:

| Rol | Email | Contraseña |
|-----|-------|------------|
| **Admin** | admin@tecnooutlet.com | password |
| **Usuario** | (generados aleatoriamente) | password |

---

## Flujo de trabajo (Jira + Git)
- Organización y seguimiento con **Jira** (épicas, historias y subtareas).
- Desarrollo colaborativo con repositorio compartido:
  - Rama `main` protegida
  - Rama `develop` para integración
  - Ramas por feature: `feature/nombre-funcionalidad`
  - Pull Requests con revisión antes de integrar
  - Commits descriptivos asociados a tareas de Jira

---

## Reparto de responsabilidades

### Hugo
- Implementación de **PayPal Sandbox** (flujo completo de pago).
- Persistencia del resultado en `payments` (transaction_id, status, paid_at).
- Página de éxito/cancelación y email automático post-pago.
- Configuración base y servicio/helper de **Cloudinary** (subida de imágenes).

### David
- Desarrollo del CRUD principal de **Products** (panel admin):
  - Paginación, filtros (marca + categoría), validaciones (FormRequest)
  - SoftDelete y borrado definitivo
- Integración de **Cloudinary** en Products:
  - Subida en create/update y guardado de `image_url`

### David (compañero)
- Parte pública:
  - Catálogo, detalle y carrito por sesión
- Pedidos:
  - Creación de `orders` + `order_items`
  - Cálculo de total y estados básicos del pedido

### Ale
- CRUD de **brands** y **categories** (panel admin).
- Factories y seeders de marcas y categorías.
- Apoyo en pivote `category_product` si se requiere.

---

## Licencia

Este proyecto es parte de un trabajo académico para el módulo de Desarrollo Entorno Servidor del IES Mar de Cádiz.
