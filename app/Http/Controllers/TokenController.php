<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class TokenController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($request->user());
    }

}
