<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Admin home page
    public function adminHome()
    {
        return view('admin.adminhome');
    }
}
