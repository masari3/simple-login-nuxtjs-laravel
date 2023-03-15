<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'fullname'              => 'string|required|max:200',
            'username'              => 'string|required|max:100',
            'email'                 => 'email|required|unique:users,email',
            'password'              => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        DB::beginTransaction();
        try {
            if ($validate->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Validation failed!',
                    'error'     => $validate->errors(),
                ]);
            }

            $data = new User();
            $data->fullname     = $request['fullname'];
            $data->username     = $request['username'];
            $data->email        = $request['email'];
            $data->password     = Hash::make($request['password']);
            $data->save();

            DB::commit();

            return response()->json([
                'status'        => true,
                'message'       => 'New user has been registered!',
                'token'         => $data->createToken($data->username)->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status'        => false,
                'message'       => $th->getMessage(),
            ], 500);
        }
    }
}
