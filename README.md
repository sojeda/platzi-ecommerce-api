## Curso de Laravel Avanzado en Platzi

Creación de un sistema que permitirá a tus usuarios puntuar compras y a otros usuarios desde 1 a 5 estrellas, implementando: Model Factory y seeders para generar datos; relaciones polimórficas entre tus clases; eventos que se dispararán ante las acciones de tus usuarios, service providers y service containers para aspectos como autenticación; y todo esto podrás publicarlo dentro de Packagist para ser reutilizado en múltiples proyectos.

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

1. Crear CategoryControllerTest y crear cada caso de uso para la API de Categorias. ```php artisan make:test CategoryControllerTest```
2. Crea mi modelo Categoria con artisan e indicar las flags necesarios para que ademas cree la migracion, factory, seeder y controllador de API con ```php artisan make:model Category --api --all```
3. Editar migracion para crear la tabla.
4. Crear las rutas y apuntarlas a cada metodo de mi API. Se puede usar  ```Route::apiResource('categories', 'CategoryController');```
5. Programar la logica de negocio dentro de CategoryController
6. Ir Testeando cada método con CategoryControllerTest ``vendor/bin/phpunit --filter=CategoryControllerTest``

## Clase 2 

1. Crear ProductControllerTest y crear cada caso de uso para nuestra API de Productos. ```php artisan make:test ProductControllerTest```
2. Crea mi modelo Producto con artisan e indicar las flags necesarios para que ademas cree la migracion, factory, seeder y controllador de API con ```php artisan make:model Produdct --api --all```
3. Editar migracion de productos para crear la tabla.
4. Crear las rutas y apuntarlas a cada metodo de mi API. Se puede usar  ```Route::apiResource('products', 'ProductController');```
5. Programar la logica de negocio dentro de ProductController
6. Ir Testeando cada método con ProductControllerTest ``vendor/bin/phpunit --filter=ProductControllerTest``

*Reto de la Clase*: Crear Endpoint para Categorias. 

## Clase 1

Repaso general de Laravel, y creación de proyecto Base.
