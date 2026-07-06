<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login using Pegawai credentials
     */
    public function login(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required|string',
            'hp_pegawai' => 'required|string',
        ]);

        // Find pegawai by nama_pegawai and verify hp_pegawai
        $pegawai = Pegawai::where('nama_pegawai', $request->nama_pegawai)
                          ->where('hp_pegawai', $request->hp_pegawai)
                          ->first();

        if ($pegawai) {
            // Store pegawai data in session
            Session::put('pegawai', [
                'id' => $pegawai->id_pegawai,
                'nama' => $pegawai->nama_pegawai,
                'nomor_hp' => $pegawai->hp_pegawai,
                'jabatan' => $pegawai->jabatan,
                'alamat' => $pegawai->alamat_pegawai,
            ]);

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'login' => 'Nama pegawai atau nomor HP tidak sesuai.',
        ])->onlyInput('nama_pegawai');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Session::forget('pegawai');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
