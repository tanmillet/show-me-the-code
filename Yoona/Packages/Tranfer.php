<?php

namespace App\Yoona\Packages;

use App\Yoona\Traits\YoBasicTrait;

/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/12
 * Time: 17:52
 * Class Tranfer
 * Author: promise tan
 * @package App\Yoona\Packages
 *
 * 演示示例
 *
 * $R360['R360companyName'] = '佰仟';
 * $R360['R360category'] = 'A-农、林、牧、渔业';
 * $outputs = tranfer('R360')->invoke($R360)->tranferOne('yo_trial_company')->getOutputs();
 * var_dump($outputs);
 * $outputs = tranfer('R360')->invoke($R360)->tranferOne('yo_trial_company' , true)->getOutputs();
 * var_dump($outputs);
 * $outputs = tranfer('R360')->invoke($R360)->tranferAll()->getOutputs();
 * var_dump($outputs);
 * $outputs = tranfer('R360')->invoke($R360)->tranferAll(true)->getOutputs();
 * var_dump($outputs);
 *
 */
class Tranfer
{

    use YoBasicTrait;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var array|\Illuminate\Foundation\Application|mixed
     */
    public $configs = [];

    /**
     * author: promise tan
     * Date: ${DATE}
     * @var array
     */
    public $inputs = [];

    /**
     * author: promise tan
     * Date: ${DATE}
     * @var array
     */
    public $outputs = [];

    /**
     * author: promise tan
     * Date: ${DATE}
     * @var bool
     */
    public $gc = false;

    /**
     * @author: promise tan
     * Tranfer constructor.
     * @param $origin
     */
    public function __construct($origin = '')
    {
        $origin = (empty($origin)) ? 'Trial' : $origin;
        $this->configs = yoconf('tranfer')[$origin];
    }

    /**
     * @author: promise tan
     * @param $inputs
     * @return $this
     */
    public function invoke($inputs, $gc = false)
    {
        $this->inputs = $inputs;
        $this->gc = $gc;

        return $this;
    }

    /**
     * @author: promise tan
     * @param bool $template
     * @return $this
     */
    public function tranferAll($template = false)
    {
        try {
            collect($this->configs)->map(function ($val, $table) use ($template) {
                $this->tranferOne($table, $template);
            });
        } catch (\Exception $e) {
            yolog()->error(__FUNCTION__ . ' 数据转化时出错！', 'tranfer');
        }

        return $this;
    }

    /**
     * @author: promise tan
     * @param $table
     * @param bool $template
     * @return $this
     */
    public function tranferOne($table, $template = false)
    {
        try {
            $this->outputs[$table] = [];

            collect($this->configs[$table])->map(function ($item, $field) use ($table, $template) {
                $temp = ($template) ? $field : $item;
                $this->outputs[$table][$field] = isset($this->inputs[$temp]) ? $this->inputs[$temp] : '';
            });
        } catch (\Exception $e) {
            yolog()->error(__FUNCTION__ . ' 数据转化时出错！', 'tranfer');
        }

        return $this;
    }

    /**
     * @author: promise tan
     * @param $model
     * @param $where
     * @return $this
     */
    public function tranferForm($model, $where)
    {
        try {
            $table = $model->getTable();
            $columns = $model->getTableColumns();

            $info = $model->where($where)->first();

            if (!empty($table) && !empty($columns)) {
                collect($columns)->map(function ($item, $field) use ($table, $info) {
                    $this->outputs[$item] = isset($info->$item) ? $info->$item : '';
                });
            }
        } catch (\Exception $e) {
            yolog()->error(__FUNCTION__ . ' 数据转化时出错！', 'tranfer');
        }

        return $this;
    }

    /**
     * @author: promise tan
     * @param $modelName
     * @return $this
     */
    public function gc($modelName)
    {
        try {
            if (empty($modelName) || is_null($modelName)) {
                return $this;
            }

            $gcOne = function ($modelName) {
                $model = $this->model($modelName, false);
                $table = $model->getTable();
                $columns = $model->getTableColumns();
                collect($this->outputs[$table])->map(function ($item, $field) use ($table, $columns) {
                    if (!in_array($field, $columns))
                        unset($this->outputs[$table][$field]);
                });
            };

            if (is_string($modelName)) {
                $gcOne($modelName);
            }

            if (is_array($modelName)) {
                collect($modelName)->map(function ($model, $index) use ($gcOne) {
                    $gcOne($model);
                });
            }

        } catch (\Exception $e) {
            yolog()->error(__FUNCTION__ . ' 数据转化时出错！', 'tranfer');
        }

        return $this;
    }


    /**
     * @author: promise tan
     * @return array|\Illuminate\Foundation\Application|mixed
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * @author: promise tan
     * @param array|\Illuminate\Foundation\Application|mixed $configs
     * @return Tranfer
     */
    public function setConfigs($configs)
    {
        $this->configs = $configs;
        return $this;
    }

    /**
     * @author: promise tan
     * @return array
     */
    public function getInputs()
    {
        return $this->inputs;
    }

    /**
     * @author: promise tan
     * @param array $inputs
     * @return Tranfer
     */
    public function setInputs($inputs)
    {
        $this->inputs = $inputs;
        return $this;
    }

    /**
     * @author: promise tan
     * @return array
     */
    public function getOutputs()
    {
        $this->destroy(); //释放内存
        return $this->outputs;
    }

    /**
     * @author: promise tan
     * @param array $outputs
     * @return Tranfer
     */
    public function setOutputs($outputs)
    {
        $this->outputs = $outputs;
        return $this;
    }

    /**
     * @author: promise tan
     */
    public function destroy()
    {
        unset($this->erorMsg);
        unset($this->configs);
        unset($this->inputs);
        // unset($this->outputs);
        return $this;
    }

}