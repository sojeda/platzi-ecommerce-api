## Curso de Laravel Avanzado en Platzi

Creación de un sistema que permitirá a tus usuarios puntuar compras y a otros usuarios desde 1 a 5 estrellas, implementando: Model Factory y seeders para generar datos; relaciones polimórficas entre tus clases; eventos que se dispararán ante las acciones de tus usuarios, service providers y service containers para aspectos como autenticación; y todo esto podrás publicarlo dentro de Packagist para ser reutilizado en múltiples proyectos.

## Clase 5 Reto

1. Crear un nuevo recurso con ``php artisan make:resource CategoryResource`` y el método toArray definir lo que queremos devolver al usuario.
2. Modificar controlador de Categorías, y donde se devuelva el modelo sustituir por ``return new CategoryResource($category);``
3. Donde tengamos colecciones usar el método collection para no crear un nuevo archivo ``CategoryResource::collection(Category::paginate(5))``
4. Crear FormRequest ``php artisan make:request StoreCategoryRequest`` y  ``php artisan make:request UpdateCategoryRequest`` y agregar la regla de validación para que el nombre sea único ``Rule::unique('categories')``, como en la edición tenemos que validar todos los registros menos el que se esta actualizando colocamos ``Rule::unique('categories')->ignore($this->category)``
5. Ejecutar los Test.

## Clase 5

1. Crear un nuevo recurso con ``php artisan make:resource ProductResource`` y el método toArray definir lo que queremos devolver al usuario.
2. Modificar controlador de Productos, y donde se devuelva el modelo sustituir por ``return new ProductResource($product);``

Si necesitamos trabajar con más de un Modelo tenemos las Colecciones. No es necesario crear una colección por cada Recursos que declaremos, puesto que los mismos, contienen un método llamado collection. Pero como estamos practicando, si necesitas personalizar la meta data dentro de una coleccion puedes crear una con el comando:

1. ``php artisan make:resource ProductCollection``
2. Si queremos indicar que use un recurso colocamos ``public $collects = ProductResource::class;``
3. Completar método *toArray*
4. Si necesitas paginación las collections ya vienen con todo preparado, en la consulta del modelo y cambiamos el método all por *paginate* ``new ProductCollection(Product::paginate(5));``

Separando responsabilidades:

1. Crear FormRequest ``php artisan make:request StoreProductRequest`` y  ``php artisan make:request UpdateProductRequest``
2. En cada uno de los request podemos incorporar las reglas de validación en el método rules, y el acceso al mismo en el método authorize.
3. Ejecutar los Test. Acá agregamos un nuevo test llamado *test_validation_new_product* para comprobar las reglas de validación, se puede jugar con esto y hacer distintas pruebas para verificación.

*Reto de la Clase*: Hacer lo mismo con Categorías, y agregar una regla de validación para que el nombre sea único.

## Clase 4 - Reto

1. Modificar los Test y usar el Facade de Sanctum para autenticar un usuario 
```php
use App\User;
use Laravel\Sanctum\Sanctum;

Sanctum::actingAs(
    factory(User::class)->create()
);
``` 
2. Usar Trait en el Modelo Usuario para generar tokens ``use HasApiTokens``
3. Crear Endpoint para devolver Token ``php artisan make:controller UserTokenController``
4. Crear Test ``php artisan make:test UserTokenControllerTest``
5. Crear la ruta ``Route::post('/sanctum/token', 'UserTokenController');``
6. Correr los Test y verificar que todo siga funcionando.

*Extra*:
El middleware ``auth:sanctum`` se colocaron en cada Controller para que los métodos index y show no lo tengan. Ver *ProductController* y *CategoryController*.


## Clase 4

1. Instalar paquete Laravel UI ``composer require laravel/ui``
2. ``php artisan ui bootstrap --auth``
3. Ejecutar migraciones ``php artisan migrate``

Aca puedes probar la creacion de Usuarios si quieres, por otro lado si quieres ver los estilos tienes que compilar con Laravel Mix, ejecutando ``npm run dev
`` si no tienes Node, recuerda que puedes ejecutarlo dentro de la maquina virtual de Homestead. 

Ahora vamos con la autenticación API:

1. Instalar Sanctum ``composer require laravel/sanctum``
2. ``php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"``
3. Ejecutar migraciones ``php artisan migrate``
4. Editar Kernel.php para agregar el Middleware de Sanctum a la api.
```
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

'api' => [
    EnsureFrontendRequestsAreStateful::class,
    'throttle:60,1',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

5. Proteger las rutas.
6. Testear y ver que fallen los Test por que devuelven que el Usuario no esta autenticado.

*Reto de la Clase*: Modificar los Test para que pasen.

## Clase 3

¿Que es y como instalar Homestead?

https://laravel.com/docs/7.x/homestead#introduction

Cambiar el motor de Base de Datos.

## Clase 2 - Reto

1. Crear CategoryControllerTest y crear cada caso de uso para la API de Categorías. ```php migración make:test CategoryControllerTest```
2. Crea mi modelo Categoría con artisan e indicar las flags necesarios para que además cree la migracion, factory, seeder y controllador de API con ```php artisan make:model Category --api --all```
3. Editar migración para crear la tabla.
4. Crear las rutas y apuntarlas a cada método de mi API. Se puede usar  ```Route::apiResource('categories', 'CategoryController');```
5. Programar la lógica de negocio dentro de CategoryController
6. Ir Testeando cada método con CategoryControllerTest ``vendor/bin/phpunit --filter=CategoryControllerTest``

## Clase 2 

1. Crear ProductControllerTest y crear cada caso de uso para nuestra API de Productos. ```php artisan make:test ProductControllerTest```
2. Crea mi modelo Producto con artisan e indicar las flags necesarios para que además cree la migración, factory, seeder y controllador de API con ```php artisan make:model Produdct --api --all```
3. Editar migración de productos para crear la tabla.
4. Crear las rutas y apuntarlas a cada método de mi API. Se puede usar  ```Route::apiResource('products', 'ProductController');```
5. Programar la lógica de negocio dentro de ProductController
6. Ir Testeando cada método con ProductControllerTest ``vendor/bin/phpunit --filter=ProductControllerTest``

*Reto de la Clase*: Crear Endpoint para Categorías. 

## Clase 1

Repaso general de Laravel, y creación de proyecto Base.
