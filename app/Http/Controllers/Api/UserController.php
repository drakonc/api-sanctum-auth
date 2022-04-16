<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        
        return response()->json([
            "status" => 1,
            "msg" =>  "Registro de usuario Exitoso"
        ]);

    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::whereEmail($request->email)->first();

        if(isset($user->id)){ //Todo: El id esta definico no es Null
            if(Hash::check($request->password,$user->password)){
                //Todo: Creamos el token
                $token = $user->createToken("auth_token")->plainTextToken;
                return response()->json([
                    "status" => 1,
                    "msg" =>  "Usuario Logueado Correctamente",
                    "access_token" => $token 
                ]);
            }else{
                //Todo: Si la contraseña no es correcta
                return response()->json([
                    "status" => 0,
                    "msg" =>  "Contraseña Errada"
                ],404);
            }
        }else{
            //Todo: Usuario No Existe
            return response()->json([
                "status" => 0,
                "msg" =>  "Usuario No Existe"
            ],404);
        }

        return response()->json([
            "status" => 1,
            "user" =>  $user
        ]);
    }

    public function userProfile(){
        return response()->json([
            "status" => 1,
            "msg" =>  "Acerca del Perfil de usuario",
            "data" => auth()->user()
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "msg" =>  "Cierre de sesion exitoso"
        ]);        
    }

}
