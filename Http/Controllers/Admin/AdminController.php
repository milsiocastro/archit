<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller{

    protected $rules = [
        'password' => 'required_with:password_confirmation|min:6|confirmed',
        'password_confirmation' => 'min:6'
    ];

    public function update(Request $request){
        $request->validate($this->rules);
        $idUser = Auth::id();
        $user = User::find($idUser);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('admin.reset')->with('msgSuccess', 'Informacion Registrada');
    }
}