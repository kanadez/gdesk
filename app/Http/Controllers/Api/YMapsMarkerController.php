<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 18.07.18
 * Time: 15:43
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\CreateYMapsMarkerRequest;

use App\Repository\YMapsMarkerRepository;

class YMapsMarkerController extends Controller
{

    /**
     *
     * @var YMapsMarkerRepository
     */
    private $repo;

    public function __construct(YMapsMarkerRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        $all_markers = $this->repo->all();

        return response()->json([
            "status" => "success",
            "data"   => $all_markers
        ]);
    }

    public function create(CreateYMapsMarkerRequest $request)
    {
        $this->repo->create($request->validated());

        return response()->json([
            "status" => "success"
        ]);
    }

    public function delete()
    {

    }

}
