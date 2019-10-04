<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
class OccupationController extends Controller {


    public function occupationdropdown(){

        $dropdownarray=array();
        $dropdownarray1=array();
        $droplist=DB::select("select * from `cdp_occupation_mst` where 1");
        if($droplist){
            foreach($droplist as $dropdownlist1){
                $dropdownarray['occupation_id']=$dropdownlist1->occupation_id;
                $dropdownarray['occupation']=$dropdownlist1->occupation;
                array_push($dropdownarray1,$dropdownarray);
    }


        }else{
                $$dropdownarray1['msg']='Empty Result';

        }
                   
                        return json_encode($dropdownarray1);


    }


}


?>