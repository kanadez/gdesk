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

class Tag
{

    public function __construct(

    )
    {

    }

    /**
     * Создает хеш-тег, если его еще нет в базе, и возвращает его id. Если есть, возвращает id существующего
     *
     * @param string $tag_text
     * @return int
     */
    public function store(string $tag_text): int
    {
        $existing_tag = LocationTag::select('id')->where('tag', $tag_text)->first();

        if (empty($existing_tag)) {
            $new_tag = new LocationTag();
            $new_tag->tag = $tag_text;
            $new_tag->save();

            return $new_tag->id;
        }

        return $existing_tag->id;
    }

    /**
     * Прикрепляет тег к локации
     *
     * @param int $location_id
     * @param int $tag_id
     */
    public function linkToLocation(int $location_id, int $tag_id): void
    {
        $new_tag_location = new LocationTagLocation();
        $new_tag_location->location_id = $location_id;
        $new_tag_location->tag_id = $tag_id;
        $new_tag_location->save();
    }

}
