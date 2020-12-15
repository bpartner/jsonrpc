<?php

namespace Bpartner\Jsonrpc;

use Bpartner\Jsonrpc\Exceptions\RpcException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RpcFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'jsonrpc' => 'required|in:2.0',
            'method'  => 'required',
            'params'  => 'required|sometimes',
            'id'      => 'required|sometimes',
        ];
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    protected function failedValidation(Validator $validator)
    {
        if (app()->runningInConsole()) {
            return;
        }

        $response = RpcResponse::make()
            ->responseError(
                'RPC: '.implode('; ', $validator->errors()->all()),
                RpcResponse::PARSE_ERROR,
                $this->request->all()
            );

        throw new RpcException($response);
    }
}
