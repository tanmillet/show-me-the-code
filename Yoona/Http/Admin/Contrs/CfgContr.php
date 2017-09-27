<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/3
 * Time: 17:44
 */

namespace App\Yoona\Http\Admin\Contrs;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Yoona\Http\BaseContr;

class CfgContr extends BaseContr
{
    //面签销售分区配置-列表
    public function saleAreaList()
    {
        return view('Yoona.Admin.sale_area_list');
    }


    //面签销售分区配置-新增
    public function saleAreaAdd()
    {
        return view('Yoona.Admin.sale_area_add');
    }


    //城市配置-列表
    public function cityList()
    {
        return view('Yoona.Admin.city_list');
    }


    //城市配置-新增
    public function cityAdd()
    {
        return view('Yoona.Admin.city_add');
    }


    //产品配置-列表
    public function productList()
    {
        return view('Yoona.Admin.product_list');
    }


    //产品配置-新增
    public function productAdd()
    {
        return view('Yoona.Admin.product_add');
    }

    //客户状态码配置-列表
    public function customStatusList($page = 0)
    {
        $arr_code = $this->model("YoCustomerStatus")->lists("code");    //状态码列表
        $arr_name = $this->model("YoCustomerStatus")->lists("name");   //状态名称列表

        $query = $this->model("YoCustomerStatus")->withTrashed()->orderBy("deleted_at")->orderBy("code");

        if (Request::has('code')) {
            $query->where('code', Request::get('code'));
        }

        if (Request::has('name')) {
            $query->where('name', Request::get('name'));
        }

        $total = $query->count();
        $list = $query->skip($page)->take(10)->get();
        return view('Yoona.Admin.custom_status_list')
            ->with("list", $list)
            ->with("total", $total)
            ->with("nowPage", ($page == 0) ? 1 : (int)($page / 10 + 1))
            ->with("arrCode", $arr_code)
            ->with("arrName", $arr_name);
    }

    //客户状态码配置-编辑（添加/修改）
    public function customStatusEdit($id = null)
    {
        $id = is_null($id) ? null : base64_decode($id);
        $model = is_null($id) ? null : $this->model("YoCustomerStatus")->find($id);
        if (!Request::isMethod('POST')) {
            return view('Yoona.Admin.custom_status_edit')->with('model', $model);
        }

        $collection = collect(Request::only('code', 'name'));
        $data = $collection->map(function ($item) {
            return trim($item);
        })->toArray();

        //验证规则
        $rules = array(
            'code' => 'required|unique:yo_customer_status,code' . (is_null($id) ? '' : ',' . $id),
            'name' => 'required|unique:yo_customer_status,name' . (is_null($id) ? '' : ',' . $id),
        );
        //提示信息
        $tips = array(
            'code.required' => '状态码不能为空',
            'code.unique' => '该状态码已存在',
            'name.required' => '状态名称不能为空',
            'name.unique' => '该状态名称已存在',
        );
        $validator = Validator::make($data, $rules, $tips);
        if ($validator->fails()) {
            $model = (object)$data;
            return view('Yoona.Admin.custom_status_edit')
                ->with('model', $model)
                ->with('message', '添加/修改客户状态配置失败，原因：' . $validator->errors()->first());
        }

        try {
            $m = $this->model("YoCustomerStatus")->firstOrCreate(['id' => $id]);
            $m->update($data);
        } catch (\Exception $e) {
            $model = (object)$data;
            return view('Yoona.Admin.custom_status_edit')
                ->with('model', $model)
                ->with('message', '添加/修改客户状态配置失败，原因：' . $e->getMessage());
        }

        return Redirect::to('yo/customstatus')->with('message', '添加/修改客户状态配置成功');
    }

    //客户状态码配置-禁用
    public function customStatusDel($id)
    {
        $id = base64_decode($id);

        $model = $this->model("YoCustomerStatus")->find($id);
        if (is_null($model) || !is_null($model->deleted_at)) {
            return Redirect::to('yo/customstatus')->with('message', '该配置记录已被删除或已被禁用');
        }

        try {
            $model->delete(); //软删除
        } catch (\Exception $e) {
            return Redirect::to('yo/customstatus')->with('message', '禁用客户状态配置失败，原因：' . $e->getMessage());
        }

        return Redirect::to('yo/customstatus')->with('message', '禁用客户状态配置成功');
    }

