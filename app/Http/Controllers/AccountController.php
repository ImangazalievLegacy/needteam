<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\AccountServiceInterface;
use Redirect;

class AccountController extends Controller
{
	public function __construct(AccountServiceInterface $accountService)
    {
        $this->accountService = $accountService;
    }

    public function getCreate()
    {
        return view('account.create');
    }

    public function getLogin()
    {
        return view('account.login');
    }

    public function postCreate(Requests\CreateUserRequest $request)
    {
        $data = $request->only('username', 'email', 'password');

        if ($this->accountService->register($data)) {
            return Redirect::route('home')->with('global', 'Спасибо за регистрацию. Мы отправили код активации на ваш E-mail.');
        }

        return Redirect::route('home')->with('global', 'Ошибка при регистрации. Пожалуйста, повторите попытку позже.');
    }

    public function getActivate($code)
    {
        if ($this->accountService->activate($code)) {
            return Redirect::route('home')->with('global', 'Аккаунт активирован. Теперь вы можете войти');
        }

        return Redirect::route('home')->with('global', 'Ошибка при активации аккаунта. Пожалуйста, повторите попытку позже.');
    }

    public function postLogin(Requests\SignInRequest $request)
    {
        $data = $request->only('email', 'password');

        $data['active'] = true;

        $remember = $request->has('remember');

        if (\Auth::attempt($data, $remember)) {
            return Redirect::intended('/');
        } else {
            return Redirect::back()->with('global', 'Email/password wrong or account not activated')->withInput($data);
        }
    }

    public function getLogout()
    {
        \Auth::logout();

        return Redirect::route('home');
    }
}
