## Curso de Laravel Avanzado en Platzi

Creación de un sistema que permitirá a tus usuarios puntuar compras y a otros usuarios desde 1 a 5 estrellas, implementando: Model Factory y seeders para generar datos; relaciones polimórficas entre tus clases; eventos que se dispararán ante las acciones de tus usuarios, service providers y service containers para aspectos como autenticación; y todo esto podrás publicarlo dentro de Packagist para ser reutilizado en múltiples proyectos.

## Clase 1

Repaso general de Laravel, y creación de proyecto Base.

## Clase 2 

1. Crear ProductControllerTest y crear cada caso de uso para nuestra API de Productos. ```php artisan make:test ProductControllerTest```
2. Crea mi modelo Producto con artisan e indicar las flags necesarios para que ademas cree la migracion, factory, seeder y controllador de API con ```php artisan make:model Produdct --api --all```
3. Editar migracion de productos para crear la tabla.
4. Crear las rutas y apuntarlas a cada metodo de mi API. Se puede usar  ```Route::apiResource('products', 'ProductController');```
5. Programar la logica de negocio dentro de ProductController
6. Ir Testeando cada método con ProductControllerTest ``vendor/bin/phpunit --filter=ProductControllerTest``

*Reto de la Clase*: Crear Endpoint para Categorias. 
