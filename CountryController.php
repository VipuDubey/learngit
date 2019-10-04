<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CountryController extends Controller{
	
	public function getCountry(){

        $countrydata = array();
        $countrydatas = array();
        $country = DB::select('select *from cdp_country_mst');
       
            if($country){
                foreach($country as $cntry){ 
                    $countrydata['country_code']=$cntry->country_code; 
                    $countrydata['country_name']=$cntry->country_name; 
                    $countrydata['country_zone']=$cntry->country_zone; 
                    $countrydata['country_id']=$cntry->id; 
                    array_push($countrydatas,$countrydata);
                }

            }else{

                $countrydatas['msg']='empty result';
                

            }
       
            return json_encode($countrydatas);
    }
}
?>