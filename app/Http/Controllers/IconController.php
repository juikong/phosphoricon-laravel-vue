<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use PhosphorIcon;

class IconController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(PhosphorIcon::getData());
    }
}
