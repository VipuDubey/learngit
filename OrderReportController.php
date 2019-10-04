<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


class OrderReportController extends Controller {

    public function searchreportorders(){
        $orderarray=array();
        $orderarray1=array();
        $appendsql='';
        if(!empty($_REQUEST['producttitle'])){
            $producttitle=$_REQUEST['producttitle'];
            $appendsql .=" and `cdp_order_transaction_detail`.`subscription_id`='".$producttitle."'";
            
        }
        if(!empty($_REQUEST['startdate'])|| !empty($_REQUEST['enddate'])){
             //$startdate=str_replace('/','-',$_REQUEST['startdate']);
            //$enddate=str_replace('/','-',$_REQUEST['enddate']);
            $startdate=$_REQUEST['startdate'];
			$enddate=$_REQUEST['enddate'];

            $appendsql .="and `cdp_order_transaction_master`.`order_date` between '$startdate' and '$enddate'  ";

        }
        $orderreport= DB::select("select `cdp_order_transaction_detail`.`transaction_id`,
                                    `cdp_order_transaction_master`.`user_id`,
                                    `cdp_order_transaction_master`.`order_number`,
                                    `cdp_order_transaction_master`.`transaction_type`,
                                    `cdp_order_transaction_master`.`payment_gateway_response`,
                                    `cdp_order_transaction_master`.`billing_email`,
                                    `cdp_order_transaction_master`.`transaction_amount`,
                                    `cdp_order_transaction_master`.`order_date`,
                                     GROUP_CONCAT(`cdp_subscription`.`title`) as `product_title`,
                                    `cdp_order_transaction_detail`.`transaction_id` 
                                    from `cdp_order_transaction_master`
                                     join `cdp_order_transaction_detail`
                                     ON `cdp_order_transaction_master`.`transaction_id`=`cdp_order_transaction_detail`.`transaction_id`
                                     join `cdp_subscription` 
                                     ON `cdp_subscription`.`subscription_id`=`cdp_order_transaction_detail`.`subscription_id`
                                     where 1 $appendsql group by `cdp_order_transaction_master`.`order_number` order by `cdp_order_transaction_master`.`order_date` desc
                                     ");
                                     
            if($orderreport){

                foreach($orderreport as $orderreports){
                    $orderarray['transaction_id']=$orderreports->transaction_id;
                    $orderarray['user_id']=$orderreports->user_id;
                    $orderarray['order_number']=$orderreports->order_number;
                    $orderarray['transaction_type']=$orderreports->transaction_type;
                    $orderarray['transaction_amount']=$orderreports->transaction_amount;
                    $orderarray['payment_gateway_response']=$orderreports->payment_gateway_response;
                    $orderarray['billing_email']=$orderreports->billing_email;
                    $orderarray['product_title']=$orderreports->product_title;
                    $orderarray['order_date']=$orderreports->order_date;
                        array_push($orderarray1,$orderarray);

                }    
                
            }else{

                $orderarray1['msg']='empty result';
            }

                return json_encode($orderarray1);
                       
                        


    }
    public function email()
    {
    
    $notemess=array();
   
    $email_from= $_REQUEST['email_from']?$_REQUEST['email_from']:'';
    $email_to=$_REQUEST['email_to']?$_REQUEST['email_to']:'';
    $email_cc=$_REQUEST['email_cc']?$_REQUEST['email_cc']:'';
    $email_subject=$_REQUEST['email_subject']?$_REQUEST['email_subject']:'';
    $email_text=$_REQUEST['email_text']?$_REQUEST['email_text']:'';
    
    
    $email_send_date=date('Y-m-d', strtotime($_REQUEST['email_send_date']));
    $lastupdated_by=$_REQUEST['lastupdated_by']?$_REQUEST['lastupdated_by']:'';
    $lastupdated_date=date('Y-m-d', strtotime($_REQUEST['lastupdated_date']));
    $email_send_status=$_REQUEST['email_send_status']?$_REQUEST['email_send_status']:'';

    $email_send_status_Response=$_REQUEST['email_send_status_Response']?$_REQUEST['email_send_status_Response']:'';
    $email_priority=$_REQUEST['email_priority']?$_REQUEST['email_priority']:'';
    
    if(isset($_REQUEST["email_processing_id"])){
    $email_processing_id = $_REQUEST["email_processing_id"];
    
    $test=DB::select("select * from cdp_email_processing where email_processing_id='$email_processing_id'");
    if($test)
    {
    
    $download=DB::insert("insert into cdp_email_processing (
                                                           email_from, 
                                                           email_to, 
                                                           email_cc,
                                                           email_subject, 
                                                           email_text,  
                                                           email_send_date,
                                                           lastupdated_by,
                                                           lastupdated_date,
                                                           email_send_status,
                                                           email_send_status_Response,
                                                           email_priority) 
                                                    VALUES (
                                                    '$email_from',' $email_to',
                                                    ' $email_cc','$email_subject',
                                                    ' $email_text','$email_send_date',
                                                    '$lastupdated_by',
                                                    '$lastupdated_date',
                                                    ' $email_send_status',
                                                    '$email_send_status_Response',
                                                    '$email_priority')");
                                                    if($download){
                                                        $notemess['msg']='successfully insert';
                                                        }
                                                        else{
                                                        $notemess['msg']='error';
                                                        }
                                                    
                                        }       else{
                                                    $notemess['msg']='something went wrong';
                                    
                                                }
    
     
                                                }else{
    
                                                    $notemess['msg']='user id does not exist';
                                                    
                                                    
                                                    }
                                                    
                                                    
                                                    
                                                   
    
    
    return json_encode($notemess);
    }





    public function reportorders()
    {
        $reportarray=array();
        if(isset($_REQUEST["user_id"]))
        {
         $user = $_REQUEST["user_id"];
         $orderreport= DB::select("select cotm.  * ,cdp_user.email from cdp_order_transaction_master as cotm 
         inner join cdp_order_transaction_detail as cotd on cotd.transaction_id=cotm.transaction_id 
         inner join cdp_user on cotm.user_id=cdp_user.id 
         where user_id='$user'");
         if($orderreport)
         {
            }else
            {
             $reportarray='user id is not match';
             return json_encode($reportarray);
             }
             return json_encode($orderreport);             
             }
             else
             {
                $reportarray['msg']='something is going worng user_id is missing';
             } 
             return json_encode($reportarray);
             }



            public function messagetemplate()
            {
                $orderdata=array();
                $orderdatas=array();
              
                $temp=DB::select("select *from cdp_email_template_mst where status=1");
             
                foreach($temp as $message){

                    $orderdata['email_template_code']=$message->email_template_code;
                    $orderdata['email_template_description']=$message->email_template_description;
                    $orderdata['type']=$message->type;
                    $orderdata['status']=$message->status;
                    $orderdata['email_subject']=$message->email_subject;
                    $orderdata['email_text']=$message->email_text;
                    $orderdata['created_date']=$message->created_date;  
                    array_push($orderdatas,$orderdata);
               
            }
                     return json_encode($orderdatas);
                     array_push($orderdatas,$orderdata);
                     throw new Exception("check value of status");
                     try {
                        checkNum(2);
                       
                        echo 'the value must be 1';
                      }
                      
                     
                      catch(Exception $e) {
                        echo 'Message: ' .$e->getMessage();
                      }
            }

          
          public function getemail()
         {
             $emailmess=array();
             $type = $_REQUEST['type']?$_REQUEST['type']:'';
             $email_template_code=$_REQUEST['email_template_code']?$_REQUEST['email_template_code']:'';
             $email_subject=$_REQUEST['email_subject']?$_REQUEST['email_subject']:'';
             $email_text=$_REQUEST['email_text']?$_REQUEST['email_text']:'';
             $status=$_REQUEST['status']?$_REQUEST['status']:'';
             $created_by=$_REQUEST['created_by']?$_REQUEST['created_by']:'';
             
             $download=DB::insert("insert into cdp_email_template_mst(type,email_template_code,email_subject,email_text,status,created_by) 
                VALUES ('$type','$email_template_code','$email_subject','$email_text','$status','$created_by')");
                if($download)
                {
                    $notemess['msg']='successfully insert';
                }
                    else
                    {
                    $notemess['msg']='error';
                    }
                
              
        
   
 
    return json_encode($notemess);
   
}


public function deletemail()
{
    $emailmess=array();
    
    $email_template_code=$_REQUEST['email_template_code']?$_REQUEST['email_template_code']:'';
    
    $created_by=$_REQUEST['created_by']?$_REQUEST['created_by']:'';
    
   $download=DB::delete("delete from cdp_email_template_mst where email_template_code='$email_template_code' and created_by='$created_by'");
       
   
      
       if($download)
       {
           $notemess['msg']='successfully delete';
       }
           else
           {
           $notemess['msg']='already deleted';
           }
       
     



return json_encode($notemess);
        }   





        public function updateflag()
        {

        $flagcode = $_REQUEST['flagcode'];
        
        $array = array();
        if (!empty($_REQUEST['flagcode'])) {
            $update=DB::update("update cdp_flags set status='1'where flagcode='$flagcode' ");
               
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
     







    public function addreferralurl()
    {
        $notemess=array();
        $institution_id=$_REQUEST['institution_id']?$_REQUEST['institution_id']:'';
        $referer_host = $_REQUEST['referer_host']?$_REQUEST['referer_host']:'';
        $description=$_REQUEST['description']?$_REQUEST['description']:'';
        $valid_from=$_REQUEST['valid_from']?$_REQUEST['valid_from']:'';
        $valid_to=$_REQUEST['valid_to']?$_REQUEST['valid_to']:'';
        $manage=DB::insert("insert into cdp_institute_http_referer(institution_id,referer_host,description,valid_from,valid_to) 
           VALUES ('$institution_id','$referer_host','$description','$valid_from','$valid_to')");
           if($manage)
           {
               $notemess['msg']='successfully insert';
           }
               else
               {
               $notemess['msg']='error';
               }    
    return json_encode($notemess); 
} 
public function deletereferralurl()
{
    $notemess=array();
    $institution_id=$_REQUEST['institution_id']?$_REQUEST['institution_id']:'';
    $http_referer_id=$_REQUEST['http_referer_id']?$_REQUEST['http_referer_id']:'';
       $download=DB::delete("delete from cdp_institute_http_referer where institution_id='$institution_id'and http_referer_id='$http_referer_id'");
       if($download)
       {
           $notemess['msg']='successfully delete';
       }
           else
           {
           $notemess['msg']='already deleted ';
        } 
        return json_encode($notemess);      
}


public function refererlist(){
    $dropdownarray=array();
    $dropdownarray1=array();
    
    if(isset($_REQUEST["institution_id"])){
    $institution_id = $_REQUEST["institution_id"];
    $institution_id=$_REQUEST['institution_id']?$_REQUEST['institution_id']:'';
    $droplist=DB::select("select *from cdp_institute_http_referer where institution_id='$institution_id'");
    if($droplist){
        foreach($droplist as $dropdownlist1){
            $dropdownarray['institution_id']=$dropdownlist1->institution_id;
            $dropdownarray['description']=$dropdownlist1->description;
            $dropdownarray['http_referer_id']=$dropdownlist1->http_referer_id;
            $dropdownarray['referer_host']=$dropdownlist1->referer_host;
            array_push($dropdownarray1,$dropdownarray);
}
    }else{
            $dropdownarray1['msg']='Empty Result';
    }  
    
    }else{

        $dropdownarray1['msg']='institution id does not exist';
        
        
        }
               
                return json_encode($dropdownarray1);
} 
public function accesstoken(){
    $dropdownarray=array();
    $dropdownarray1=array();
   
    if(isset($_REQUEST["ins_id"])){
    $ins_id = $_REQUEST["ins_id"];
   
    $droplist=DB::select("select *from cdp_ins_pregenkey where ins_id='$ins_id' and status=1");
    if($droplist){
        foreach($droplist as $dropdownlist1){
            $dropdownarray['ins_id']=$dropdownlist1->ins_id;
            $dropdownarray['pregenkey']=$dropdownlist1->pregenkey;
            $dropdownarray['id']=$dropdownlist1->id;
            $dropdownarray['expiry_date']=$dropdownlist1->expiry_date;
            $dropdownarray['status']=$dropdownlist1->status;
            array_push($dropdownarray1,$dropdownarray);
}
    }else{
            $dropdownarray1['msg']='Empty Result';
    }  
    
    }else{
        $dropdownarray1['msg']='ins id does not exist';
        
        
        }  
                return json_encode($dropdownarray1);
} 

public function emailfatch()
{
       $reportarray=array();
       $emailreport=array();
       
         $emailreport=DB::select("select cotm.id,cotd.user_id,cotm.given_name,cotm.surname,cotm.email from cdp_inst_token_customer as cotm 
         inner join cdp_inst_user_token as cotd on cotd.user_id=cotm.email");
         if($emailreport)
         {
            }else
            {
             $reportarray='error';
             return json_encode($reportarray);
             }
             return json_encode($emailreport);             
            
             return json_encode($reportarray);
             }

public function emailhistory()
{
    $emailreport=array();
    $emailreports=array();
   
    $ins_id = $_REQUEST["ins_id"];
    $emailreport=DB::select("select prg.id,prg.created_by,cith.reason_to_remove,ciut.last_login,cith.token_assign_date,prg.pregenkey,prg.status,prg.lastupdated_by,prg.lastupdated_date,prg.expiry_date
    from cdp_ins_pregenkey as prg
    left join cdp_inst_user_token as ciut on prg.id=ciut.token_id
    left join cdp_inst_token_history as cith on prg.id=cith.token_id
    where prg.ins_id=$ins_id and prg.status in(1,0) order by prg.id desc");
    if($emailreport)
    {
           }else
    {
        $emailreports['msg']='error';
        return json_encode($emailreports);
    }

       
       return json_encode($emailreport);
    }



    
       public function emailstatus()
      {
        $emailreport=array();
        $reportarray=array();
        $status=$_REQUEST["status"];
        $created_by=$_REQUEST["created_by"];
        $created_date=$_REQUEST["created_date"];
        if(isset($_REQUEST["id"])){
        $id = $_REQUEST["id"];
        $update=DB::update("update cdp_ins_pregenkey set status='$status' where id='$id' ");
        if($update)
            {   
            $reportarray['msg']='successful update';  
        }else
        {
            $reportarray['msg']='error';
            return json_encode($reportarray);
        }
    }else{
        $reportarray['msg']='id does not exist';
        
        return json_encode($emailreport);
        } 
           return json_encode($reportarray);
        } 



         public function updatehistory($emailreport)
         {
         $emailinst=DB::insert("insert into cdp_inst_token_history(id,user_id, token_id,created_by,created_date, lastupdated_by,lastupdated_date,device_id,last_login) 
         VALUES ('$id','$user_id',' $token_id','$created_by','$created_date',' $lastupdated_by','$lastupdated_date','$device_id','$last_login')");

         }
        public function emaildetails()
       {
        $emailreport=array();
        $emailreports=array();
   
        $email=$_REQUEST["user_id"];
    
        $emailreport=DB::select("select *from cdp_inst_user_token where user_id='$email'");
        $emailinst=$emailreport;
        if($emailinst)
        {
        $this->updatehistory($emailreport);
        
        }else
        {
        $emailreports['msg']='error';
        return json_encode($emailinst);
       }
       return json_encode($emailinst);
    }
}

     
    