    //客户状态码配置-启用
    public function customStatusEnable($id)
    {
        $id = base64_decode($id);

        $model = $this->model("YoCustomerStatus")->withTrashed()->find($id);
        if (is_null($model) || is_null($model->deleted_at)) {
            return Redirect::to('yo/customstatus')->with('message', '该配置记录已被删除或已被启用');
        }

        try {
            $model->restore(); //恢复软删除
        } catch (\Exception $e) {
            return Redirect::to('yo/customstatus')->with('message', '启用客户状态配置失败，原因：' . $e->getMessage());
        }

        return Redirect::to('yo/customstatus')->with('message', '启用客户状态配置成功');
    }

    //客户影像资料类型配置列表
    public function customPicType($page = 0)
    {
        $arr_type_name = $this->model("YoCustomerPicType")->lists("type_name");    //类型名称列表

        $query = $this->model("YoCustomerPicType")->withTrashed()->orderBy("deleted_at")->orderBy("type_code");

        if (Request::has('type_name')) {
            $query->where('type_name', Request::get('type_name'));
        }

        $total = $query->count();
        $list = $query->skip($page)->take(10)->get();
        return view('Yoona.Admin.custom_pic_type_list')
            ->with("list", $list)
            ->with("total", $total)
            ->with("nowPage", ($page == 0) ? 1 : (int)($page / 10 + 1))
            ->with("arr_type_name", $arr_type_name);
    }

    //客户影像资料类型配置-编辑（添加/修改）
    public function customPicTypeEdit($id = null)
    {
        $id = is_null($id) ? null : base64_decode($id);
        $model = is_null($id) ? null : $this->model("YoCustomerPicType")->find($id);
        if (!Request::isMethod('POST')) {
            $code = is_null($id) ? buildPicType() : '';
            return view('Yoona.Admin.custom_pic_type_edit')->with('model', $model)->with("code", $code);
        }

        $collection = collect(Request::only('type_code', 'type_name'));
        $data = $collection->map(function ($item) {
            return trim($item);
        })->toArray();

        //验证规则
        $rules = array(
            'type_code' => 'required|unique:yo_customer_pic_type,type_code' . (is_null($id) ? '' : ',' . $id),
            'type_name' => 'required|unique:yo_customer_pic_type,type_name' . (is_null($id) ? '' : ',' . $id),
        );
        //提示信息
        $tips = array(
            'type_code.required' => '类型代码不能为空',
            'type_code.unique' => '该类型代码已存在，请刷新页面重新生成',
            'type_name.required' => '类型名称不能为空',
            'type_name.unique' => '该类型名称已存在',
        );
        $validator = Validator::make($data, $rules, $tips);
        if ($validator->fails()) {
            $model = (object)$data;
            return view('Yoona.Admin.custom_pic_type_edit')
                ->with('model', $model)
                ->with('message', '添加/修改客户影像资料类型配置失败，原因：' . $validator->errors()->first());
        }

        try {
            $m = $this->model("YoCustomerPicType")->firstOrCreate(['id' => $id]);
            $m->update($data);
        } catch (\Exception $e) {
            $model = (object)$data;
            return view('Yoona.Admin.custom_pic_type_edit')
                ->with('model', $model)
                ->with('message', '添加/修改客户影像资料类型配置失败，原因：' . $e->getMessage());
        }

        return Redirect::to('yo/custom-pic-type')->with('message', '添加/修改客户影像资料类型配置成功');
    }

    //客户影像资料类型配置-禁用
    public function customPicTypeDel($id)
    {
        $id = base64_decode($id);

        $model = $this->model("YoCustomerPicType")->find($id);
        if (is_null($model) || !is_null($model->deleted_at)) {
            return Redirect::to('yo/custom-pic-type')->with('message', '该配置记录已被删除或已被禁用');
        }

        try {
            $model->delete(); //软删除
        } catch (\Exception $e) {
            return Redirect::to('yo/custom-pic-type')->with('message', '禁用客户影像资料类型配置失败，原因：' . $e->getMessage());
        }

        return Redirect::to('yo/custom-pic-type')->with('message', '禁用客户影像资料类型配置成功');
    }

    //客户影像资料类型配置-启用
    public function customPicTypeEnable($id)
    {
        $id = base64_decode($id);

        $model = $this->model("YoCustomerPicType")->withTrashed()->find($id);
        if (is_null($model) || is_null($model->deleted_at)) {
            return Redirect::to('yo/custom-pic-type')->with('message', '该配置记录已被删除或已被启用');
        }

        try {
            $model->restore(); //恢复软删除
        } catch (\Exception $e) {
            return Redirect::to('yo/custom-pic-type')->with('message', '启用客户影像资料类型配置失败，原因：' . $e->getMessage());
        }

        return Redirect::to('yo/custom-pic-type')->with('message', '启用客户影像资料类型配置成功');
    }
}