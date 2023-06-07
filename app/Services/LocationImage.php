<?php

namespace App\Services;

use App\Models\LocationImage as Image;

class LocationImage
{

    public function __construct(

    )
    {

    }


    /**
     * Создает изображение и прикрепляет к локации
     *
     * @param string $image_path
     * @param int $location_id
     * @return int
     */
    public function storeAndlinkToLocation(string $image_path, int $location_id): int // TODO переделать хранение изображений с отдельными ссылками на локацию, а не location_id внутри как сейчас
    {
        $new_image = new Image();
        $new_image->location_id = $location_id;
        $new_image->image_path = $image_path;
        $new_image->save();

        return $new_image->id;
    }

}
