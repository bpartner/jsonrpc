# JSON-RPC 2.0 server for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bpartner/jsonrpc.svg?style=flat-square)](https://packagist.org/packages/bpartner/jsonrpc)
[![Total Downloads](https://img.shields.io/packagist/dt/bpartner/jsonrpc.svg?style=flat-square)](https://packagist.org/packages/bpartner/jsonrpc)

Simple JSON-RPC 2.0 server for Laravel, without batch request.

## Installation

You can install the package via composer:

```bash
composer require bpartner/jsonrpc

php artisan vendor:publish --tag=config
```
### Configure 
in `config/jsonrpc.php` set namespace and Bearer token (default 1234567890)

If Bearer token not work, add to your .htaccess file this rule
```
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
```

## Basic Usage

``` bash
php artisan make:rpc MyHandler 
```

After creating handler, in handle method put your business logic and return array with response data.

``` php
public function handle(): array
{
    $myData = MyBusinessLogicClass::make($this->params);

    return ['data' => $myData];
}
```

Send POST request to `/jsonrpc/v1/endpoint` in Json RPC 2.0 standard with Bearer Token.


``` json
{
    "jsonrpc": "2.0",
    "method": "myHandler",
    "params": {
        "param1": "param1",
        "param2": {
            "param3": 123,
        }
    },
    "id": "abracadabra"
}
```
for validate input parameters use `rule` method in handler.

``` php
protected function rule(): array
{
    return [
        'param1' => 'require|string',
        'param2.param3' => 'require|numeric'
    ];
}
```

## Advanced Usage

You can use any route, middleware and guards for your rpc endpoint.

1. Disable default route in `config/jsonrpc.php`
2. Create your custom route
3. Create your middleware   
4. Create your controller and use RpcService

Middleware example

``` php
class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('x-auth-token') !== config('jsonrpc.token')) {
            return  response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Unauthorized',
            ]);
        }
        return $next($request);
    }
}
```

Controller example

``` php
namespace App\Http\Controllers


use Bpartner\Jsonrpc\RpcFormRequest;
use Bpartner\Jsonrpc\RpcRequest;
use Bpartner\Jsonrpc\RpcServiceInterface; 
use Illuminate\Routing\Controller;

class MyContoller extends Controller
{
    public function __invoke(RpcFormRequest $request, RpcServiceInterface $rpcService)
    {
        return $rpcService->run();
    }
}
```

## Middleware

Create own middleware for any RpcHandler
```php
 protected string|array $middlewares = 'guest';
//or
 protected string|array $middlewares = [
    MyMiddleware::class,
    SecondMiddleware::class
];
```

## Important

This package not support Batch request. 
For example, this request not supported:
``` json
[
    {"jsonrpc": "2.0", "method": "sum", "params": [1,2,4], "id": "1"},
    {"jsonrpc": "2.0", "method": "notify_hello", "params": [7]},
    {"jsonrpc": "2.0", "method": "subtract", "params": [42,23], "id": "2"},
    {"foo": "boo"},
    {"jsonrpc": "2.0", "method": "foo.get", "params": {"name": "myself"}, "id": "5"},
    {"jsonrpc": "2.0", "method": "get_data", "id": "9"} 
]
```

## Credits

- [Alexander Zinchenko](https://github.com/bpartner)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
