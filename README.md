[![run-tests](https://img.shields.io/github/workflow/status/tyloo/api-response-helpers/run-tests?style=flat-square)](https://github.com/tyloo/api-response-helpers/actions)
[![Packagist Version](https://img.shields.io/packagist/v/tyloo/api-response-helpers?style=flat-square)](https://packagist.org/packages/tyloo/api-response-helpers)
[![Packagist PHP Version](
https://img.shields.io/packagist/php-v/tyloo/api-response-helpers?style=flat-square)](https://packagist.org/packages/tyloo/api-response-helpers)
[![Packagist License](https://img.shields.io/packagist/l/tyloo/api-response-helpers?style=flat-square)](https://packagist.org/packages/tyloo/api-response-helpers)


# API Response Helpers

A simple package allowing for consistent API responses throughout your PHP application (Symfony, Laravel).

## Requirements

- PHP `^8.0`

## Installation / Usage

`composer require tyloo/api-response-helpers`


Simply reference the required trait within your controller:

```php
<?php

namespace App\Controller;

use Tyloo\ApiResponseHelpers;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrdersController
{
    use ApiResponseHelpers;

    public function index(): JsonResponse
    {
        return $this->respondWithSuccess();
    }
}
```

Optionally, the trait could be imported within a base controller.

## Available methods

#### `respondNotFound(string|Exception $message, ?string $key = 'error')`

Returns a `404` HTTP status code, an exception object can optionally be passed.

#### `respondWithSuccess(?array $contents = [])`

Returns a `200` HTTP status code

#### `respondOk(string $message)`

Returns a `200` HTTP status code

#### `respondUnAuthenticated(?string $message = null)`

Returns a `401` HTTP status code

#### `respondForbidden(?string $message = null)`

Returns a `403` HTTP status code

#### `respondError(?string $message = null)`

Returns a `400` HTTP status code

#### `respondCreated(?array $data = [])`

Returns a `201` HTTP status code, with response optional data

#### `respondNoContent(?array $data = [])`

Returns a `204` HTTP status code, with optional response data. Strictly speaking, the response body should be empty. However, functionality to optionally return data was added to handle legacy projects. Within your own projects, you can simply call the method, omitting parameters, to generate a correct `204` response i.e. `return $this->respondNoContent()`

## Use with additional object types

In addition to a plain PHP `array`, the following data types can be passed to relevant methods:

- Objects implementing the native PHP `JsonSerializable` contract

This allows a variety of object types to be passed and converted automatically.

Below are a few common object types that can be passed.

## Motivation

Ensure consistent JSON API responses throughout an application. The motivation was primarily based on a very old inherited project. The project contained a plethora of methods/structures used to return an error:

- `return new JsonResponse(['error' => $error], 400)`
- `return new JsonResponse(['data' => ['error' => $error], 400)`
- `return new JsonResponse(['message' => $error], Response::HTTP_BAD_REQUEST)`
- `return new JsonResponse([$error], 400)`
- etc.

I wanted to add a simple trait that kept this consistent, in this case:

`$this->respondError('Ouch')`

## Contribution

Any ideas are welcome. Feel free to submit any issues or pull requests.

## Testing

`vendor/bin/phpunit`

## Credits

- [Julien Bonvarlet](https://github.com/tyloo)
- Heavily inspired by [Rob Allport](https://github.com/ultrono)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
