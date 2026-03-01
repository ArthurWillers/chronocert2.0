<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;

class PasswordUpdateResponse implements PasswordUpdateResponseContract
{
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('toast', [
                'type' => 'success',
                'message' => 'Senha alterada com sucesso.',
            ]);
    }
}
