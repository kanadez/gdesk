<?php

namespace App\Services;

use App\Exceptions\Auth\AuthException;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Filters\Users\UsersFilter;
use phpDocumentor\Reflection\Types\Object_;

class UserService
{


    public function __construct()
    {

    }

    public function getUsers(Request $request): array
    {
        $users = User::select()
                    ->whereNull('deleted_at')
                    ->with('position', 'contacts')
                    ->orderBy('id','desc');
        $users = UsersFilter::filter($users, $request);
        $users = $users->paginate($request->input('per_page', config('app.default_per_page')));

        return ['total' => $users->count(), 'users' => $users];
    }

    public function getUser(int $id): User
    {
        $user = User::select()
                    ->with('position', 'contacts')
                    ->find($id);

        return $user;
    }

    public function createUser(string $name, string $email, string $password) : User {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);
    }

    public function changePasswordWithoutConfirm(User $user, string $new_password) : bool {
            $user->password = Hash::make($new_password);
            return $user->save();
    }

    public function deleteUser(int $id) : bool {
        $user = User::find($id);
        $user->deleted_at = Carbon::now();

        return $user->save();
    }

    public function login(string $email, string $password) : User {
        $user = User::whereEmail($email)->first();
        if (!$user) {
            throw new AuthException('User not found!');
        }

        if (!Hash::check($password, $user->password)) {
            throw new AuthException('Invalid credentials!');
        }

        return $user;
    }

    public function getSettings(User $user) : array {
        $userSettings = DB::table('user_settings')->where('user_id', $user->id)->first();

        if (is_null($userSettings)) {
            return [];
        }
        return json_decode($userSettings->settings, true);
    }

    public function saveSettings(User $user, $name, $value) {
        $userSettings = $this->getSettings($user);
        $userSettings[$name] = $value;
        DB::table('user_settings')->updateOrInsert(['user_id' => $user->id], ["settings" => json_encode($userSettings)]);
    }

    public function verify(User $user) : void {
        $user->markEmailAsVerified();
    }

    public function createToken(User $user) : string {
        return $user->createToken('default')->plainTextToken;
    }

    public function refreshToken(User $user) : string {
        $this->revokeToken($user);
        return $this->createToken($user);
    }

    public function revokeToken(User $user) : void {
        $user->tokens()->where('name', 'default')->delete();
    }

    public function revokeCurrentToken(User $user) {
        $user->currentAccessToken()->delete();
    }
}
