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
- [Flujo de trabajo (Jira + Git)](#flujo-de-trabajo-jira--git)
- [Reparto de responsabilidades](#reparto-de-responsabilidades)

---

## Tecnologías
- **Laravel**
- **Bootstrap** (interfaz)
- **PayPal Sandbox** (pasarela de pago)
- **Cloudinary API** (subida y almacenamiento de imágenes)

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
Tablas principales:
- `users`
- `brands`
- `categories`
- `products`
- `category_product` (tabla pivote)
- `orders`
- `order_items`
- `payments`

Relaciones:
- **1:n**
  - `brands` → `products`
  - `users` → `orders`
- **n:m**
  - `products` ↔ `categories` (pivot `category_product`)
  - `orders` ↔ `products` (pivot `order_items`)

Datos de prueba:
- Se incluyen **factories** y **seeders** para generar datos masivos y coherentes.

---

## Instalación y ejecución
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
