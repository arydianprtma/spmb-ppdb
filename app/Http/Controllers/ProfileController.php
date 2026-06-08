<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $avatarPath = $user->avatar;
        $avatarUrl = null;

        if ($avatarPath) {
            if (filter_var($avatarPath, FILTER_VALIDATE_URL)) {
                $avatarUrl = $avatarPath;
            } else {
                $avatarUrl = '/storage/' . ltrim($avatarPath, '/');
            }
        }

        return Inertia::render('Profile/Edit', [
            'user' => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'whatsapp' => $user->whatsapp,
                'avatar'   => $avatarUrl,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        \Log::info('Profile Update Request:', [
            'has_file' => $request->hasFile('avatar'),
            'file_details' => $request->file('avatar'),
            'all_keys' => array_keys($request->all()),
            'file_keys' => array_keys($request->allFiles()),
        ]);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'whatsapp' => 'required|string|min:10|max:15|unique:users,whatsapp,' . $user->id,
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah digunakan.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.min'      => 'Nomor WhatsApp minimal 10 digit.',
            'whatsapp.max'      => 'Nomor WhatsApp maksimal 15 digit.',
            'whatsapp.unique'   => 'Nomor WhatsApp ini sudah terdaftar.',
            'avatar.image'      => 'File harus berupa gambar.',
            'avatar.max'        => 'Ukuran foto maksimal 4MB.',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('profile', 'public');
            $validated['avatar'] = $path;
        } else {
            unset($validated['avatar']);
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
            'password.min'              => 'Password minimal 8 karakter.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
