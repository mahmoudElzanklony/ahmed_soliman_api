<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\userFormRequest;
use App\Models\roles;
use App\Models\students_subjects_years;
use App\Models\User;
use App\Notifications\UserRegisteryNotification;
use App\Services\Messages;
use App\Services\SendWhatApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('register');
    }
    //
    public function register(userFormRequest $request)
    {

        DB::beginTransaction();
        $data = $request->validated();

        $user = User::query()->create($data);
        // create user year


        $user->createToken($data['phone'])->plainTextToken;
        DB::commit();
        return Messages::success(message: __('messages.user_registered_successfully'));
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return Messages::success(__('messages.logout_successfully'));
    }
}
