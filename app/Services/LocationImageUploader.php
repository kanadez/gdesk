<?php

namespace App\Services;

use App\Exceptions\Auth\AuthException;
use App\Models\User;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Services\FunctionResult as Result;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class LocationImageUploader
{

    public function store(array $images): Result
    {
        $stored_images_names = [];

        foreach ($images as $image) {
            $image_content =  file_get_contents($image->getRealPath());

            if ($image_content === false) {
                $result = Result::error(Lang::get('errors.not_all_images_were_uploaded'));
            } else {
                $new_file_name = time().uniqid(rand());
                $file_path = "locations/images/$new_file_name.jpg";
                $store_result = Storage::disk('public')->put($file_path, $image_content);

                if ($store_result === false) {
                    $result = Result::error(Lang::get('errors.not_all_images_were_uploaded'));
                } else {
                    array_push($stored_images_names, $file_path);
                }
            }
        }

        return Result::success($stored_images_names);
    }

}
