# 🛠️ Instrucciones de instalación — Parte de David

## 1. Instalar paquete de Cloudinary

```bash
composer require cloudinary-labs/cloudinary-laravel
```

## 2. Publicar config de Cloudinary

```bash
php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config"
```

## 3. Configurar `.env`

Añade estas variables a tu `.env` (crea una cuenta gratuita en https://cloudinary.com):

```env
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME

# O por separado:
CLOUDINARY_CLOUD_NAME=tu_cloud_name
CLOUDINARY_API_KEY=tu_api_key
CLOUDINARY_API_SECRET=tu_api_secret
```

## 4. Ejecutar migraciones

```bash
php artisan migrate
```

## 5. Ejecutar seeders (datos de prueba)

```bash
php artisan db:seed
```

O para resetear todo:

```bash
php artisan migrate:fresh --seed
```

## 6. Verificar rutas

```bash
php artisan route:list --path=admin
```

Deberías ver estas rutas:

| Método  | URI                                   | Nombre                     |
|---------|---------------------------------------|----------------------------|
| GET     | admin/products                        | admin.products.index       |
| GET     | admin/products/create                 | admin.products.create      |
| POST    | admin/products                        | admin.products.store       |
| GET     | admin/products/{product}              | admin.products.show        |
| GET     | admin/products/{product}/edit         | admin.products.edit        |
| PUT     | admin/products/{product}              | admin.products.update      |
| DELETE  | admin/products/{product}              | admin.products.destroy     |
| GET     | admin/products/trash                  | admin.products.trash       |
| PATCH   | admin/products/{product}/restore      | admin.products.restore     |
| DELETE  | admin/products/{product}/force-delete | admin.products.force-delete|

## 7. Acceder al panel

```
http://localhost:8000/admin/products
```

---

## 📁 Archivos creados por David

### Migraciones
- `database/migrations/2025_02_01_000001_create_brands_table.php`
- `database/migrations/2025_02_01_000002_create_categories_table.php`
- `database/migrations/2025_02_01_000003_create_products_table.php`
- `database/migrations/2025_02_01_000004_create_category_product_table.php`

### Modelos
- `app/Models/Brand.php`
- `app/Models/Category.php`
- `app/Models/Product.php` (con SoftDeletes)

### Controlador
- `app/Http/Controllers/Admin/ProductController.php`

### Validación
- `app/Http/Requests/ProductRequest.php`

### Servicio Cloudinary
- `app/Services/CloudinaryService.php`

### Vistas
- `resources/views/layouts/admin.blade.php`
- `resources/views/admin/products/index.blade.php`
- `resources/views/admin/products/create.blade.php`
- `resources/views/admin/products/edit.blade.php`
- `resources/views/admin/products/show.blade.php`
- `resources/views/admin/products/trash.blade.php`
- `resources/views/admin/products/_form.blade.php`

### Factories & Seeders
- `database/factories/BrandFactory.php`
- `database/factories/CategoryFactory.php`
- `database/factories/ProductFactory.php`
- `database/seeders/BrandSeeder.php`
- `database/seeders/CategorySeeder.php`
- `database/seeders/ProductSeeder.php`
- `database/seeders/DatabaseSeeder.php` (actualizado)

### Rutas
- `routes/web.php` (actualizado)

---

## ⚠️ NOTA: Middleware auth

Las rutas están SIN middleware `auth` por ahora para que puedas probar.
Cuando Hugo tenga Breeze instalado, descomenta las líneas en `routes/web.php`:

```php
Route::middleware(['auth', 'verified'])->group(function () {
    // ... todas las rutas admin van aquí
});
```
