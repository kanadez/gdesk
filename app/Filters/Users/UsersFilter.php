<?php

namespace App\Filters\Users;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UsersFilter
{

    public static function filter(Builder $users, Request $request): Builder
    {

        $request_data = $request->all();

        foreach ($request_data as $key => $data) {

            if ($data === null) continue;

            switch ($key) {
                case 'id':
                    $users = $users->where('p.id', $data);
                    break;

                case 'email':
                    $users = $users->whereRaw("email LIKE '%$data%'");
                    break;

                case 'name':
                    $users = $users->whereRaw("name LIKE '%$data%'");
                    break;

                case 'position':
                    $users = $users->where('position_id', $data);
                    break;

                case 'telegram':
                    if (UsersFilterValidator::min($data, 2) === false) continue;

                    $users = $users->whereRaw("id IN (SELECT user_id FROM contacts WHERE type = 'telegram' AND contact LIKE '%$data%')");
                    break;
            }
        }

        return $users;
    }

}
