<?php

namespace Vitoutry\PermissionUI\Forms;

use Kris\LaravelFormBuilder\Form;

class RoleForm extends Form
{
    public function buildForm()
    {
    	// $allModule = \Module::enabled();
     //    $arrModule = [];
     //    $arrModule['Role'] = "Role";
     //    if($allModule){
     //        foreach ($allModule as $mKey => $mValue) {
     //        	$valModule = ucwords($mValue->getName());
     //            $arrModule[$valModule]=$valModule;
     //        }
     //    }
        $allModule=config('roles');
        
        foreach ($allModule as $key => $value) {
            $allkey[ucwords($key)]=ucwords($key);
        }
        $this->add('name', 'text',[
        			'rules' => 'required|unique:roles',
            		'label' => ('Role Name'),
            		'attr' => ['id'=>'name']
            	])
        	->add('module', 'select', [
			    'choices' => $allkey,
			    'selected' => '',
			    'empty_value' => 'Select Module',
			    'attr' => ['id'=>'module']
			])
			->add('role','hidden')
            ->add('permission','hidden')
            ->add('id','hidden');
    }
}
