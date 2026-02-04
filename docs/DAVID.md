# Documentación - Parte de David

## CRUD Principal de Products + Integración Cloudinary

Este documento describe la implementación del CRUD principal de productos en el panel de administración, incluyendo la integración con Cloudinary para la gestión de imágenes.

---

## Índice
1. [Estructura de archivos](#estructura-de-archivos)
2. [Funcionalidades implementadas](#funcionalidades-implementadas)
3. [Modelo Product](#modelo-product)
4. [Controlador ProductController](#controlador-productcontroller)
5. [FormRequests (Validaciones)](#formrequests-validaciones)
6. [Servicio CloudinaryService](#servicio-cloudinaryservice)
7. [Rutas](#rutas)
8. [Vistas](#vistas)
9. [Configuración de Cloudinary](#configuración-de-cloudinary)
10. [Middleware Admin](#middleware-admin)

---

## Estructura de archivos

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       └── ProductController.php    # Controlador CRUD
│   ├── Middleware/
│   │   └── AdminMiddleware.php          # Middleware de admin
│   └── Requests/
│       ├── StoreProductRequest.php      # Validación crear
│       └── UpdateProductRequest.php     # Validación editar
├── Models/
│   └── Product.php                      # Modelo con relaciones
└── Services/
    └── CloudinaryService.php            # Servicio Cloudinary

resources/views/
├── admin/
│   ├── dashboard.blade.php              # Dashboard admin
│   └── products/
│       ├── index.blade.php              # Listado + filtros
│       ├── create.blade.php             # Formulario crear
│       ├── edit.blade.php               # Formulario editar
│       ├── show.blade.php               # Detalle producto
│       └── trash.blade.php              # Papelera
└── layouts/
    └── admin.blade.php                  # Layout Bootstrap

routes/
└── web.php                              # Rutas admin

bootstrap/
└── app.php                              # Registro middleware
```

---

## Funcionalidades implementadas

### CRUD Completo
- ✅ **Listado** con paginación (10 productos por página)
- ✅ **Filtros** por marca (brand_id) y categoría (category_id)
- ✅ **Búsqueda** por nombre o SKU
- ✅ **Crear** producto con validaciones
- ✅ **Editar** producto con validaciones
- ✅ **Eliminar** (SoftDelete - borrado lógico)
- ✅ **Papelera** para ver productos eliminados
- ✅ **Restaurar** productos de la papelera
- ✅ **Eliminar permanentemente** (borrado físico)

### Integración Cloudinary
- ✅ Subida de imágenes a Cloudinary
- ✅ Guardado de `image_url` y `cloudinary_public_id` en BD
- ✅ Actualización de imagen (elimina anterior, sube nueva)
- ✅ Eliminación de imagen de Cloudinary al borrar permanentemente
- ✅ Preview de imagen antes de subir

---

## Modelo Product

**Archivo:** `app/Models/Product.php`

```php
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'sku', 'description', 'price', 'stock',
        'image_url', 'cloudinary_public_id', 'brand_id', 'active',
    ];

    // Relaciones
    public function brand(): BelongsTo        // 1:n con brands
    public function categories(): BelongsToMany  // n:m con categories
    public function orders(): BelongsToMany      // n:m con orders
}
```

**Campos de la tabla `products`:**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK autoincremental |
| name | string | Nombre del producto |
| sku | string | Código único (unique) |
| description | text | Descripción (nullable) |
| price | decimal(10,2) | Precio en euros |
| stock | integer | Unidades disponibles |
| image_url | string | URL de Cloudinary (nullable) |
| cloudinary_public_id | string | ID en Cloudinary (nullable) |
| brand_id | foreignId | FK a brands |
| active | boolean | Estado activo/inactivo |
| deleted_at | timestamp | SoftDelete |
| created_at | timestamp | Fecha creación |
| updated_at | timestamp | Fecha actualización |

---

## Controlador ProductController

**Archivo:** `app/Http/Controllers/Admin/ProductController.php`

### Métodos implementados:

| Método | Ruta | Descripción |
|--------|------|-------------|
| `index()` | GET /admin/products | Listado con filtros y paginación |
| `create()` | GET /admin/products/create | Formulario crear |
| `store()` | POST /admin/products | Guardar nuevo producto |
| `show()` | GET /admin/products/{id} | Ver detalle |
| `edit()` | GET /admin/products/{id}/edit | Formulario editar |
| `update()` | PUT /admin/products/{id} | Actualizar producto |
| `destroy()` | DELETE /admin/products/{id} | SoftDelete |
| `trash()` | GET /admin/products/trash | Papelera |
| `restore()` | POST /admin/products/{id}/restore | Restaurar |
| `forceDelete()` | DELETE /admin/products/{id}/force-delete | Borrar permanente |

### Ejemplo de filtros en `index()`:

```php
// Filtro por marca
if ($request->filled('brand_id')) {
    $query->where('brand_id', $request->brand_id);
}

// Filtro por categoría (relación n:m)
if ($request->filled('category_id')) {
    $query->whereHas('categories', function ($q) use ($request) {
        $q->where('categories.id', $request->category_id);
    });
}
```

---

## FormRequests (Validaciones)

### StoreProductRequest
**Archivo:** `app/Http/Requests/StoreProductRequest.php`

```php
public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
        'description' => ['nullable', 'string', 'max:5000'],
        'price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
        'stock' => ['required', 'integer', 'min:0', 'max:99999'],
        'brand_id' => ['required', 'exists:brands,id'],
        'categories' => ['required', 'array', 'min:1'],
        'categories.*' => ['exists:categories,id'],
        'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        'active' => ['boolean'],
    ];
}
```

### UpdateProductRequest
Similar a StoreProductRequest pero con regla `unique` que ignora el producto actual:

```php
'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($productId)],
```

### Mensajes de error personalizados
Ambos FormRequests incluyen mensajes en español:

```php
public function messages(): array
{
    return [
        'name.required' => 'El nombre del producto es obligatorio.',
        'sku.unique' => 'Este código SKU ya existe en otro producto.',
        'price.min' => 'El precio no puede ser negativo.',
        'categories.required' => 'Debes seleccionar al menos una categoría.',
        'image.max' => 'La imagen no puede superar los 2MB.',
        // ...
    ];
}
```

---

## Servicio CloudinaryService

**Archivo:** `app/Services/CloudinaryService.php`

```php
class CloudinaryService
{
    private const PRODUCTS_FOLDER = 'tecnooutlet/products';

    // Subir imagen nueva
    public function uploadImage(UploadedFile $image): array
    {
        $result = Cloudinary::upload($image->getRealPath(), [
            'folder' => self::PRODUCTS_FOLDER,
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto',
            ]
        ]);

        return [
            'url' => $result->getSecurePath(),
            'public_id' => $result->getPublicId(),
        ];
    }

    // Eliminar imagen
    public function deleteImage(?string $publicId): bool

    // Actualizar imagen (elimina anterior + sube nueva)
    public function updateImage(UploadedFile $newImage, ?string $oldPublicId): array
}
```

---

## Rutas

**Archivo:** `routes/web.php`

```php
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // Dashboard
        Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');

        // Rutas adicionales (papelera, restaurar, eliminar permanente)
        Route::get('products/trash', [ProductController::class, 'trash'])
            ->name('products.trash');
        Route::post('products/{id}/restore', [ProductController::class, 'restore'])
            ->name('products.restore');
        Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])
            ->name('products.forceDelete');

        // CRUD resource
        Route::resource('products', ProductController::class);
    });
```

### Lista de rutas generadas:

| Método | URI | Nombre | Acción |
|--------|-----|--------|--------|
| GET | /admin | admin.dashboard | Dashboard |
| GET | /admin/products | admin.products.index | Listado |
| GET | /admin/products/create | admin.products.create | Formulario crear |
| POST | /admin/products | admin.products.store | Guardar |
| GET | /admin/products/{id} | admin.products.show | Detalle |
| GET | /admin/products/{id}/edit | admin.products.edit | Formulario editar |
| PUT | /admin/products/{id} | admin.products.update | Actualizar |
| DELETE | /admin/products/{id} | admin.products.destroy | SoftDelete |
| GET | /admin/products/trash | admin.products.trash | Papelera |
| POST | /admin/products/{id}/restore | admin.products.restore | Restaurar |
| DELETE | /admin/products/{id}/force-delete | admin.products.forceDelete | Borrar permanente |

---

## Vistas

### Layout Admin (`layouts/admin.blade.php`)
- Sidebar con navegación
- Bootstrap 5 via CDN
- Bootstrap Icons
- Alertas de sesión (success/error)
- Responsive

### Listado (`products/index.blade.php`)
- Filtros: búsqueda, marca, categoría
- Tabla con paginación
- Badges de stock (verde/amarillo/rojo)
- Modal de confirmación para eliminar

### Crear/Editar (`products/create.blade.php`, `products/edit.blade.php`)
- Formulario completo con validación
- Selección múltiple de categorías (checkboxes)
- Preview de imagen antes de subir
- Switch para estado activo/inactivo

### Detalle (`products/show.blade.php`)
- Información completa del producto
- Imagen de Cloudinary
- Datos técnicos (public_id, URL)

### Papelera (`products/trash.blade.php`)
- Lista de productos eliminados
- Botones para restaurar o eliminar permanentemente
- Modal de confirmación para borrado permanente

---

## Configuración de Cloudinary

### 1. Crear cuenta en Cloudinary
1. Ir a https://cloudinary.com/
2. Registrarse (plan gratuito disponible)
3. Acceder al Dashboard
4. Copiar las credenciales

### 2. Configurar variables de entorno
En el archivo `.env`:

```env
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
```

O configuración separada:
```env
CLOUDINARY_CLOUD_NAME=tu_cloud_name
CLOUDINARY_API_KEY=tu_api_key
CLOUDINARY_API_SECRET=tu_api_secret
```

### 3. Paquete instalado
```bash
composer require cloudinary-labs/cloudinary-laravel
```

---

## Middleware Admin

**Archivo:** `app/Http/Middleware/AdminMiddleware.php`

```php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Acceso denegado. Se requieren permisos de administrador.');
    }

    return $next($request);
}
```

**Registro en:** `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

