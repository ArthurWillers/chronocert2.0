<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse as ProfileInformationUpdatedResponseContract;

class ProfileInformationUpdatedResponse implements ProfileInformationUpdatedResponseContract
{
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('toast', [
                'type' => 'success',
                'message' => 'Informações do perfil atualizadas com sucesso.',
            ]);
    }
}
