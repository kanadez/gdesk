<?php

namespace App\Exceptions\Auth;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthException extends Exception implements Renderable
{
    //
    public function render()
    {
        return new JsonResponse(['message' => $this->getMessage()], Response::HTTP_BAD_REQUEST);
    }
}
