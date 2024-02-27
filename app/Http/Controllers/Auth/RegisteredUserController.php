<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'same:confirm_password', Rules\Password::defaults()],
            'confirm_password' => 'required',
        ]);



        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);




        event(new Registered($user));


        $user->sendEmailVerificationNotification();

        Auth::login($user);

        // * Return a JSON response with  user successfully registered and email verification sent
        return response()->json(
            [
                'message' => 'User successfully registered and email verification sent',
                'user' => $user
            ],
            Response::HTTP_CREATED
        );
    }
}
