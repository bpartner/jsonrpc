<?php

namespace Bpartner\Jsonrpc\Exceptions;


use Exception;
use Illuminate\Http\Response;

class ValidatorError extends Exception
{
    public function render($request)
    {
        return response()->json([
            'status' => 'error',
            'code' => Response::HTTP_METHOD_NOT_ALLOWED,
            'message' => 'Method not allowed'
        ]);
    }
}
