<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class FormatController extends Controller
{

    public function formatlist()
    {
		header('Access-Control-Allow-Origin: *');
        $formatdata = array();
        $formatdatas = array();

        
            $Format=DB::select("select `product_format_id`,`format_name` from  `cdp_product_format_mst` where 1");
        if($Format){

                foreach ($Format as $Formats) {
                    $formatdata['formatId'] = $Formats->product_format_id;
                    $formatdata['formatName'] = $Formats->format_name;
                    array_push($formatdatas, $formatdata);
                }

            }else{
                    $formatdatas['msg']='Failure';
                    $formatdatas['code']='404';
            }
        return json_encode($formatdatas);
    }

}
