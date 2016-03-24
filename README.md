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

## Writing your first Routes index.php.

```php
    use WebX\Routes\Util\RoutesBootstrap;

    $routes = RoutesBootstrap::create();
    $routes->onAlways(function(ContentResponse $response) {
          $response->setContent("Hello, there!");
    });
```

## Routing in Routes
```php
    use WebX\Routes\Util\RoutesBootstrap;

    $routes = RoutesBootstrap::create();
    $routes->onSegment("api",function(Routes $routes) {
        $routes->onMatch("v(?P<version>\d+)$",function(Routes $routes,$version) {
            $routes->load("api_v{$version}");
        })->onAlways(JsonResponse $response) {
            $response->setData(["message"=>"Not a valid API call"]);
        });
    })->onAlways(function(ContentResponse $response){
        $response->setContent("Sorry, page not found.");
        $response->setStatus(404);
    });

```



