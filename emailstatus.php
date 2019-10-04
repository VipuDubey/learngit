public function emailstatus()
    {
        $emailreport=array();
        $reportarray=array();
        $status=$_REQUEST["status"];
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