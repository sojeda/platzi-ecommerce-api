# Curso de Laravel Avanzado en Platzi

Creación de un sistema que permitirá a tus usuarios puntuar compras y a otros usuarios desde 1 a 5 estrellas, implementando: Model Factory y seeders para generar datos; relaciones polimórficas entre tus clases; eventos que se dispararán ante las acciones de tus usuarios, service providers y service containers para aspectos como autenticación; y todo esto podrás publicarlo dentro de Packagist para ser reutilizado en múltiples proyectos.

## Clase 11 - Reto

1. Crear evento con ``php artisan make:event ModelUnrated``
2. Agregar los parámetros requeridos al Evento
```php
private Model $qualifier;
private Model $rateable;

public function __construct(Model $qualifier, Model $rateable)
{
    $this->qualifier = $qualifier;
    $this->rateable = $rateable;
}

public function getQualifier(): Model
{
    return $this->qualifier;
}

public function getRateable(): Model
{
    return $this->rateable;
}
```
3.  Disparar evento en el trait CanRate
```php
event(new ModelUnrated($this, $model));
```
4. Crear Listener con ``php artisan make:listener SendEmailModelUnratedNotification``
5. Editar el listener para enviar una notificación si el modelo es un Producto.
```php
public function handle(ModelUnrated $event)
    {
        $rateable = $event->getRateable();

        if ($rateable instanceof Product) {
            $notification = new ModelUnratedNotification(
                $event->getQualifier()->name,
                $rateable->name
            );
            $rateable->createdBy->notify($notification);
        }
    }
```
6. Crear la notificación ``php artisan make:notification ModelUnratedNotification``
7. Editar la notificación. (ver ModelUnratedNotification.php)
8. Agregar el evento y el listener al EventServiceProvider
```php
ModelUnrated::class => [
    SendEmailModelUnratedNotificacion::class,
],
```
9. Testear.

Se puede ver el Test en RatingTest.php ``Event::assertDispatchedTimes(ModelUnrated::class);``

## Clase 11

1. Crear evento con ``php artisan make:event ModelRated``
2. Agregar los parametros requeridos al Evento
```php
public function getQualifier(): Model
{
    return $this->qualifier;
}

public function getRateable(): Model
{
    return $this->rateable;
}

public function getScore(): float
{
    return $this->score;
}
```
3.  Disparar evento en el trait CanRate
```php
event(new ModelRated($this, $model, $score));
```
4. Crear Listener con ``php artisan make:listener SendEmailModelRatedNotification``
5. Editar el listener para enviar una notificación si el modelo es un Producto.
```php
public function handle(ModelRated $event)
{
    $rateable = $event->getRateable();

    if ($rateable instanceof Product) {
        $notification = new ModelRatedNotification(
            $event->getQualifier()->name,
            $rateable->name,
            $event->getScore()
        );
        $rateable->createdBy->notify($notification);
    }
}
```
6. Crear la notificación ``php artisan make:notification ModelRatedNotification``
7. Editar la notificación para que envie un correo con el nombre del producto y la puntuación. (ver ModelRatedNotification.php)
8. Agregar el evento y el listener al EventServiceProvider
```php
ModelRated::class => [
    SendEmailModelRatedNotificacion::class
],
```
9. Testear.

Se puede ver el Test en RatingTest.php

## Clase 10 Reto

Modificar el comando de Newsletter para que dentro del correo se envien los 6 productos con mejor calificacion.

1. Usar relación HasMany dentro del trait CanBeRate
2. Hacer la consulta de Productos dentro de SendNewsletterCommand
```php
$productQuery = Product::query();

$productQuery->withCount(['qualifications as average_rating' => function ($query) {
    $query->select(DB::raw('coalesce(avg(score),0)'));
}])->orderByDesc('average_rating');

$products = $productQuery->take(6)->get();
```
3. Modificar NewsletterNotification para recibir los productos e incorporarlos al correo.

## Clase 10

Si queremos ejecutar las tareas programadas en nuestro Homestead, abrimos el archivo Homestead.yaml y en la configuración  del sitio colocamos:

```yaml
sites:
    - map: platzi-api.test
      to: /home/vagrant/code/platzi-ecommerce-api/public
      schedule: true
```

1. Editar el archivo App/Console/Kernel.php

- onOneServer: por si nuestra aplicación se ejecuta en varios servidores, poder limitar la tarea para que solo se ejecute en un único servidor.
- evenInMaintenanceMode: que nos sirve para ejecutar la tarea asi tengamos activo el modo mantenimiento.
- withoutOverlapping: Para evitar la superposición de las tareas, de tal manera que no se ejecute un comando si hay una instancia corriendo del mismo.
- sendOutputTo: nos sirve para trabajar con la salida de cada comando, lo que hice fue que cada vez que se ejecute, me escriba un archivo llamado inspire.log

2. Agregar log al .gitignore

## Clase 9 Reto

Preparar los endpoint correspondientes para que los usuarios puedan calificar productos a traves de la API.

