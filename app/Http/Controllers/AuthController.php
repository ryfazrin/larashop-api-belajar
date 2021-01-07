<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        
        // menggunakan class model User: use App\User;
        $user = User::where('email', '=', $request->email)->firstOrFail();
        $status = "error";
        $message = "";
        $data = null;
        $code = 401;
        if($user){
            // menggunakan class Hash: use Illuminate\Support\Facades\Hash;
            // jika hasil hash dari password yang diinput user sama dengan password di database user maka
            if (Hash::check($request->password, $user->password)) {
                // generate token
                $user->generateToken();
                $status = 'success';
                $message = 'Login sukses';
                // tampilkan data user menggunakan method toArray
                $data = $user->toArray();
                $code = 200;
            }
            else{
                $message = "Login gagal, password salah";
            }
        }
        else{
            $message = "Login gagal, username salah";
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function register(Request $request)
    {
        // menggunakan class validator: use Illuminate\Support\Facades\Validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', // name harus diisi teks dengan panjang maksimal 255
            'email' => 'required|string|email|max:255|unique:users', // email harus unik pada tabel users
            'password' => 'required|string|min:6', // password minimal 6 karakter
        ]);
        if ($validator->fails()) { // fungsi untuk ngecek apakah validasi gagal
            // validasi gagal
            $errors = $validator->errors();
            return response()->json([
                'data' => [
                    'message' => $errors,
                ]
                ], 400);
        }
        else {
            // validasi sukses
            $user = \App\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => json_encode(['CUSTOMER']),
            ]);
            if ($user) {
                // Auth::login($user);
                $user->generateToken();
                $status = 'success';
                $message = "register successfully";
                $data = $user->toArray();
                $code = 200;
            } else {
                $message = 'register failed';
            }    
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function logout(Request $request)
    {
        // menggunakan class Auth : use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'logout berhasil',
            'data' => null,
        ], 200);
        
    }
}
