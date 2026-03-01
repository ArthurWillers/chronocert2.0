<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('settings.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'delete_password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (! Hash::check($request->delete_password, $user->password)) {
            throw ValidationException::withMessages([
                'delete_password' => 'A senha informada está incorreta.',
            ]);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('toast', [
            'type' => 'success',
            'message' => 'Sua conta foi excluída com sucesso.',
        ]);
    }
}
