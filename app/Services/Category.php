<?php

namespace App\Services;

use App\Models\LocationCategory;
use App\Models\LocationRoute;
use App\Models\LocationTag;
use App\Models\LocationTagLocation;
use App\Models\User;
use App\Models\LocationImage;

use App\Presenter\LocationEditPresenter;
use App\Presenter\LocationShowPresenter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Laravel\Scout\Searchable;

use App\Presenter\LocationPresenter;

use App\Services\FunctionResult as Result;

use App\Repository\LocationRepository;

class Category
{

    public function __construct(

    )
    {

    }


    /**
     * Прикрепляет локацию к категории
     *
     * @param int $location_id
     * @param int $tag_id
     */
    public function linkToLocation(int $location_id, int $category_id): void
    {
        $new_category = new LocationCategory();
        $new_category->location_id = $location_id;
        $new_category->category_id = $category_id;
        $new_category->save();

    }

}
