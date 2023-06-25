<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 * Date: 18.07.18
 * Time: 15:43
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\RouteRepository;
use Illuminate\Http\Request;

use App\Http\Requests\Api\SearchByCategoryRequest;

use App\Repository\CategoryRepository;

use App\Services\SearchByCategory;

class SearchByCategoryController extends Controller
{

    /**
     * @var SearchByCategory
     */
    private $search;

    public function __construct(
        SearchByCategory $search
    )
    {
        $this->search = $search;
    }

    public function index(SearchByCategoryRequest $request)
    {
        $search_result = $this->search->find($request->validated());

        return response()->json([
            'status' => $search_result->success,
            'results' => $search_result->returnValue
        ]);
    }

}
