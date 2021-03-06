# WebX-Routes - A PHP controller framework

Main features and design goals of webx-routes:
* Simplicity - Easy to follow request/response routes to business logic in a natural hierarchical model.
* Scalability - never load unnecessary files
* Dependency injection (IOC) everywhere.
* Testability - clear separation of view- and business objects by glueing it all together.

## Getting started

In `composer.json` add:

```json
 {
    "require" : {
        "webx/routes" : "major.minor.patch"
    }
 }
```

## Writing your first Routes index.php `public/index.php`

```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Response;

    require_once "../vendor/autoload.php"; // $_SERVER["DOCUMENT_ROOT"] points to 'public' folder.

    RoutesBootstrap::run(function(Response $response) {
        $response->typeJson([
            "user" =>
                ["name" => "Mr. Andersson"]
            ]
        ]);
        $response->data(1998,"user.popular"); //Merges the data into 'user.popular'
    });
```

Will generate JSON response:
```php
    {
        "user" : {
            "name" => "Mr. Andersson",
            "popular" => 1998
        }
    }
```

## Built-in ResponseTypes in Routes
Routes supports the following ResponseTypes out of the box
* `JsonResponse` Renders data as Json (Default ResponseType).
* `TemplateResponseType` Renders data with a template (Twig)
* `RawResponseType` Renders data as is.
* `DownloadResponseType` Renders data as a downloadable file.
* `RedirectResponseType` 301 or 302 redirect to a different url.
* `FileContentResponseType` Sends a file's content to browser with auto detecting content-type.

## Routing in Routes
```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Routes;
    use WebX\Routes\Api\Response;

    RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("api",function(Routes $routes) {

            $routes->onMatch("v(?P<version>\d+)$",function(Routes $routes,$version) {
                $routes->load("api_v{$version}");                      // $version from RegExp

            })->onAlways(Response $response) {
                $response->typeJson(["message"=>"Not a valid API call"]);
            });

        })->onAlways(function(Response $response){
            $response->typeRaw();
            $response->data("Sorry, page not found.");
            $response->status(404);

        })
    });
```

## Routing in Routes - with url parameters example
```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Routes;
    use WebX\Routes\Api\Response;

    RoutesBootstrap::run(function(Routes $routes) {
        $routes->onAlways(function(Response $response, $myParam="default") {
            $response->typeRaw($myParam);
        }
    });
```
Will render:
* `hello` for request on `/hello`
* `default` for request on `/`


### Route switches:
Route switches are evaluated top-down. If a route-switch is executed no further switches, in same the scope, are evaluated and executed.

The following route switches are supported
* `onAlways($action)` Executes without evaluation.
* `onTrue($expression,$action)` Executes if `$expression` evaluates to `true`
* `onSegment("url-segment",$action)` Evaluates the current url segment (complete url exploded by `/`). Within a route-switch match the current url-segment will advance one position.
* `onMatch("reg-exp",$action)` Evaluates the reg-exp against a string (url is default). Matched parameters in the reg-exp will be used if the same variable name is used in the `$action`;

## Using Twig

`page.twig`

```twig
    <html>
        <body>
            <h1>Welcome {{user.name}}</h1>
            Your input was {{input}}
        </body>
    </html>
```

```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Response;
    use WebX\Routes\Api\Request;

    RoutesBootstrap::run(function(Response $response, Request $request) {
          $response->typeTemplate()->id("page");
          $response->data(["name"=>"Mr. Andersson"],"user");
          $response->data($request->parameter("input"), "input");
    });
```

## Reading input
Routes provides a unified and type-safe way to read request input from query parameters and json- form-encoded requests.

```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Response;
    use WebX\Routes\Api\Request;

    RoutesBootstrap::run(function(Response $response, Request $request) {
          $reader = $request->reader(Request::INPUT_AS_JSON);
          $response->typeJson(["greeting"=>"Hello, {$reader->asString("user.name")}"]);
    });
```


### Configuring Twig

Load a configuration `changetwig` (can be any name) at Bootstrap time.

Example: To change Twigs tag-delimeters to `{{{` and `}}}` (To simplify mixed Angular and Twig in the same page).
```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Routes;
    use WebX\Routes\Api\Response;

    RoutesBootstrap::run([function(Routes $routes) {

        $routes->onAlways(function(Response $response) {
              $response->templateType()->id("page");
              $response->data(["name"=>"Mr. Andersson"],"user");
        })

    },"changetwig"]);
```

