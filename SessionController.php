<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function getsession()
    {
        $getsession = array();
        $getsessval = array();
        $appendSql = '';
        if (!empty($_REQUEST['instituteadmin'])) {
            $instituteadmin = $_REQUEST['instituteadmin'];
            $appendSql .= " and `cdp_institution_account_user`.`user_id`='$instituteadmin'";
        }
        if (!empty($_REQUEST['institutionname'])) {
            $institutionname = $_REQUEST['institutionname'];
            $appendSql .= " and`cdp_institution_sessions`.`institute_name`='$institutionname'";
        }
        
        if (!empty($_REQUEST['ipaddress'])) {
            $ipaddress = $_REQUEST['ipaddress'];
            $appendSql .= "and `cdp_institution_sessions`.`ip_address`='$ipaddress'";
        }
        if (!empty($_REQUEST['institutioncode'])) {
            $institutioncode = $_REQUEST['institutioncode'];
            $appendSql .= " and `cdp_institution_details`.`institution_code`='$institutioncode'";
        }
        if (!empty($_REQUEST['sessionstatus'])) {
            $sessionstatus = $_REQUEST['sessionstatus'];
            $appendSql .= " and `cdp_institution_sessions`.`is_active`='$sessionstatus'";
        }
        if (!empty($_REQUEST['fromdate'])) {
            $fromdate = $_REQUEST['fromdate'];
            $appendSql .= " and `cdp_institution_sessions`.`last_used` >='" .  $fromdate . " 00:00:00' ";
        }
        if (!empty($_REQUEST['todate'])) {
            $todate = $_REQUEST['todate'];
            $appendSql .= " and `cdp_institution_sessions`.`last_used` >='" .  $todate . " 23:59:59'";
        }
        $Session = DB::select("select *
                                from `cdp_institution_sessions`
                                join `cdp_institution_account_user`
                                 ON `cdp_institution_account_user1`.`institution_id`=`cdp_institution_sessions`.`institute_id`
                                 join `cdp_institution_details`
                                 ON `cdp_institution_details`.`institution_id`=`cdp_institution_sessions`.`institute_id`
                                 where 1  $appendSql order by `cdp_institution_sessions`.`last_used` DESC ");
                                print_r($Session);
                                exit;
       
                               foreach($Session As $Session1 ){
                                $getsession['session_id']=$Session1->session_id;
                                $getsession['institute_name']=$Session1->institute_name;
                                $getsession['ip_address']=$Session1->ip_address;
                                $getsession['is_active']=$Session1->is_active;
                                $getsession['institute_id']=$Session1->institute_id;
                                $product['msg'] = 'Success';
                                array_push($getsessval, $getsession);
                               }  
                             
                               return json_encode($getsessval);
    }
}

