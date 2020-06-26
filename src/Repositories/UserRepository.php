<?php 
namespace App\Repositories;

use Vitoutry\Repository\Contracts\RepositoryInterface;
use Vitoutry\Repository\Eloquent\Repository;
// use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRepository extends Repository {

    public function model() {
        return 'App\Entities\User';
    }
    public function createRole($role,$arrPermission,$id){
    	if($id){
            $existedRole = Role::find($id)->update(['name'=>$role]);
        }
        $existedRole = Role::where('name',$role)->first();
    	
    	if(!$existedRole){
    	    $existedRole = Role::create(['name' => $role]);
    	}
    	$existedRole->permissions()->detach();

    	if($arrPermission){
    		foreach ($arrPermission as $pkey => $pvalue) {
    			$existedPermission = Permission::where('name',$pvalue)->first();
    			if(!$existedPermission){
    				Permission::create(['name' => $pvalue]);
    			}
    			$existedRole->givePermissionTo($pvalue);
    		}
    	}
    	return $existedRole;
    }
    public function getSelectUser(){

        $staff = $this->model->active()->get();
        $staffs['']="";
        foreach ($staff as $key => $value) {
            $staffs[$value->id] = $value->profile->fullname();
        }
        return $staffs;
    }

    public function detectNumber($role="Representer",$id_coun){
        $user = $this->model->where('category_id', '=',NULL)->where('country_id','=',$id_coun)->get();
        $count=0;
        foreach ($user as $key => $value) {
            if($value->hasRole(ucwords($role))){
                $count++;
            }
        }
        return $count;
    }

}