<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return 'Hello World';
    }

    public function profile(Request $request)
    {
        // Получаем текущего аутентифицированного пользователя
        $user = Auth::user();

        // Возвращаем профиль пользователя
        return response()->json(['user' => $user]);
    }
}
