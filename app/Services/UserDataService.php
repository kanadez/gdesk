<?php

namespace App\Services;

use App\Exceptions\Auth\AuthException;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Object_;

class UserDataService
{

    private $user_data_fields = [

    ];

    public function addUserData(User $user, array $data) : bool {
        $fields_to_except = [ // TODO возможно просто на фронте фильтровать данные чтобы здесь не фильтровать. в обновлении тоже
            'name',
            'email',
            'password',
            'position',
            'contacts'
        ];

        foreach($data as $key => $value) {
            if(array_search($key, $fields_to_except) === false) {
                $user->$key = $value;
            }
        }

        return $user->save();
    }

    public function updateUserData(User $user, array $data) : bool {
        $fields_to_except = [
            'password', // TODO сделать обработку пароля если он пуст
            'position',
            'contacts',
            'deleted_contacts'
        ];

        foreach($data as $key => $value) {
            if(array_search($key, $fields_to_except) === false) {
                $user->$key = $value;
            }
        }

        return $user->save();
    }

}
