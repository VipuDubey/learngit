<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DropdownController extends Controller{

    public function getdropdownList(){

        $dropdownarray=array();
        $dropdownarray1=array();
        $appendsql='';
        if(!empty($_REQUEST['type'])&& ($_REQUEST['type']=='coupon')){
            $appendsql .=" and `cdp_dropdownlist`.`type`='coupon'";	

        }
        if(!empty($_REQUEST['type'])&& ($_REQUEST['type']=='discount')){
            $appendsql .=" and `cdp_dropdownlist`.`type`='discount'";

        }
		if(!empty($_REQUEST['type'])&& ($_REQUEST['type']=='flag')){
			$appendsql .=" and `cdp_dropdownlist`.`type`='flag'";

		}
        $dropdownvalue=DB::select("select * from `cdp_dropdownlist` where 1 $appendsql");
		//print_r($dropdownvalue);
		//die();
		if($dropdownvalue){
			foreach($dropdownvalue as $dropdownvalue1){

					$dropdownarray['dropdownname']=$dropdownvalue1->dropdown_name;
					$dropdownarray['dropdownvalue']=$dropdownvalue1->dropdown_val;
					//$dropdownarray['status']=$dropdownvalue1->status;
					array_push($dropdownarray1,$dropdownarray);
			} 
			
		}
		return json_encode($dropdownarray1);

    }
	


}


?>