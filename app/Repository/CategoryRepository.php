<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 *
 */

namespace App\Repository;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function model()
    {
        return Category::class;
    }
}
