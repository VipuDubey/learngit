<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
class MessageController extends Controller
{

    
    public function getmessage()
    {
        $messagearray = array();
        $messagefarray1 = array();
        $appendsql = '';
        if (!empty($_REQUEST['emailsubject'])) {
            $emailsubject = $_REQUEST['emailsubject'];
            $appendsql .= "and `cdp_email_template_mst`.`email_subject`='" . $emailsubject . "'";
        }
        if (!empty($_REQUEST['emailtext'])) {
            $emailtext = $_REQUEST['emailtext'];
            $appendsql .= "and `cdp_email_template_mst`.`email_text`='$emailtext'";

        }
        if (!empty($_REQUEST['emaildescription'])) {

            $emaildescription = $_REQUEST['emaildescription'];
            $appendsql .= "and `cdp_email_template_mst`.`email_template_description`='$emaildescription'";
        }
        if (!empty($_REQUEST['type'])) {

            $type = $_REQUEST['type'];
            $appendsql .= "and `cdp_email_template_mst`.`type`='$type'";
        }
        if (!empty($_REQUEST['status'])) {

            $status = $_REQUEST['status'];
            $appendsql .= "and `cdp_email_template_mst`.`status`=' $status'";
        }
        $allmessage = DB::select("select
                                 `cdp_email_template_mst`.`email_template_code`,
                                 `cdp_email_template_mst`.`type`,
                                 `cdp_email_template_mst`.`email_template_description`,
                                 `cdp_email_template_mst`.`email_subject`,
                                 `cdp_email_template_mst`.`email_text`,
                                 `cdp_email_template_mst`.`created_date`,
                                 `cdp_email_template_mst`.`status`  from `cdp_email_template_mst`
                                 where 1 $appendsql order by `cdp_email_template_mst`.`created_date` DESC 
                                 ");
            if($allmessage){

                foreach ($allmessage as $messagedata) {
                    $messagearray['email_template_code'] = $messagedata->email_template_code;
                    $messagearray['type'] = $messagedata->type;
                    $messagearray['email_template_description'] = $messagedata->email_template_description;
                    $messagearray['email_subject'] = $messagedata->email_subject;
                    $messagearray['email_text'] = $messagedata->email_text;
                    $messagearray['created_date'] = $messagedata->created_date;
                    $messagearray['status'] = $messagedata->status;
                    array_push($messagefarray1,$messagearray);
                }
                    
            }else{

                    $messagefarray1['msg']='empty result';
            }
            return json_encode($messagefarray1);
      

    }
    public function createmessageTemplate(){
        $messageaarray=array();
       
            $insertmessage=DB::table('cdp_email_template_mst')->insert(
                ['type'=>$_REQUEST['type'],
                'email_template_description'=>$_REQUEST['emaildescription'],
                'email_subject'=>$_REQUEST['emailsubject'],
                'email_text'=>$_REQUEST['emailtext'],
                'created_date'=>date ( "Y-m-d H:i:s" ),
                'email_template_code'=>strtoupper ( str_replace ( ' ', '_', substr ( trim ( $_REQUEST['emaildescription'] ), 0, 30 ) ) ),
                'status'=>$_REQUEST['status']
            ]);
           // print_r($insertmessage) ;
            
            $messageaarray['message']='inserted successfully';

            if(!empty($_REQUEST['emailtemplatecode'])){
            $updatemessage=DB::table('cdp_email_template_mst')->where('email_template_code',$_REQUEST['emailtemplatecode'])
                               ->update(['type'=>$_REQUEST['type'],
                               'email_template_description'=>$_REQUEST['emaildescription'],
                               'email_subject'=>$_REQUEST['emailsubject'],
                               'emailtext'=>$_REQUEST['emailtext'],
                               'created_date'=>date ( "Y-m-d H:i:s" ),
                               
                               'status'=>$_REQUEST['status']]) ;   

                    $emailtemplatecode=$_REQUEST['emailtemplatecode'];
                    $messageaarray['message']='updated successfully';
        }
                return json_encode($messageaarray);
               
      
    }
    public function checkduplicateEmail(){
        $mesarray=array();
        $checkemail=DB::select("select * 
                                from `cdp_email_template_mst`
                                where `cdp_email_template_mst`.`email_template_code`='".$_REQUEST['emailtemplatecode']."'");
                       
                                
            if($checkemail){
                $mesarray['msg']='already exists';

            }else{

                $mesarray['msg']='empty result';
            }
              
            return json_encode($mesarray);

    }
    public function deleteEmailtemplate(){

        $array=array();
        $deleteEmail=DB::table('cdp_email_template_mst')->where('email_template_code',$_REQUEST['emailtemplatecode'])
                                     ->update(['status' => '2']);
                                                
        if($deleteEmail)
        {

                $array['response']='successfully deleted';
        }else{

            $array['response']='not Updated';
        }
            return json_encode($array);
    }
}