---

## Cómo probar

### 1. Ejecutar migraciones y seeders
```bash
php artisan migrate:fresh --seed
```

### 2. Credenciales de admin
- **Email:** admin@tecnooutlet.com
- **Password:** password

### 3. Acceder al panel
- URL: http://localhost:8000/admin

### 4. Probar CRUD
1. Crear un producto nuevo con imagen
2. Ver que la imagen se sube a Cloudinary
3. Editar el producto y cambiar la imagen
4. Eliminar el producto (va a papelera)
5. Restaurar desde papelera
6. Eliminar permanentemente

---

## Commits sugeridos

```bash
git add .
git commit -m "feat(products): add ProductController with CRUD operations"
git commit -m "feat(products): add FormRequests with Spanish validation messages"
git commit -m "feat(cloudinary): add CloudinaryService for image management"
git commit -m "feat(admin): add admin layout with Bootstrap 5"
git commit -m "feat(products): add views for index, create, edit, show, trash"
git commit -m "feat(admin): add AdminMiddleware for access control"
git commit -m "feat(products): add filters by brand and category"
git commit -m "feat(products): add SoftDelete with trash and restore"
```

---

## Autor
**David** - CRUD Products + Cloudinary

---

## Notas para el equipo

### Para Hugo (PayPal + Cloudinary config):
- El servicio `CloudinaryService` ya está creado y listo para usar
- Solo necesitas configurar las variables en `.env`
- Puedes usar el mismo servicio para otras entidades si lo necesitas

### Para David (compañero) - Tienda:
- Los productos tienen la relación `categories()` para mostrar en la tienda
- El campo `image_url` tiene la URL de Cloudinary lista para usar en `<img>`
- Los productos inactivos (`active = false`) no deberían mostrarse en la tienda

### Para Ale (Brands/Categories):
- Los modelos `Brand` y `Category` ya tienen SoftDeletes configurados
- Las factories ya están creadas con datos realistas
- Puedes usar el layout `layouts/admin.blade.php` para tus vistas
