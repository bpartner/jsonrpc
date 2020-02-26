# Very short description of the package

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
in `config/jsonrpc.php` set namespace and Bearer token


## Basic Usage

``` bash
php artisan make:rpc MyHandler 
```

After creating handler, in handle method put your business logic and return array with response data.

``` php
public function handle(): array
{
    $myData = MyBusinessLogicClass::make();

    return ['data' => $myData];
}
```

Send POST request to `/jsonrpc/v1/endpoin` in Json RPC 2.0 standard with Bearer Token.


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
3. Create your controller and use RpcService, for example:

``` php
    private $request;

    public function __construct(RpcFormRequest $request)
    {
        $this->request = new RpcRequest($request);
    }

    public function __invoke()
    {
        $rpcService = new RpcService($this->request);

        return $rpcService->run();
    }

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
