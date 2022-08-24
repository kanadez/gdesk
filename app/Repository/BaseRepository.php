<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 18.07.18
 * Time: 10:48
 */

namespace App\Repository;

use App\Models\BaseModel;
use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;

abstract class BaseRepository extends PrettusBaseRepository
{
    protected const BEFORE_CREATE = 'beforeCreate';
    protected const AFTER_CREATE  = 'afterCreate';
    protected const BEFORE_UPDATE = 'beforeUpdate';
    protected const AFTER_UPDATE  = 'afterUpdate';

    public function getSql()
    {
        $this->applyCriteria();
        $this->applyScope();

        return [
            'sql' => $this->model->toSql(),
            'bindings' => $this->model->getBindings(),
        ];
    }

    public function create(array $attributes)
    {
        if (method_exists($this, self::BEFORE_CREATE)) {

            $result = call_user_func_array([$this, self::BEFORE_CREATE], [&$attributes]);
            if ($result === false) {
                return $result;
            }
        }

        $model = parent::create($attributes);

        if (method_exists($this, self::AFTER_CREATE)) {
            $result = call_user_func_array([$this, self::AFTER_CREATE], [$model]);
            if ($result === false)
                return $result;
        }

        return $model;
    }

    public function update(array $attributes, $id)
    {
        if (method_exists($this, self::BEFORE_UPDATE)) {
            $result = call_user_func_array([$this, self::BEFORE_UPDATE], [&$attributes, $id]);
            if ($result === false) {
                return $result;
            }
        }

        $model = parent::update($attributes, $id);

        if (method_exists($this, self::AFTER_UPDATE)) {
            $result = call_user_func_array([$this, self::AFTER_UPDATE], [$model]);
            if ($result === false)
                return $result;
        }

        return $model;
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        if (method_exists($this, self::BEFORE_UPDATE)) {
            $result = call_user_func_array([$this, self::BEFORE_UPDATE], [&$values]);
            if ($result === false) {
                return $result;
            }
        }

        $model = parent::updateOrCreate($attributes, $values);

        if (method_exists($this, self::AFTER_UPDATE)) {
            $result = call_user_func_array([$this, self::AFTER_UPDATE], [$model]);
            if ($result === false)
                return $result;
        }

        return $model;
    }
}
