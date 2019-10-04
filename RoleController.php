<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    public function selectroles()
    {
        $role = array();
        $role1 = array();
        $getroles = DB::select("select `cdp_role`.`id`,`cdp_role`.`description` from cdp_role");
        if ($getroles) {
            foreach ($getroles as $allrole) {
                $role['id'] = $allrole->id;
                $role['description'] = $allrole->description;
                array_push($role1, $role);
            }
        } else {
            $role1['msg'] = 'empty result';
            $role1['status'] = '400';
        }

        return json_encode($role1);
    }

    public function getInstitutions()
    {

        $inst = array();
        $inst1 = array();


        $selectInstitution = DB::select('select `cdp_institution_details`.`institution_id`,
                                    `cdp_institution_details`.`institution_name` 
                                    from cdp_institution_details');
        if ($selectInstitution) {
            foreach ($selectInstitution as $instlist) {
                $inst['institution_id'] = $instlist->institution_id;
                $inst['institution_name'] = $instlist->institution_name;
                array_push($inst1, $inst);
            }
            //$inst1['status']='200';
        } else {
            $inst1['msg'] = 'empty result';
            $inst1['status'] = '400';
        }

        return json_encode($inst1);
    }
}