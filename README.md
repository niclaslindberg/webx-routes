# WebX-Routes - A PHP controller framework

Main features and design goals of webx-routes:
* Simplicity
* Testability
* Dependency injection (IOC) everywhere.
* Contextual (lazy) configuration loading.

## Get started

In `composer.json` add:

```json
 {
    "require" : {
        "webx/routes" : "major.minor.patch"
    }
 }
```

## Writing your first Routes index.php

```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Responses\ContentResponse;

    require_once "../vendor/autoload.php";  //If Routes loaded with composer

    RoutesBootstrap::run(function(ContentResponse $response) {
        $response->setContent("Hello, there!");
    });
```

## Routing in Routes
```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Routes;
    use WebX\Routes\Api\Responses\ContentResponse;

    RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("api",function(Routes $routes) {

            $routes->onMatch("v(?P<version>\d+)$",function(Routes $routes,$version) {
                $routes->load("api_v{$version}");
            })->onAlways(JsonResponse $response) {
                $response->setData(["message"=>"Not a valid API call"]);
            });

        })->onAlways(function(ContentResponse $response){
            $response->setContent("Sorry, page not found.");
            $response->setStatus(404);

        })->onException(function(SomeException $e, ContentResponse $response){
            $response->setContent("Some specific error occurred");
            $response->setStatus(200);

        })->onException(function(Exception $e, ContentResponse $response){
            $response->setContent("Unknown occurred");
            $response->setStatus(500);

        })
    });
```

## Using Twig

`page.twig`

```twig
    <html>
        <body>
            <h1>Welcome {{user.name}}</h1>
        </body>
    </html>
```

```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Routes;
    use WebX\Routes\Api\Responses\TemplateResponse;

    RoutesBootstrap::run(function(Routes $routes) {

        $routes->onAlways(function(TemplateResponse $response) {
              $response->setTemplate("page");
              $response->setContent(["name"=>"Mr. Andersson"],"user");
        })

    });
```

## Loading configurations and the IOC container
All logic, in Routes, is executed in ```actions```. An action can be either a:
  * ```\Closure```
  * ```string``` (In format "ControllerClass#method")

To support lazy loading of configurations Routes allows actions to be defined as an `array` in the format:
`[$action,"config1","config2","configN"]`

`src/MyBusiness/Impl/Services/Admin.php`
```php
    use MyBusiness\Api\Services\IAdminService;

    class AdminService implements IAdminService {
        public function __construct() {}

        public function countAdmins() {
            return 3;
        }
    }
```


`config/admin.php`:
```php
    use MyBusiness\Impl\Services\AdminService;

    return [
        "ioc" => [
            ["register",AdminService::class]
        ]
    ]
```

```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Routes;
    use WebX\Routes\Api\Responses\ContentResponse;
    use MyBusiness\Api\Services\IAdminService;

    RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("admin",[function(ContentResponse $response, IAdminService $adminService) {
              $response->setContent(sprintf("System admins: %s",$adminService->countAdmins()));
        },"admin"]);

        // The admin-configuration is only loaded if routes matched the `admin` segment.
    });
```


## Tests
Execute in root directory
```bash
    phpunit -c tests
```




