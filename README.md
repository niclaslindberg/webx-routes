# WebX-Routes - A PHP controller framework

Main features and design goals of webx-routes:
* Simplicity
* Testability
* No framework code in business logic

## Get started

In `composer.json` add:

```json
 {
    "require" : {
        "webx/routes" : "X.Y.Z"
    }
 }

## Writing your first routes index.php.

```php
    use WebX\Routes\Util\RoutesBootstrap;

    $routes = RoutesBootstrap::create();
    $routes->onAlways(function(ContentResponse $response) {
          $response->setContent("Hello, there!");
    });
```

