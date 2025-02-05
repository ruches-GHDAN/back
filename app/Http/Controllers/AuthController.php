<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
        public function register(Request $request){
            try {
                $request->validate([
                    'firstName' => 'required',
                    'lastName' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:6'
                ], [
                    'firstName.required' => 'Le champ prénom est requis.',
                    'lastName.required' => 'Le champ nom est requis.',
                    'email.required' => 'Le champ email est requis.',
                    'email.email' => 'L\'adresse email doit être valide.',
                    'email.unique' => 'L\'adresse email est déjà utilisée.',
                    'password.required' => 'Le mot de passe est requis.',
                    'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.'
                ]);

                $user = User::create([
                    'firstName' => $request->firstName,
                    'lastName' => $request->lastName,
                    'email'=> $request->email,
                    'password' => bcrypt($request->password)
                ]);

                $token = $user->createToken('auth_token', ['expires_in' => 7200])->plainTextToken;

                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => [
                        'id' => $user->id,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'email' => $user->email,
                        'city' => $user->city
                    ]
                ]);

            } catch (ValidationException $e) {
                return response()->json(['errors' => $e->validator->errors()], 422);
            }
        }

        public function login(Request $request)
        {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ], [
                'email.required' => 'Le champ email est requis.',
                'email.email' => 'L\'adresse email doit être valide.',
                'password.required' => 'Le mot de passe est requis.'
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('auth_token', ['expires_in' => 7200])->plainTextToken;

                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => [
                        'id' => $user->id,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'email' => $user->email,
                    ]
                ]);

            } else {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        }

        public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out'], 200);
        }
}
