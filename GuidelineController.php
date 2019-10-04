<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuidelineController extends Controller{

    public function selectguidelines (Request $request){
		
        header('Access-Control-Allow-Origin: *');
       
        $guidedata=array();
        $guidedatas=array();
        $guideline =DB::select("select `cdp_guideline_content`.`guideline_id`,`cdp_guideline_content`.`guideline_title`,`cdp_guideline_content`.`is_print_guideline`,`cdp_guideline_content`.`is_set_of_guideline` from `cdp_guideline_content` where `cdp_guideline_content`.`is_print_guideline` = 1 and `cdp_guideline_content`.`is_set_of_guideline` != 1" );
		
        if(!empty($_REQUEST['product_format_id']) || empty($_REQUEST['product_format_id'])){

            $guidedata['guideline_title']='eTG complete';
            array_push($guidedatas, $guidedata);
        }
        if($_REQUEST['product_format_id']=='5') {
        foreach($guideline as $guidelines){

            $guidedata['guideline_id']=$guidelines->guideline_id;
            $guidedata['guideline_title']=$guidelines->guideline_title;
            array_push($guidedatas, $guidedata);

        }
		}
   
	
        return json_encode($guidedatas);

    }


}

?>