<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CartManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    public function create(): View
    {
        return view('customer-auth.register');
    }

    public function store(Request $request, CartManager $carts): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:160', 'unique:customers,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $customer = Customer::create($data);
        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();
        $carts->claimForCustomer($request, $customer);

        return redirect()->intended(route('home'))->with('success', 'Tu cuenta fue creada correctamente.');
    }

    public function loginForm(): View
    {
        return view('customer-auth.login');
    }

    public function login(Request $request, CartManager $carts): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);

        if (! Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => 'El correo o la contraseña no coinciden.']);
        }

        $request->session()->regenerate();
        $carts->claimForCustomer($request, $request->user('customer'));

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function forgotPasswordForm(): View
    {
        return view('customer-auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $status = Password::broker('customers')->sendResetLink($request->only('email'));

        return back()->with($status === Password::RESET_LINK_SENT ? 'status' : 'error', __($status));
    }

    public function resetPasswordForm(Request $request, string $token): View
    {
        return view('customer-auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Customer $customer, string $password): void {
                $customer->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();
            },
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
