<?php

namespace App\Services;

use App\Repository\TagsRepository;

use App\Services\FunctionResult as Result;

use App\Models\LocationTag;

class SearchTags
{

    /**
     * @var TagsRepository
     */
    protected $tags;

    public function __construct(TagsRepository $tags)
    {
        $this->tags = $tags;
    }

    public function find(array $data)
    {
        $finded_tags = LocationTag::search($data['query'])->query(function ($builder) {
            $builder->select(['id', 'tag']);
        })->get();

        return Result::success($finded_tags->toArray());
    }

}
