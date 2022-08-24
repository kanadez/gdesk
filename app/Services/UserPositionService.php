<?php

namespace App\Services;

use App\Exceptions\Auth\AuthException;
use App\Models\User;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserPositionService
{
    public function getPositions(): array
    {
        $positions = Position::select()->get()->toArray();

        return $positions;
    }

    public function assignPositionToUser(User $user, Position $position): bool
    {
        // TODO проверять полномочия на это действие
        $user->position_id = $position->id;
        return $user->save();
    }


}
