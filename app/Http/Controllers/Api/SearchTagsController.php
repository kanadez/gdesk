<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 * Date: 18.07.18
 * Time: 15:43
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SearchTags;
use Illuminate\Http\Request;

use App\Http\Requests\Api\SearchTagsRequest;

use App\Services\Route;

class SearchTagsController extends Controller
{

    /**
     * @var SearchTags
     */
    private $search;

    public function __construct(
        SearchTags $search
    )
    {
        $this->search = $search;
    }

    public function index(SearchTagsRequest $request)
    {
        $search_result = $this->search->find($request->validated());

        return response()->json([
            'status' => $search_result->success,
            'results' => $search_result->returnValue
        ]);
    }

}
