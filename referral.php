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
   $download=DB::delete("delete from cdp_institute_http_referer where institution_id='$institution_id'");
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