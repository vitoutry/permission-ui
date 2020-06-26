<?php 
namespace Vitoutry\PermissionUI\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
// use App\Repositories\UserRepository;
use App\Models\User;
use Kris\LaravelFormBuilder\FormBuilder;
use Yajra\DataTables\Datatables; 

class RoleController extends Controller {

	public function __construct(User $user)
	{
		$this->user = $user;
        $this->idTable = 1;
	}
	public function index(){

		return view('vitoutry.permissionui.role.index');
	}
    
	public function roleList()
	{
    
		$role = Role::orderBy('created_at', 'desc')->get();
        return Datatables::of($role)
        	->addColumn('id',function ($role){
        		return $this->idTable++;
        	})
            ->addColumn('action', function ($role) {
                    $result = '<a href="'.route('role.edit', $role->id).'" class="btn btn-icon btn-primary btn-xs m-b-3" title="Edit" style="margin-right: 5px;"><i class="fa fa-edit"></i></a>';
                    //  <span class="glyphicon glyphicon-star" aria-hidden="true"></span> Star
                   
                    $result .= '<a onclick="javascript:clickDestroy(this); return false;" data-item="'.$role->id.'" href="'.route('role.destroy', $role->id).'" class="sw-delete btn btn-icon btn-danger btn-xs m-b-3" title="Delete" ><i class="fa fa-trash"></i></a>';
                    return $result;
                })
            ->make(true);
	}
	public function create(FormBuilder $formBuilder){
		$form = $formBuilder->create(\Vitoutry\PermissionUI\Forms\RoleForm::class,
            [
                'method'    =>  'POST',
                'url'       =>  route('role.store'),
                'role'      =>  'form',
                'class'     =>  'form-horizontal',
                'enctype'   =>  'multipart/form-data',
                'novalidate'=>  'novalidate',
                'model'     =>  null,
            ]
        );
        $jsonRole = $this->listPermission();
        $pselected = null;
        $porigin = null;
        return view('vitoutry.permissionui.role.create', compact('form','jsonRole','pselected','porigin'));
	}
    public function store(FormBuilder $formBuilder){
        return $this->save($formBuilder);
    }  

    public function update($id,FormBuilder $formBuilder){
        return $this->save($formBuilder);
    }

    public function edit(FormBuilder $formBuilder,$id){
        $role = Role::find($id);
        $form = $formBuilder->create( \Vitoutry\PermissionUI\Forms\RoleForm::class,
            [
                'method'    =>  'PUT',
                'url'       =>  route('role.update',$id),
                'role'      =>  'form',
                'class'     =>  'form-horizontal',
                'enctype'   =>  'multipart/form-data',
                'novalidate'=>  'novalidate',
                'model'     =>  $role
            ]
        );
        $pselected = [];
        $porigin = null;
        $jsonRole = $this->listPermission();
        $role_p = $role->permissions;
        if($role_p){
            foreach ($role_p as $pkey => $pvalue) {
                $pselected[] = $pvalue->name;
            }
        }
        return view('vitoutry.permissionui.role.create', compact('form','jsonRole','pselected','porigin'));
    }

    function listPermission(){

        $permission=config('roles');
        $getAllRole=[];
        foreach($permission as $key=>$value){
            $arrRole=null;
            foreach ($value as $ind => $val) {
                $arrStr=$val.'-'.$key;
                $arrRole[]=$arrStr;
            }
            $getAllRole[ucwords($key)]=$arrRole;
        }
        $jsonRole=json_encode($getAllRole);
        return $jsonRole;
    }
    function save($formBuilder){
        try {
            $form = $formBuilder->create( \Vitoutry\PermissionUI\Forms\RoleForm::class);
            $getRole = Request::input('role');
            $getPermission = Request::input('permission');
            $getId = Request::input('id');
            $p_selected = ($getRole?explode(",", $getRole):null);
            $p_origin = ($getPermission?explode(",", $getPermission):null);
            if(Request::input('role')){
                if($getId){
                    $rules = ['name' => 'required|unique:roles,name,'.$getId ];
                    $form->validate($rules);
                }
                
                if(!$form->isValid()){
                    // flash()->error("Please verify all data is corrected!");
                    return redirect()->back()->withErrors($form->getErrors())->withInput()->with(["pselected"=>$p_selected,"porigin"=>$p_origin]);
                }else{
                    $role = trim(Request::input('name'));                    
                    // $result = $this->user->createRole($role,$p_selected,$getId);
                    $result = $this->__createRole($role,$p_selected,$getId);
                    if($result){
                        // flash()->success( ('The record was successfully saved.'));
                        return redirect(route('role.index'));
                    }
                }
            }else{
                flash()->error("Please select at lease one permission!");
                return redirect()->back()->withInput()->with(["pselected"=>$p_selected,"porigin"=>$p_origin]);
            }

        } catch (Exception $e) {
            
        }
    }
    
    public function postDelete(){
        $id_role = Request::input('item');
        $role = Role::find($id_role);
        if(count($role->users)){
           return "failed";
        }else{
            $role->delete();
            return "success";
        }
    }


    // @todo: may update to 
    private  function __createRole($role,$arrPermission,$id){
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


}