<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SessionController extends Controller
{
    public function authenticate()
    {
        $data = $this->validateWith([
            'account' => 'string|required',
            'password' => 'string|required',
        ]);

        /** @var User|null $user */
        $user = User::query()->where('account', $data['account'])->first();

        if ($user === null || !Hash::check($data['password'], $user->password_hash)) {
            throw new UnauthorizedHttpException('Bad credentials');
        }

        Auth::login($user);

        return \response()->json($user);
    }

    public function getCurrent()
    {
        return \response()->json(Auth::user());
    }

    public function destroy()
    {
        Auth::guard('web')->logout();
        return response()->noContent();
    }
}
