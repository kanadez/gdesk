<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AppController extends Controller
{
    public function index() {
        return view('gdesk');
    }

    public function test(Request $request) {
    }
}
