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

use App\Http\Requests\Api\SearchLocationRequest;

use App\Repository\CategoryRepository;

use App\Services\Search;

class SearchController extends Controller
{

    /**
     * @var Search
     */
    private $search;

    public function __construct(
        Search $search
    )
    {
        $this->search = $search;
    }

    public function index(SearchLocationRequest $request)
    {
        $search_result = $this->search->find($request->validated());

        return response()->json([
            'status' => $search_result->success,
            'results' => $search_result->returnValue
        ]);
    }

}