Override the setting for `TemplateResponseType` to add a configurator for Twig
`config/changetwig.php`:
```php
    return [
        "responseTypes" => [
            "WebX\\Routes\\Api\\ResponseTypes\\TemplateResponseType" => [
                "config" => [
                    "configurator" => function(Twig_Environment $twig) {
                        $lexer = new Twig_Lexer($twig, array(
                            'tag_variable'  => array('{{{', '}}}')
                        ));
                        $twig->setLexer($lexer);
                    },
                    "options" => [    // Passed as second argument to Twig_Environment
                        "cache" => "/tmp/twig_cache"
                    ]
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
    use WebX\Routes\Api\Response;
    use MyBusiness\Api\Services\IAdminService;

    RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("admin",[function(Response $response, IAdminService $adminService) {
              $response->data($adminService->countAdmins(),"count");
        },"admin"]);

        // The admin-configuration is only loaded if routes matched the `admin` segment.
    });
```

#Config file anatomy
The config files, that may be loaded at any level, are normal php files that are expected to return an `array`.

Minimal config file (that does nothing):

```php
<?php

return [];

?>
```

## Ioc container
The [WebX-Ioc container](https://github.com/niclaslindberg/webx-ioc) is embedded in the WebX-Routes framework. WebX-Routes supports dynamic registration / static invokation of services in the config `ioc` section.

Dynamically register a service with the WebX-Ioc container.
`config/someconfig.php`
```php
<?php

return [
    "ioc" => [
        "register" => [ // The classes will be scanned for all their implemented interfaces.
            [MyClass:class],
            [MyOtherClass:class]
        ],
        "initStatic" => [ //
            [MyValueObject::class,"initMethod"] // The static "initMethod" will be invoked with declared dependencies.
        ]
    ],
    "mappings" => [
        "closureParameterName" => "iocInstanceId"
    ]

]
```


#Working with Controllers
Routes support a more traditional controller structure as well. Controllers are simple classes with their methods and constructors invoked with IOC support.

Routes supports `$action` to be defined as a `string` in the format `ControllerClass#method`

```php
    use WebX\Routes\Api\RoutesBootstrap;
    use WebX\Routes\Api\Routes;

    RoutesBootstrap::run(function(Routes $routes) {

        $routes->onSegment("admin",['MyBusiness\\Controllers\\AdminCtrl','adminConfig']
        // The admin-configuration is only loaded if routes matched the `admin` segment. Methods on the
        // controller will automatically be mapped by the next available segment

        // If no next segment exist Routes will map the request to `index()` of the controller instance
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

        public function countAdmins(Response $response, RawResponseType $responseType, IAdminService $adminService) {
            $response->type($responseType);
            $response->data("Hello there " + $adminService->countAdmins() + " admin(s)");
        }
    }
    #Controller functions can be invoked with user parameters. Parameters, taking precedence over IOC injected ones,
    #can be defined in the last arguemnt `$parameters` array or with parametes defiend in the `onMatch` switch.
```

## Defining default namespaces for loading controllers
Full class names can be skipped by adding namespaces in the `namespaces` section of a dynamic configuration.

`config/admin.php`:
```php
    return [
        "namespaces" => ["MyBusiness\\Controllers"]
    ]
```

## Creating your own `ResponseType`
To implement your own ResponseType simply create an interface that extends `ResponseType` with an implementation configure it with `ioc/register` in a config file. See `bootstrap_config.php` of how to configure.

Configuring your own `ResponseType` in your config file:
```
    return [
        "responseTypes" => [
            "YourNamespace\\YourResponseTypeInterface" => [
                "class" => "YourNamespace\\YourResponseTypeClass",
                "config" => [
                    "yourSetting" => false // Will be available by Configuration to ResponseType.
                ]
            ]
        ]
    ]
```


### Configuring Routes
Standard configuration in Routes is based on the applications directory relativly to the `$_SERVER['DOCUMENT_ROOT']`.

Configuring RoutesBootstrap
```php
    RoutesBootstrap::run($action,[
        "home" => "../"         // Default.
                                // Use '/' to have application in same directory
                                // as public files (not recommended).
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

## Tests
Execute in root directory
```bash
    phpunit -c tests
```




