<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{   
    public function searchorders()
    {
        $orderarray = array();
        $orderarray1 = array();
        $appendsql = '';
        if (!empty($_REQUEST['email'])) {
            $email=$_REQUEST['email'];
            $appendsql .=  "and  `cdp_user`.`email`='$email'";
        }
        if (!empty($_REQUEST['ordernumber'])) {
            $ordernumber=$_REQUEST['ordernumber'];
            $appendsql .=  "and  `cdp_order_transaction_master`.`ordernumber`='$ordernumber'";
        }

        if (!empty($_REQUEST['invoicenumber'])) {
            $invoicenumber=$_REQUEST['invoicenumber'];
            $appendsql .=  "and  `cdp_myob_order_transaction`.`myob_invoice_no`='$invoicenumber'";
        }
        if ($_REQUEST['status']=='processed') {
            $appendsql .= "and `cdp_order_transaction_master`.`handled`=2";
        }
        if ($_REQUEST['status']=='pending'){
            $appendsql .=  "and  `cdp_order_transaction_master`.`handled`='0'";
        }

        if ($_REQUEST['status']=='failed') {
            $appendsql .=  "and  `cdp_order_transaction_master`.`handled`=-1";
        }

        if ($_REQUEST['status']=='completed') {
            $appendsql .=  "and  `cdp_order_transaction_master`.`handled`=1";
        }
        if(empty($_REQUEST['status'])){
            $appendsql .=  "and  `cdp_order_transaction_master`.`handled`=0";
        }
        $Orders = DB::select("select *,
                                Group_Concat(DISTINCT cdp_order_transaction_detail.coupon_referrer_code) AS coupon_referrer_code,
                                GROUP_CONCAT(cdp_order_transaction_detail.subscription_id) AS subscription_id,
                                GROUP_CONCAT(cdp_order_transaction_detail.price) AS trans_price,
                                GROUP_CONCAT(cdp_order_transaction_detail.quantity) AS prod_quantity,
                                Group_Concat(DISTINCT cdp_subscription_product.id) AS sub_id,
                                Group_Concat(DISTINCT cdp_subscription.subscription_category_id) AS subsmcat_id,
                                GROUP_CONCAT(cdp_product.product_id) AS prod_id,
                                GROUP_CONCAT(cdp_product.product_title) AS product_title,
                                `cdp_user`.`id`,
                                `cdp_user`.`businessname`,
                                `cdp_user`.`given_name`,
                                `cdp_user`.`surname`,
                                `cdp_user`.`street_address`,
                                `cdp_user`.`phone_number`,
                                `cdp_user`.`email`,
                                `cdp_user`.`postal_code`,
                                `cdp_user`.`country`,
                                `cdp_user`.`suburb`,
                                `cdp_user`.`state`,
                                `cdp_user`.`username`,
                                `cdp_user`.`occupation`,
                                `cdp_user`.`customer_type`
                                from `cdp_order_transaction_master`
                                join `cdp_order_transaction_detail`
                                ON `cdp_order_transaction_master`.`transaction_id`=`cdp_order_transaction_detail`.`transaction_id`
                                inner join `cdp_subscription_product`
                                 ON `cdp_order_transaction_detail`.`subscription_id`=`cdp_subscription_product`.`subscription_id`
                                inner join `cdp_subscription`
                                 ON `cdp_subscription`.`subscription_id`=`cdp_order_transaction_detail`.`subscription_id`
                                 inner join `cdp_product`
                                  ON `cdp_product`.`product_id`=`cdp_subscription_product`.`product_id`
                                  inner join  `cdp_user`
                                  ON `cdp_user`.`id`=`cdp_order_transaction_master`.`user_id`
                                  inner join `cdp_myob_order_transaction`
                                  ON `cdp_myob_order_transaction`.`transaction_id`=   `cdp_order_transaction_detail`.`transaction_id`
                                  where 1 $appendsql order by `cdp_order_transaction_master`.`order_date` desc
                                  ");

                                  foreach( $Orders as  $Ordersget){

                                        $order['order_date']=$Ordersget->order_date;
                                        $order['order_number']=$Ordersget->order_number;
                                        $order['given_name']=$Ordersget->given_name;
                                        $order['email']=$Ordersget->email;
                                        $order['created_by']=$Ordersget->created_by;
                                        $order['transaction_id']=$Ordersget->transaction_id;
                                        $order['user_id']=$Ordersget->user_id;
                                        $order['surname']=$Ordersget->surname;
                                        $order['businessname']=$Ordersget->businessname;
                                        $order['street_address']=$Ordersget->street_address;
                                        $order['postal_code']=$Ordersget->postal_code;
                                        $order['state']=$Ordersget->state;
                                        $order['handled']=$Ordersget->handled;
                                       
                                               array_push($orderarray1,$order); 
                                  }
                                    return json_encode($orderarray1);
                                }


    public function getorder()
 {

 $orderdata=array();
 $orderdatas=array();
$user = $_REQUEST["user_id"];
$Order = DB::select("select *from cdp_user_notes where user_id='$user'" );
if($Order){
foreach($Order as $Ord){ 
$orderdata['note_id']=$Ord->note_id;
$orderdata['user_id']=$Ord->user_id;
$orderdata['note_subject']=$Ord->note_subject;
$orderdata['notes_level']=$Ord->notes_level;
$orderdata['note_text']=$Ord->note_text;
$orderdata['created_by']=$Ord->created_by;
$orderdata['created_date']=$Ord->created_date;
$orderdata['updated_by']=$Ord->updated_by;
$orderdata['updated_date']=$Ord->updated_date;

array_push($orderdatas,$orderdata);
}
}else{
$orderdatas['msg']='empty result';
}
return json_encode($orderdatas);
 }

 public function getupdate(){
    $notemess=array();
    $user = $_REQUEST["user_id"];
    if(!empty( $user)){
    $test=DB::select("select * from cdp_user_notes where user_id='$user'");
    if($test)
    {
    $update=DB::update("update cdp_user_notes set status='2'where user_id='$user' ");
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
    
   $notemess['msg']=array();
    
    }
    return json_encode($notemess);
    
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




public function emailtext()
{
$temp=$_REQUEST["email_template_code"];
if(isset($temp)&& empty($temp)){ 
$droplist=DB::select("select email_text from cdp_email_template_mst where email_template_code='$temp'");

if($droplist){
    echo 'template_code find';
}
else{
echo'template_code not find';
}
echo'<br/>';

}else{

echo 'not'; 

}
return json_encode($droplist); 
}
}