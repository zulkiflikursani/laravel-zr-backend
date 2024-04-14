<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return User::all();
    }

    public function user()
    {
        return Auth::user();
    }

    public function register(Request $request)
    {
        $user = User::create([
            "company" => $request->input('company'),
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "password" => Hash::make($request->input('password')),
            "level" => $request->input('level'),
        ]);
        return $user;
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                "message" => "invalid credensial",
            ], Response::HTTP_UNAUTHORIZED);
        };

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie("jwt", $token, 60 * 24); //1 day
        // $cookie = cookie("jwt", $token); //1 day
        return response([
            "name" => $user->name,
            "email" => $user->email,
            "level" => $user->level,
            "company" => $user->company,
            "accessToken" => $token
        ])->withCookie($cookie);
    }
    public function findEmail(string $email)
    {
        $result = User::where(['email', $email]);
        return $result;
    }
    /**
     * Store a newly created resource in storage.
     */

    // public function refresToken()
    // {
    //     $user = Auth::user();
    //     $token = $user->ref();
    //     $cookie = cookie("jwt", $token); //1 day
    //     return response([
    //         "refresToken" => $token
    //     ])->withCookie($cookie);
    // }
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        $result = User::find($user);

        return $result;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    public function provider(Request $request)
    {
        $result = false;
        $email = User::where("email", $request->input('email'))->first();
        if ($email == null) {
            $result = false;
        } else {
            $result = true;
        }

        return response([
            "status" => $result,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }
}
