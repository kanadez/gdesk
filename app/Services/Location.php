<?php

namespace App\Services;

use App\Models\LocationCategory;
use App\Models\LocationRoute;
use App\Models\LocationTag;
use App\Models\User;
use App\Models\LocationImage;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

use App\Presenter\LocationPresenter;

use App\Services\FunctionResult as Result;

use App\Repository\LocationRepository;

class Location
{

    /**
     * @var LocationRepository
     */
    protected $locations;

    public function __construct(
        LocationRepository $locations
    )
    {
        $this->locations = $locations;
    }

    public function edit(int $id): Result
    {
        $location = $this->locations
                            ->with(['images', 'category.category', 'tags', 'route'])
                            ->setPresenter(LocationPresenter::class)
                            ->find($id);

        return Result::success($location);
    }

    public function store(array $data): Result
    {
        $created_location = $this->locations->create([
            'title'         => $data['title'],
            'description'   => $data['description'],
        ]);

        $new_category = new LocationCategory();
        $new_category->location_id = $created_location->id;
        $new_category->category_id = $data['category'];
        $new_category->save();

        if (!empty($data['route'])) {
            $new_route = new LocationRoute();
            $new_route->location_id = $created_location->id;
            $new_route->route_id = $data['route'];
            $new_route->save();
        }

        foreach ($data['images'] as $image) {
            $new_image = new LocationImage();
            $new_image->location_id = $created_location->id;
            $new_image->image_path = $image;
            $new_image->save();
        }

        foreach ($data['tags'] as $tag) {
            $new_tag = new LocationTag();
            $new_tag->location_id = $created_location->id;
            $new_tag->tag = $tag;
            $new_tag->save();
        }

        return Result::success($created_location->id);
    }

}
