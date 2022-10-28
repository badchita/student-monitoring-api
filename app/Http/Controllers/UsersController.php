<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $status = 200;
    public function show($id)
    {
        $user = User::find($id);
        $data = New UsersResource($user);
        return response($data, $this->status);
    }
}
