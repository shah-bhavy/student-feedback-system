<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeRegisteredNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private const CAPTCHA_SESSION_KEY = 'registration_captcha_answer';

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        [$first, $second] = $this->generateCaptchaOperands();

        session()->put(self::CAPTCHA_SESSION_KEY, $first + $second);

        return view('auth.register', [
            'captchaQuestion' => "{$first} + {$second}",
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['nullable', Rule::in(['student', 'faculty', 'principal'])],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'captcha_answer' => ['required', 'integer'],
        ]);

        $expectedCaptchaAnswer = (int) $request->session()->get(self::CAPTCHA_SESSION_KEY, -1);

        if ((int) $request->input('captcha_answer') !== $expectedCaptchaAnswer) {
            return back()
                ->withErrors(['captcha_answer' => 'Captcha answer is incorrect.'])
                ->withInput($request->except(['password', 'password_confirmation', 'captcha_answer']));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->input('role', 'student'),
        ]);

        $request->session()->forget(self::CAPTCHA_SESSION_KEY);

        event(new Registered($user));
        $user->notify(new WelcomeRegisteredNotification());

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function generateCaptchaOperands(): array
    {
        return [random_int(1, 9), random_int(1, 9)];
    }
}
