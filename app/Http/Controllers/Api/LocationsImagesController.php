<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 18.07.18
 * Time: 15:43
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LocationImageUploader;
use Illuminate\Http\Request;

class LocationsImagesController extends Controller
{

    /**
     * @var LocationImageUploader
     */
    private $locationImageUploader;

    public function __construct(LocationImageUploader $locationImageUploader)
    {
        $this->locationImageUploader = $locationImageUploader;
    }

    public function index()
    {

    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $images = $request->allFiles();
        $upload_result = $this->locationImageUploader->store($images);

        if ($upload_result->success) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'stored_images' => $upload_result->returnValue
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'error' => $upload_result->errorMessage,
                'data' => [
                    'stored_images' => $upload_result->returnValue
                ]
            ], 422);
        }
    }

}
