<?php
namespace App\Yoona\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class YoBasicTrait
 * Author: promise tan
 * @package App\Yoona\Traits
 */
trait YoBasicTrait
{
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var
     */
    protected $model;

    //理解model
    /**
     * @author: promise tan
     * @param $modelName
     * @return $this
     */
    public function model($modelName, $open = true)
    {
        $lut = yoconf('constant')['ModelMap'];
        $className = collect($lut)->get($modelName, '');
        if (empty($className)) {
            throw new ModelNotFoundException;
        }
        $this->model = app()->make($className);

        return ($open) ? $this : $this->model;
    }

    /**
     * @author: promise tan
     * @param $searchModel
     * @param $byModel
     */
    public function one2One($searchModel)
    {

    }

    /**
     * @author: promise tan
     * @param $searchModel
     * @param $byModel
     */
    public function one2More($searchModel)
    {

    }

    /**
     * @param null $limit
     * @return mixed
     */
    public function paginate($limit = null)
    {
        return $this->model
            ->paginate($limit);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function where(array $data)
    {
        return $this->model->where($data);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $res = $model->save();

        return $res;
    }

    /**
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        $model = $this->model->find($id);
        $res = $model->fill($attributes)->save();

        return $res;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $attributy
     * @return mixed
     */
    public function lists($attributy)
    {
        return $this->model->lists($attributy);
    }

    /**
     * @return mixed
     */
    public function withTrashed()
    {
        return $this->model->withTrashed();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function firstOrCreate(array $attributes)
    {
        if (!is_null($instance = $this->model->where($attributes)->first())) {
            return $instance;
        }

        return $this->model->create($attributes);
    }
}