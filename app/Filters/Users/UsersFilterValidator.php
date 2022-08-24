<?php

namespace App\Filters\Users;

class UsersFilterValidator
{

    /**
     * Если длина строки $data меньше чем $min_length, валидация не пройдена (return false), иначе true
     *
     * @param string|null $data
     * @param int $min_len
     * @return bool
     */
    public static function min(?string $data, int $min_len): bool
    {
        if (empty($data)) return false;

        if (strlen($data) < $min_len) return false;

        return true;
    }

}
