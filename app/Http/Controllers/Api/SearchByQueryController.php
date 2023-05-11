<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 18.07.18
 * Time: 15:43
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\RouteRepository;
use Illuminate\Http\Request;

use App\Http\Requests\Api\SearchByQueryRequest;

use App\Repository\CategoryRepository;

use App\Services\SearchByQuery;

class SearchByQueryController extends Controller
{

    /**
     * @var SearchByQuery
     */
    private $search;

    public function __construct(
        SearchByQuery $search
    )
    {
        $this->search = $search;
    }

    public function index(SearchByQueryRequest $request)
    {
        $search_result = $this->search->find($request->validated());

        return response()->json([
            'status' => $search_result->success,
            'results' => $search_result->returnValue
        ]);
    }

}
