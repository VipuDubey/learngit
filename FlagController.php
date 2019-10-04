<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class FlagController extends Controller
{

    public function searchflag()
    {
        $flagarray = array();
        $flagsarray = array();
        $appendsql = '';
        if ((!empty($_REQUEST['flagcode']))) {
            $flagcode = $_REQUEST['flagcode'];
            $appendsql .= "and `cdp_flags`.`flag`='$flagcode'";
        }
        if ((!empty($_REQUEST['flagtype']))) {
            $flagtype = $_REQUEST['flagtype'];
            $appendsql .= "and `cdp_flags`. `tablename`='$flagtype'";
        }

        if ((!empty($_REQUEST['status']))) {
            $status = $_REQUEST['status'];
            $appendsql .= "and `cdp_flags`.`status`='$status'";
        }

        $getflag = DB::select("select `cdp_flags`.`tablename`,`cdp_flags`.`flag`,`cdp_flags`.`description`,`cdp_flags`.`status` from `cdp_flags`  where 1 $appendsql ");

        foreach ($getflag as $getflags) {
            $flagarray['flagtype'] = $getflags->tablename;
            $flagarray['flagcode'] = $getflags->flag;
            $flagarray['flagdescription'] = $getflags->description;
            $flagarray['status'] = $getflags->status;
            array_push($flagsarray, $flagarray);

        }

        return json_encode($flagsarray);

        
    }
    public function updateflag()
        {

        $flagcode = $_REQUEST['flagcode'];
        // print_r($_REQUEST);
        $array = array();
        if (!empty($_REQUEST['flagcode'])) {
            $deleteflag = DB::table('cdp_flags')->where('flag', $_REQUEST['flagcode'])

                ->update(['status' => 'flagcode',
                    'lastupdated_by' => 'user',

                    'lastupdated_date' => date('Y-m-d H:i:s'),

                ]);

            if ($deleteflag) {

                $array['response'] = 'successfully update';
            } else {

                $array['response'] = 'not Updated';
            }
        } else {
            $array['response'] = 'something went wrong!';

        }
        return json_encode($array);

    }

    public function addflag()
    {

        //echo $_SERVER['REQUEST_METHOD'];
        if(!empty($_REQUEST['user_id'])){
            $user_id = $_REQUEST['user_id'];
        }
        if(!empty($_REQUEST['flagcode'])){
            $flagcode = ucfirst($_REQUEST['flagcode']) ;
        }

        if(!empty($_REQUEST['flagtype'])){
            $flagtype = $_REQUEST['flagtype'];
        }
       
        if(!empty($_REQUEST['flagdescription'])){
            $flagdescription = $_REQUEST['flagdescription'];

            }
        
        
       
        $flagarray = array();
        $checkflagstatus=DB::select("select `cdp_flags`.`flag` from  cdp_flags  where  flag='$flagcode'");
            
            if($checkflagstatus){

            $flagarray['msg']='This Flag already exists';
            }else{
            $addflag = DB::table('cdp_flags')->insert([
                'tablename' => $_REQUEST['flagtype'],
                'flag' => $flagcode,
                'description' => $flagdescription,
                'created_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s'),
                'lastupdated_date' => date('Y-m-d H:i:s'),

            ]);
            if ($addflag) {


                $flagarray['msg'] = 'success';
                $flagarray['code'] = '200';
            } else {

                $flagarray['msg'] = 'Not Inserted';
                $flagarray['code'] = '400';
            }

            }
       

        return json_encode($flagarray);

    }


}
