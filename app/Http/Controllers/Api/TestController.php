<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 * Date: 18.07.18
 * Time: 15:43
 */

namespace Modules\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\Core\Contracts\Collections\TestQuestionsCollectionContract;

class TestController extends Controller
{
    /**
     *
     * @var TestQuestionsCollectionContract
     */
    private $testQuestions;

    public function __construct(TestQuestionsCollectionContract $testQuestions)
    {
        $this->testQuestions = $testQuestions;
    }


    public function index()
    {

    }

    public function getQuestions()
    {
        return $this->testQuestions->all();
    }


}
