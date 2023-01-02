<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\Auth\RegisterController as BackpackRegisterController;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BackpackRegisterController
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $user_model_fqn = config('backpack.base.user_model_fqn');
        $user = new $user_model_fqn();
        $users_table = $user->getTable();
        $email_validation = backpack_authentication_column() == 'email' ? 'email|' : '';

        return Validator::make($data, [
            'name'                             => 'required|max:255',
            'username'                         => 'required|max:255',
            backpack_authentication_column()   => 'required|'.$email_validation.'max:255|unique:'.$users_table,
            'password'                         => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->is_admin = true;
        $user->password = bcrypt($data['password']);
        $user->save();
        return $user;
    }
    public function showRegistrationForm(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('vendor.backpack.base.auth.register');
    }
}
