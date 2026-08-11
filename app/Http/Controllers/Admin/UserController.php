<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->role === 'administrator', 403);

        return view('admin.users.index', ['users' => User::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'administrator', 403);
        $data = $this->validated($request);
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = true;
        $user = User::create($data);
        ActivityLog::record('created', $user, "Creó el usuario {$user->email}.");

        return back()->with('success', 'Usuario creado.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'administrator', 403);
        $data = $this->validated($request, $user);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        } $data['is_admin'] = true;
        $user->update($data);
        ActivityLog::record('updated', $user, "Actualizó el usuario {$user->email}.");

        return back()->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'administrator' && $user->id !== auth()->id(), 403);
        $user->delete();

        return back()->with('success', 'Usuario eliminado.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate(['name' => ['required', 'string', 'max:120'], 'email' => ['required', 'email', 'max:180', Rule::unique('users')->ignore($user)], 'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'], 'role' => ['required', Rule::in(['administrator', 'content_editor', 'product_manager', 'read_only'])]]);
    }
}
