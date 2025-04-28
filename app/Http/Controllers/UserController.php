<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\roles;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $roles = roles::all();
        return view('users.index', compact('roles'));
    }

    public function fetch()
    {
        $users = User::with('roles')->get();
        return response()->json(['data' => $users]);
    }

}
