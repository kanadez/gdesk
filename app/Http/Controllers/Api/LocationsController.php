<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 * Date: 18.07.18
 * Time: 15:43
 */

namespace App\Http\Controllers\Api;

use App\Criteria\RoutesWithImageCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateLocationRequest;
use App\Presenter\RouteForPopularPresenter;
use App\Presenter\RoutePresenter;
use App\Repository\RouteRepository;
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
     * @var RouteRepository
     */
    private $routes;

    /**
     * @var Location
     */
    private $location;

    public function __construct(
        CategoryRepository $categories,
        Location $location,
        RouteRepository $routes
    )
    {
        $this->categories = $categories;
        $this->routes = $routes;
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
        $categories = $this->categories->all();
        $routes = $this->routes
                        ->pushCriteria(RoutesWithImageCriteria::class)
                        ->setPresenter(RouteForPopularPresenter::class)
                        ->orderBy('created_at', 'desc')
                        ->all();

        return response()->json([
            'categories' => $categories,
            'popular_routes' => $routes['data']
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

    public function show(int $id)
    {
        $show_result = $this->location->show($id);

        return response()->json([
            'status' => $show_result->success,
            'location' => $show_result->returnValue
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

    public function update(UpdateLocationRequest $request, int $id)
    {
        $update_result = $this->location->update($request->validated(), $id);

        return response()->json([
            'status' => $update_result->success,
            'location_id' => $update_result->returnValue
        ]);
    }

    public function destroy()
    {

    }

}
