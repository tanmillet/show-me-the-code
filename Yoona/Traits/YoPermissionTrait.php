<?php
namespace App\Yoona\Traits;
use Illuminate\Support\Facades\Input;


trait YoPermissionTrait
{
	protected $admin_id = '';
	//获取管理员所拥有的权限
 	public static function get_admin_permission($admin_id){
        
        $role_id = self::get_admin_role($admin_id);
        $permission_array = self::get_permission_list_by_role_id($role_id);
        $permission = self::get_permission_by_id($permission_array,$admin_id);
        return $permission;
    }


    public static function get_admin_role($admin_id)
    {
        //$this->admin_id = $admin_id;

        $value = \Cache::remember('admin-'.$admin_id,'10',function()use($admin_id){
            $data = \DB::table('auth_role')->select('role_id')->where('admin_id',$admin_id)->get();
            $array = array();
            foreach($data as $val){
                array_push($array,$val->role_id);
            }
            return $array;
        });
         return $value;
        /*
        $data = DB::table($this->table)->select('role_id')->where('admin_id',$this->admin_id)->get();
        $array = array();
        foreach($data as $val){
            array_push($array,$val->role_id);
        }
        return $array;
        */
    }

    


    public static function get_permission_list_by_role_id($role_id)
    {
        $data = \DB::table('permission_role')->whereIn('role_id',$role_id)->get();
        $array = array();
        foreach($data as $val){
            $array = array_merge($array,unserialize($val->permission));
            $array = array_unique($array);
        }
        return $array;
    }

    public static function get_permission_by_id($id,$admin_id = ''){
      /*  $val = Cache::remember('admin_own_permission_'.serialize($id).'-'.$admin_id,'10',function(){
            $data = DB::table($this->table)->whereIn('id',$id)->where('status','1')->get();
            $array = array();
            foreach($data as $val){
                $array = array_add($array,$val->name,$val);
            }
      return $data;
        });
      */
        $data = \DB::table('permission')->whereIn('id',$id)->where('status','1')->get();
        //$array = array();
        $array["url"] = array();
        $array["parent"] = array();
        $array["menu"] = array();
        foreach($data as $val){
            //array_push($array["url"], $val->route);
            $array["url"]  = array_add($array["url"],$val->name,$val);
            if($val->node == 1){
               // $array["parent"] = array_add($array["parent"],$val->id,$val->display_name,$val->route);
               $array["parent"][$val->id]['name'] = $val->display_name;
               $array["parent"][$val->id]['url'] = $val->route;
            }elseif($val->node == 2){
                if(!isset($array["menu"][$val->param_id])){
                    $array["menu"][$val->param_id] = array();
                }
                array_push($array["menu"][$val->param_id], array($val->display_name, $val->route));
            }
            //$array = array_add($array,$val->name,$val);
        }
        return $array;
    }

    //校验是否拥有权限
    public static function is_allow($name){
        $permission = Session::get('permission');
        $permission = $permission['url'];
        if(isset($permission[$name])){
            return 'true';
        }else{
            return 'false';
        }
    }

    public static function get_permission_display_name($name){
        $permission = Session::get('permission');
        if(isset($permission[$name])){
            return $permission[$name]->display_name;
        }else{
            return false;
        }
    }
}
