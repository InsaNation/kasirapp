<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['login', 'showLoginForm', 'logout']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required'],
        ]);
    
        $response = Http::post('http://127.0.0.1:8001/api/login', [
            'name' => $credentials['name'],
            'password' => $credentials['password'],
        ]);
    
        \Log::info('API Response', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);
    
        if ($response->successful()) {
            $responseData = $response->json();
    
            // Directly access the 'user' key
            $userData = $responseData['user'] ?? null;
    
            if ($userData && isset($userData['is_deleted'])) {
                if ($userData['is_deleted'] == '0') {
                    // Find or create the user in the database
                    $user = User::firstOrCreate(
                        ['name' => $userData['name']],
                        ['password' => Hash::make($credentials['password']), 'role' => $userData['role']]
                    );
    
                    Auth::login($user);
    
                    if ($user->role == 'kasir') {
                        return redirect()->route('index_transaksi')->with('success', 'Login Success, Welcome Kasir');
                    } elseif ($user->role == 'admin') {
                        return redirect()->route('dashboard_admin')->with('success', 'Login Success, Welcome Admin');
                    } else {
                        return redirect()->route('login-page')->with('error', 'You are not authorized to access this page.');
                    }
                } else {
                    return redirect()->route('login-page')->with('error', 'User account is deleted.');
                }
            } else {
                \Log::error('Unexpected API response format', ['response' => $responseData]);
                return redirect()->route('login-page')->with('error', 'Unexpected API response format.');
            }
        } else {
            \Log::error('API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return redirect()->route('login-page')->with('error', 'The provided credentials are incorrect or the API request failed.');
        }
        
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
