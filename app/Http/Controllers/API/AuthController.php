<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);
        //  assigning role
        if($request->role == 'normaluser')
        {
            $user->assignRole('normaluser');
        }else{
            $user->assignRole('venderuser');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
    }
    public function update(Request $request,$id)
    {
        $user = User::where('id',$id)->first();
        if($user != null)
        {
            // $validator = Validator::make($request->all(),[
            //     'name' => 'required|string|max:255',
            //     'email' => 'required|string|email|max:255|unique:users',
            //     'password' => 'required|string|min:8'
            // ]);


            $user->name  = $request->name;
            $user->email = $request->email;
            if($request->password)
            {
                $user->password = Hash::make($request->password);
            }

            // if($validator->fails()){
            //     return response()->json($validator->errors());
            // }

            //  assigning role
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()
                ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
        }else{
            return response()
            ->json(['message' => 'record not found the id is invalid!' ]);
        }



    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}