<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10)->appends(['search' => $request->search]);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Validasi manual
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email',
            'password' => 'required|string',
            'password_confirmation' => 'required|string',
        ]);

        // Cek unique username
        if (User::where('username', $request->username)->exists()) {
            return back()->with('error', 'Username is already taken.')->withInput();
        }

        // Cek unique email
        if (User::where('email', $request->email)->exists()) {
            return back()->with('error', 'Email is already registered.')->withInput();
        }

        // Cek minimal karakter
        if (strlen($request->password) < 6) {
            return back()->with('error', 'Password must be at least 6 characters.')->withInput();
        }

        // Cek konfirmasi
        if ($request->password !== $request->password_confirmation) {
            return back()->with('error', 'Password confirmation does not match.')->withInput();
        }

        // Simpan user
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password, // Auto hash by Laravel 11
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validasi dasar (biar Laravel tangani input kosong, format, dsb)
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email',
            'password' => 'nullable|string',
            'password_confirmation' => 'nullable|string',
        ]);

        // Cek unique username (kecuali milik sendiri)
        if (User::where('username', $request->username)->where('id', '!=', $user->id)->exists()) {
            return back()->with('error', 'Username is already taken.')->withInput();
        }

        // Cek unique email (kecuali milik sendiri)
        if (User::where('email', $request->email)->where('id', '!=', $user->id)->exists()) {
            return back()->with('error', 'Email is already registered.')->withInput();
        }

        // Jika user ingin mengganti password
        if (!empty($request->password)) {
            if (strlen($request->password) < 6) {
                return back()->with('error', 'Password must be at least 6 characters.')->withInput();
            }

            if ($request->password !== $request->password_confirmation) {
                return back()->with('error', 'Password confirmation does not match.')->withInput();
            }

            $user->password = $request->password; // Laravel 11 auto hash
        }

        // Update data lainnya
        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
