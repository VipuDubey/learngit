<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


Class StatusController extends Controller {

   public function Getstatus(){
        header('Access-Control-Allow-Origin: *');
    $status=array();
    $statusarr=array();
    $Statusval=DB::select("select * from cdp_status where 1");
                foreach($Statusval as $Statusval1){

                        $status['statusname']=$Statusval1->status_name;
                        $status['statusvalue']=$Statusval1->status_val;

                        array_push($statusarr,$status);

                }
                return json_encode($statusarr);     


   }




}
?>