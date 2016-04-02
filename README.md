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

### Configuring Twig

Load a configuration `changetwig` (can be any name) at Bootstrap time.

Example: To change Twigs tag-delimeters to `{{{` and `}}}` (To simplify mixed Angular and Twig in the same page).
```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Routes;
    use WebX\Routes\Api\Responses\TemplateResponse;

    RoutesBootstrap::run([function(Routes $routes) {

        $routes->onAlways(function(TemplateResponse $response) {
              $response->setTemplate("page");
              $response->setContent(["name"=>"Mr. Andersson"],"user");
        })

    },"changetwig"]);
```

Override the setting for `TemplateResponse` to add a configurator for Twig
`config/changetwig.php`:
```php
    return [
        "responses" => [
            "WebX\\Routes\\Api\\Responses\\TemplateResponse" => [
                "config" => [
                    "configurator" => function(Twig_Environment $twig) {
                        $lexer = new Twig_Lexer($twig, array(
                            'tag_variable'  => array('{{{', '}}}')
                        ));
                        $twig->setLexer($lexer);
                    }
                ]
            ]
        ]
    ]
```



## Loading configurations and the IOC container
All logic, in Routes, is executed in ```actions```. An action can be either a:
  * ```\Closure```
  * ```string``` (In format "ControllerClass#method")

To support lazy loading of configurations Routes allows actions to be defined as an `array` in the format:
`[$action,"config1","config2","configN"]`

`src/MyBusiness/Impl/Services/AdminService.php`
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
            "register" => [
                [AdminService::class]
            ]
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

### Configuring Routes
Standard configuration in Routes is based on the applications directory relativly to the `$_SERVER['DOCUMENT_ROOT']`.

Configuring RoutesBootstrap
```php
    RoutesBootstrap::run($action,[
        "home" => "../"         // Default.
                                // Use '/' to have application in same directory
                                // as public files (not reecommended).
    ]);
```

The default directory structure for a Routes application:
```
    /
        /config          (Config files loaded by [$action, "someconfig"]
            someconfig.php

        /routes          (Files loaded by Routes->load("someroute")
            someroute.php

        /templates       (Templates loaded by TemplateResponse->setTemplate("sometemplate")
            sometemplate.twig

        /public          ($_SERVER['DOCUMENT_ROOT'])
            index.php

        /vendor          (Composer)
            /webx
                /routes
                /ioc
```

#Working with Controllers
Routes support a more traditional controller structure as well. Controllers a simple classes with their methods invoked with IOC support.

Routes supports `$action` to be defined as a `string` in the format `ControllerClass#method`

```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Routes;

    RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("myMethod"",['MyBusiness\\Controllers\\AdminCtrl#myMethod',"admin"]
        // The admin-configuration is only loaded if routes matched the `myMethod` segment.
    });
```

`src/MyBusiness/Controllers/AdminController.php`
```php

    namespace MyBusiness\Controllers;

    class AdminController {

        private $logService;

        public function __construct(ILogService $logService) {
            $this->logService = $logService;
        }

        public function countAdmins(ContentResponse $response, IAdminService $adminService) {
            $response->setContent("Hello there " + $adminService->countAdmins() + " admin(s)");
        }
    }
    #Controllers support both method- and constructor IOC. Parameters, taking precedence over IOC injected ones, can be defined in the last arguemnt `$parameters` array.
```

## Defining default namespaces for loading controllers
Full class names can be skipped by adding namespaces in the `namespaces` section of a dynamic configuration.

`config/admin.php`:
```php
    return [
        "namespaces" => ["MyBusiness\\Controllers"]
    ]
```


## Tests
Execute in root directory
```bash
    phpunit -c tests
```




