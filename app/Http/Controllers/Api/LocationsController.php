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

use App\Http\Requests\Api\CreateLocationRequest;

use App\Repository\CategoryRepository;

use App\Services\Location;

class LocationsController extends Controller
{


    /**
     * @var CategoryRepository
     */
    private $categories;

    /**
     * @var Location
     */
    private $location;

    public function __construct(
        CategoryRepository $categories,
        Location $location
    )
    {
        $this->categories = $categories;
        $this->location = $location;
    }

    public function index()
    {

    }


    /**
     * Отдает данные для формы создания локации
     *
     * @return array
     */
    public function create()
    {
        return response()->json([
            'categories' => $this->categories->all()
        ]);
    }

    /**
     * Сохраняет вновь созданную локацию
     *
     * @return array
     */
    public function store(CreateLocationRequest $request)
    {
        $store_result = $this->location->store($request->validated());

        return response()->json([
            'status' => $store_result->success,
            'location_id' => $store_result->returnValue
        ]);
    }

    public function edit(int $id)
    {
        $edit_result = $this->location->edit($id);

        return response()->json([
            'status' => $edit_result->success,
            'location' => $edit_result->returnValue
        ]);
    }

    public function destroy()
    {

    }

}