1. Crear Controlador con los métodos ``php artisan make:controller ProductRatingController``

```php
public function rate(Product $product, Request $request)
{
    $this->validate($request, [
        'score' => 'required'
    ]);

    /** @var User $user */
    $user = $request->user();
    $user->rate($product, $request->get('score'));

    return new ProductResource($product);
}

public function unrate(Product $product, Request $request)
{
    /** @var User $user */
    $user = $request->user();
    $user->unrate($product);

    return new ProductResource($product);
}
```

2. Crear las rutas y colocarla en el grupo de rutas.
```php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('newsletter', [\App\Http\Controllers\NewsletterController::class, 'send'])->name('send.newsletter');

    Route::post('products/{product}/rate', [ProductRatingController::class, 'rate']);

    Route::post('products/{product}/unrate', [ProductRatingController::class, 'unrate']);
});
```

3. Testear *ProductRatingControllerTest*

## Clase 9 

1. Crear Controller para Newsletter ``php artisan make:controller NewsletterController``
2. Crear método ``send``.
3. Crear Ruta ``Route::post('newsletter', [\App\Http\Controllers\NewsletterController::class, 'send'])->name('send.newsletter');``
4. Complementar método para que envie los correos usando el comando:
```php
Artisan::call(SendEmailVerificationReminderCommand::class);

return response()->json();
```
5. Testear endpoint.

## Clase 8 Extra

1. Agregar Barra de Progreso

```php
$this->output->progressStart($count);
$builder->each(function (User $user) {
    $user->notify(new NewsletterNotification());
    $this->output->progressAdvance();
});
$this->output->progressFinish();
```

## Clase 8 Reto

Envía un correo electronico a los usuarios que no han verificado su cuenta después de haberse registrado hace una semana

1. ``php artisan make:command SendEmailVerificationReminderCommand`` 
2. Armar query con Eloquent
3. Enviar correos y probar

## Clase 8

1. ``php artisan make:command SendNewsletterCommand``
2. Colocar una firma y descripción en el comando creado.
3. Crear notificación ``php artisan make:notification NewsletterNotification``
4. Habilitar verificación de correo implementando en el modelo ``User`` la interfaz ``MustVerifyEmail`` y modificando las rutas de Auth ``Auth::routes(['verify' => true]);``
5. Configurar Servidor SMTP, tenemos el de Homestead o podemos usar Mailtrap.
6. Crear la consulta dentro del Comando.
7. Probar que lleguen los correos.

## Clase 7 Reto

1. Utilizar el trait *CanBeRate* en Usuario.
2. Modificar los Test para probar.

## Clase 7

1. ``php artisan make:migration UpdateRatingTable``
2. Composicion de la tabla con rateable y qualifier
3. ``php artisan make:model Rating``
4. Definir mis relaciones *morphTo*
5. Definir las relaciones en *User*.
6. Hacer lo mismo en el modelo *Product*.
7. Como queremos evitar duplicar código, y nuestras relaciones son abstractas, podemos llevarlas a un trait.
8. Creo una carpeta llamada Traits y muevo el código a CanBeRated y CanRate respectivamente,
9. Correr los Test

La idea es que en nuestros trait no haya referencia alguna a ningún modelo, de tal manera que lo podamos reutilizar, así que pasemos por parámetro.

## Clase 6 Reto

1. Crear migración ``php artisan make:model AddCreatedByToProductsTable``
2. Agregar relación en Product.
```php
use App\User;

public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}
```
3. Editar el Factory
```php
$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->numberBetween(10000, 60000),
        'category_id' => function () {
            return Category::inRandomOrder()->first()->id;
        },
        'created_by' => function () {
            return User::inRandomOrder()->first()->id;
        },
    ];
});
```
Nota: Para que funcione correctamente hay que asegurarnos que ejecutamos los Seeders en el Orden correcto. (Ver DatabaseSeeder.php)

## Clase 6

1. Crear migración ``php artisan make:migration add_category_id_to_products_table``
2. Editar migración para agregar la columna a la tabla Productos y ademas colocarle una categoría por defecto llamada "Otras" que creamos en la migración.
3. Ejecutar migración ``php artisan migrate``
4. Agregar relación en el Modelo Product:
```php
use App\Category;

public function category()
{
    return $this->belongsTo(Category::class);
}
```
5. Agregar relación inversa en Category.
```php
use App\Product;

public function products()
{
    return $this->hasMany(Product::class);
}
```
6. Modificar el Factory de Productos, para cuando lo usemos se cree con una categoria al azar.
```php
'category_id' => function (array $post) {
    return Category::inRandomOrder()->first()->id;
},
```
7. Testear y verificar que este andando todo.

###Extra: Crear Relación con un Modelo llamado Rating.

1. Crear Modelo (Rating.php), migracion (2020_05_23_235914_create_ratings_table.php) y Test (RatingTest.php)
2. Modificar la migración.
3. Relacionar con Productos y Usuarios (ver los modelos).


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
