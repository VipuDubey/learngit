<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class EmailController extends Controller
{     
public function emailtext()
{
    $emailarray=array();
    
    if(isset($_REQUEST["email_template_code"])){

$temp=$_REQUEST["email_template_code"]; 

$droplist=DB::select("select email_text,email_subject from cdp_email_template_mst where email_template_code='$temp'");

if($droplist){
}else{
    $emailarray='user id is missing';
    
}          
         return json_encode($droplist);
           
}


else{
    $emailarray['msg']='something is going worng email_template_code is missing';
}
return json_encode($emailarray);
}    
 

public function emaildropdown(){
    $dropdownarray=array();
    $dropdownarray1=array();
    
    $droplist=DB::select("select *from `cdp_email_template_mst` where status=1 AND type='MAIL'");
 
    if($droplist){
        foreach($droplist as $dropdownlist1){
            $dropdownarray['email_template_code']=$dropdownlist1->email_template_code;
            array_push($dropdownarray1,$dropdownarray);
}
    }else{
            $dropdownarray1['msg']='Empty Result';
    }          
                return json_encode($dropdownarray1);
}

public function downlodkey(){
    $user = $_REQUEST["user_id"];
    //SELECT CURRENT_TIMESTAMP();
  $update=DB::update("update cdp_user_download set  download_count=1 and user_download_status= 1 where user_id='$user'");
print_r($update);
exit();
    
     $download=DB::insert("insert into cdp_user_download (user_id, download_key, created_by,)
    value ('thedoc2', 'vipul1', 'vipuldubey')");
    return json_encode($download);

   
} 
    public function getofflinkey(){
    $offlinearray=array();
    $offlinearray1=array();
    if(isset($_REQUEST["user_id"])){
    $user = $_REQUEST["user_id"];
    $test=DB::select("select *from cdp_cdoffline_download where user_download_status=1 and user_id='$user'");
    if($test){
        foreach($test as $dropdownlist1){
            $offlinearray['user_id']=$dropdownlist1->user_id;
            $offlinearray['user_download_id']=$dropdownlist1->user_download_id;
            $offlinearray['download_count']=$dropdownlist1->download_count;
            $offlinearray['download_key']=$dropdownlist1->download_key;
            $offlinearray['user_download_status']=$dropdownlist1->user_download_status;
            $offlinearray['expiry_date']=$dropdownlist1->expiry_date;
            $offlinearray['created_by']=$dropdownlist1->created_by;
            $offlinearray['created_date']=$dropdownlist1->created_date;
            $offlinearray['lastupdated_by']=$dropdownlist1->lastupdated_by;
            $offlinearray['lastupdated_date']=$dropdownlist1->lastupdated_date;
            $offlinearray['email']=$dropdownlist1->email;
            $offlinearray['isMigrated']=$dropdownlist1->isMigrated;
            array_push($offlinearray1,$offlinearray);
        }
    }else{
        $offlinearray1['msg']='user id is not match';
    }          
                return json_encode($offlinearray1);
}

else{
    $offlinearray1['msg']='something is going worng user id is missing';
}
return json_encode($offlinearray1);
}    
 
public function emailnotification()
{
    $emailarray=array();
    if(isset($_REQUEST["email_template_code"])){   
$temp=$_REQUEST["email_template_code"]; 

$list=DB::select("select type, email_from, email_subject, email_text, status from cdp_email_template_mst where email_template_code='$temp'");
if($list){
}else{
    $emailarray='user id is missing';
    return json_encode($emailarray);
}          
            return json_encode($list);
           
}


else{
    $emailarray['msg']='something is going worng email_template_code is missing';
}
return json_encode($emailarray);
} 

public function roledropdown(){
    $dropdownarray=array();
    $dropdownarray1=array();
    $droplist=DB::select("select *from cdp_role where ID NOT IN (1,3,5,7)");
    if($droplist){
        foreach($droplist as $dropdownlist1){
            $dropdownarray['id']=$dropdownlist1->id;
            $dropdownarray['description']=$dropdownlist1->description;
            $dropdownarray['name']=$dropdownlist1->name;
            array_push($dropdownarray1,$dropdownarray);
}
    }else{
            $dropdownarray1['msg']='Empty Result';
    }          
                return json_encode($dropdownarray1);
}


public function getoccupation(){
    $occupationarray=array();
    $occupationarray1=array();
    
    $test=DB::select("select *from cdp_occupation_mst ");
    if($test){
        foreach($test as $occupationlist1){
            $occupationarray['occupation']=$occupationlist1->occupation;
            
            array_push($occupationarray1,$occupationarray);
        }
    }else{
        $occupationarray1['msg']='empty result';
    }          
return json_encode($occupationarray1);
} 

public function getimage()
{
    $notemess=array();
    $institutionid = $_REQUEST["institution_id"];
    if(!empty( $institutionid)){
      
        $update=DB::update("update cdp_institution_details set logo='?'where institution_id='$institutionid' ");
        if($update){
        $notemess['msg']='successfully updated';
        }
        else{
        $notemess['msg']='already updated';
        }}
        else{
        
        $notemess['msg']='user id is missing';
        
        }
        return json_encode($notemess);
 }
 
 
 public function notificationupdate(){
    $notemess=array();
    $notificationid = $_REQUEST["notification_id"];
    if(!empty($notificationid)){
    $test=DB::select("select * from cdp_user_notification where notification_id='$notificationid'");
    if($test)
    {
    $update=DB::update("update cdp_user_notification set status='2'where notification_id='$notificationid' ");
    if($update){
    $notemess['msg']='successfully updated';
    }
    else{
    $notemess['msg']='already updated';
    }
    
    }else{
    
    $notemess['msg']='user id does not exist';
    
    
    }
    
    }else{
    
    $notemess['msg']='user id is missing';
    
        }
        return json_encode($notemess);
        }


   public function getnotification()
   {

    $notemess=array();

      
        $notification_type = $_REQUEST['notification_type']?$_REQUEST['notification_type']:'';
        $send_from= $_REQUEST['send_from']?$_REQUEST['send_from']:'';
        $send_to=$_REQUEST['send_to']?$_REQUEST['send_to']:'';
        $send_cc=$_REQUEST['send_cc']?$_REQUEST['send_cc']:'';
        $notification_subject=$_REQUEST['notification_subject']?$_REQUEST['notification_subject']:'';
        $notification_text=$_REQUEST['notification_text']?$_REQUEST['notification_text']:'';
        $highlight_message=$_REQUEST['highlight_message']?$_REQUEST['highlight_message']:'0';
        $template_id_used=$_REQUEST['template_id_used']?$_REQUEST['template_id_used']:'';
        $file_attach=$_REQUEST['file_attach']?$_REQUEST['file_attach']:'';
        $send_date=date('Y-m-d', strtotime($_REQUEST['send_date']));
        $lastupdated_by=$_REQUEST['lastupdated_by']?$_REQUEST['lastupdated_by']:'';
        $lastupdated_date=date('Y-m-d', strtotime($_REQUEST['lastupdated_date']));
        $status=$_REQUEST['status']?$_REQUEST['status']:'';
      
        if($notification_type&&$send_from&&$send_to){
                
            


                $download=DB::insert("insert into cdp_user_notification (notification_type,send_from, send_to, send_cc,notification_subject, notification_text, highlight_message, template_id_used,file_attach, send_date,lastupdated_by,lastupdated_date,status) 
                VALUES ('$notification_type','$send_from',' $send_to',' $send_cc','$notification_subject',' $notification_text','$highlight_message','$template_id_used','$file_attach','$send_date','$lastupdated_by','$lastupdated_date',' $status')");
                
                
                
                if($download){
                    $notemess['msg']='successfully insert';
                    }
                    else{
                    $notemess['msg']='error';
                    }
                
    }       else{
                $notemess['msg']='something went wrong';

            }
        
   
 
    return json_encode($notemess);



}

public function purchaseddropdown(){
    $dropdownarray=array();
    $dropdownarray1=array();
    $droplist=DB::select("select *from cdp_role where ID NOT IN (1,3,5,7)");
    if($droplist){
        foreach($droplist as $dropdownlist1){
            $dropdownarray['id']=$dropdownlist1->id;
            $dropdownarray['description']=$dropdownlist1->description;
            $dropdownarray['name']=$dropdownlist1->name;
            array_push($dropdownarray1,$dropdownarray);
}
    }else{
            $dropdownarray1['msg']='Empty Result';
    }          
                return json_encode($dropdownarray1);
}
public function dropCountry(){

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