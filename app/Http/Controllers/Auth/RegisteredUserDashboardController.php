<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserDashboardController extends DashboardController
{
    /**
     * Срок жизни cookie
     */
    private const COOKIE_LIFETIME = 365 * 24 * 60 * 60;

    /**
     * Display the registration view.
     *
     * @return Application|ResponseFactory|Response
     */
    public function create(): Application|ResponseFactory|Response
    {
        return response(view('auth.register'))
            ->cookie('register_page_first_open_date', date('Y-m-d H:i:s'), time() + self::COOKIE_LIFETIME);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  RegisterRequest  $request
     * @return RedirectResponse
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
