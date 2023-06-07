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

use App\Services\FunctionResult as Result;
use App\Services\LocationImage as LocationImageService;

use App\Repository\LocationRepository;

class Location
{

    /**
     * @var LocationRepository
     */
    protected $locations;

    /**
     * @var Tag
     */
    protected $tag;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var LocationImageService
     */
    protected $locationImage;

    public function __construct(
        LocationRepository $locations,
        Tag $tag,
        Route $route,
        Category $category,
        LocationImageService $locationImage
    )
    {
        $this->locations = $locations;
        $this->tag = $tag;
        $this->route = $route;
        $this->category = $category;
        $this->locationImage = $locationImage;
    }

    public function show(int $id): Result
    {
        $location = $this->locations
            ->with(['images', 'category.category', 'tags.tag', 'route', 'ymaps_marker'])
            ->setPresenter(LocationShowPresenter::class)
            ->find($id);

        return Result::success($location);
    }

    public function edit(int $id): Result
    {
        $location = $this->locations
                            ->with(['images', 'category.category', 'tags.tag', 'route', 'ymaps_marker'])
                            ->setPresenter(LocationEditPresenter::class)
                            ->find($id);

        return Result::success($location);
    }

    public function store(array $data): Result
    {
        $created_location = $this->locations->create([
            'title'         => $data['title'],
            'description'   => $data['description'],
        ]);

        $this->category->linkToLocation($created_location->id, $data['category']);

        if (!empty($data['route'])) {
            $this->route->linkToLocation($created_location->id, $data['route']);
        }

        foreach ($data['images'] as $image_path) {
            $this->locationImage->storeAndlinkToLocation($image_path, $created_location->id);
        }

        foreach ($data['tags'] as $tag_text) {
            $tag_id = $this->tag->store($tag_text);
            $this->tag->linkToLocation($created_location->id, $tag_id);
        }

        return Result::success($created_location->id);
    }

    public function update(array $data, int $id): Result
    {
        $created_location = $this->locations->update([
            'title'         => $data['title'],
            'description'   => $data['description'],
        ], $id);

        $existing_location_category = LocationCategory::select('id', 'category_id')
                                            ->where('location_id', $id)
                                            ->first();

        if ($existing_location_category->category_id !== $data['category']) {
            $this->category->linkToLocation($created_location->id, $data['category']);
            $existing_location_category->delete();
        }

        $existing_route = LocationRoute::select('id', 'route_id')
                                    ->where('location_id', $id)
                                    ->first();

        if ($existing_route->route_id !== $data['route'] && !empty($data['route'])) {
            $this->route->linkToLocation($created_location->id, $data['route']);
            $existing_route->delete();
        }

        foreach ($data['images'] as $image_path) {
            $existing_image = LocationImage::select('id')
                                    ->where('image_path', $image_path)
                                    ->first();

            if (empty($existing_image)) {
                $this->locationImage->storeAndlinkToLocation($image_path, $created_location->id);
            }
        }

        foreach ($data['tags'] as $tag_text) {
            $tag_id = $this->tag->store($tag_text);
            $existing_tag_location = LocationTagLocation::select()
                                            ->where('tag_id', $tag_id)
                                            ->where('location_id', $created_location->id)
                                            ->first();

            if (empty($existing_tag_location)) {
                $this->tag->linkToLocation($created_location->id, $tag_id);
            }
        }

        return Result::success($created_location->id);
    }

}
