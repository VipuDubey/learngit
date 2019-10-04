
public function emailfatch()
{
    $emailarray=array();
    
    $emailreport= DB::select("select cotm.email,cotd.user_id  from cdp_inst_token_customer as cotm 
    inner join cdp_inst_user_token as cotd on cotd.user_id=cotm.email"); 
    
     if($orderreport)
     {
        }else
        {
         $emailarray='error';
         return json_encode($emailarray);
        }
         return json_encode($emailreport);             
        
        }