<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class CustomerController extends Controller
{

    public function simpleSearch(Request $request){ 
        ini_set('display_errors', 1);
        $appendSql = '';
        $resultarray = array();                 
        $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : '';
        $cust_id = (isset($_REQUEST['cust_id'])) ? $_REQUEST['cust_id'] : '';
        $given_name = (isset($_REQUEST['given_name'])) ? $_REQUEST['given_name'] : '';
        $searchemail = (isset($_REQUEST['searchemail'])) ? $_REQUEST['searchemail'] : '';
        $search_phone_number = (isset($_REQUEST['search_phone_number'])) ? $_REQUEST['search_phone_number'] : '';
        $order_number = (isset($_REQUEST['order_number'])) ? $_REQUEST['order_number'] : '';
        $transaction_id = (isset($_REQUEST['transaction_id'])) ? $_REQUEST['transaction_id'] : '';
        $search_status = (isset($_REQUEST['search_status'])) ? $_REQUEST['search_status'] : '';
        $search_logged_status = (isset($_REQUEST['search_logged_status'])) ? $_REQUEST['search_logged_status'] : '';
        $search_businessname = (isset($_REQUEST['search_businessname'])) ? $_REQUEST['search_businessname'] : '';
        $subscription_categoryid = (isset($_REQUEST['subscription_categoryid'])) ? $_REQUEST['subscription_categoryid'] : '';
        $subscriptionsid = (isset($_REQUEST['subscriptionsid'])) ? $_REQUEST['subscriptionsid'] : '';
        $subscription_expiry = (isset($_REQUEST['subscription_expiry'])) ? $_REQUEST['subscription_expiry'] : '';
        $sortby = (isset($_REQUEST['sortby'])) ? $_REQUEST['sortby'] : '';

        if($search){                               
            $strlen = strlen($search);
            if($strlen >= 3){
                $appendSql .= "  and `cdp_user`.`account_status` = 1 ";
                $appendSql .= "  and `cdp_user_role`.`role_id` IN (3,6) ";                
                $appendSql .= "  and `cdp_user`.`id` like '%" . $search . "%' or `cdp_user`.`given_name` like '%" . $search . "%' or `cdp_user`.`surname` like '%" . $search . "%' or `cdp_user`.`email` like '%" . $search . "%' or `cdp_user`.`businessname` like '%" . $search . "%'
                                or `cdp_user`.`hospitalname` like '%" . $search . "%' or `cdp_user`.`street_address` like '%" . $search . "%'
                                or `cdp_user`.`country` like '%" . $search . "%' or `cdp_user`.`phone_number` like '%" . $search . "%'
                                or `cdp_user`.`occupation` like '%" . $search . "%' ";
                $searchResult = DB::select("select `cdp_user`.`id`,
					`cdp_user`.`customer_type`,
					`cdp_user`.`businessname`,
					`cdp_user`.`hospitalname`,
					`cdp_user`.`title`,
                    `cdp_user`.`given_name`,
                    `cdp_user`.`parent_user`,
                    `cdp_user`.`phone_number`,
                     `cdp_user`.`email`,                         
                     `cdp_user`.`street_address`,
                     `cdp_user`.`suburb`,
                     `cdp_user`.`state`,
                     `cdp_user`.`postal_code`,
                     `cdp_user`.`country`,
                     `cdp_user`.`occupation`,                         
                     `cdp_user`.`surname` ,
                     `cdp_user`.`account_status` ,
					 `cdp_user`.`account_locked` ,
                     `cdp_user_role`.`role_id`
                     from `cdp_user` LEFT JOIN `cdp_user_role`
                      on `cdp_user_role`.`user_id`=`cdp_user`.`id`                         
                       where 1  $appendSql");
                              
                if($searchResult){ 
                    foreach ($searchResult as $sresult) {
                           $resultarr['id'] = $sresult->id;
						   $resultarr['customer_type'] = $sresult->customer_type;
						   $resultarr['businessname'] = $sresult->businessname;
						   $resultarr['hospitalname'] = $sresult->hospitalname;
						   $resultarr['title'] = $sresult->title;
                           $resultarr['given_name'] = $sresult->given_name;
                           $resultarr['parent_user'] = $sresult->parent_user;
                           $resultarr['phone_number'] = $sresult->phone_number;
                           $resultarr['email'] = $sresult->email;
                           $resultarr['street_address'] = $sresult->street_address;
                           $resultarr['suburb'] = $sresult->suburb;
                           $resultarr['state'] = $sresult->state;
                           $resultarr['postal_code'] = $sresult->postal_code;
                           $resultarr['country'] = $sresult->country;
                           $resultarr['occupation'] = $sresult->occupation;
                           $resultarr['surname'] = $sresult->surname;
                           $resultarr['account_status'] = $sresult->account_status;
						   $resultarr['account_locked'] = $sresult->account_locked;
                           $resultarr['role_id'] = $sresult->role_id;                                                             
                           array_push($resultarray, $resultarr);
                    }                       
                    
                    
                } else{
                    $resultarray['msg'] = 'No data found.';
                    $resultarray['code'] = '400';                        
                }                  
                            
            } else{
                $resultarray['msg'] = 'Please enter atleast 3 character.';
                $resultarray['code'] = '400';                    
            }
            return json_encode($resultarray);
        }
			else{
            $appendSql .= "  and `cdp_user`.`account_status` = 1 ";
            $appendSql .= "  and `cdp_user_role`.`role_id` IN (3,6) ";                
            if($cust_id){ 
                $appendSql .= "  and (`cdp_user`.`id` LIKE '%" . $cust_id . "%' )";  
            }
            if($given_name){ 
                $appendSql .= "  and (`cdp_user`.`given_name` LIKE '%" . $given_name. "%')"; 
            }
            if($searchemail){ 
                $appendSql .= "  and (`cdp_user`.`email` LIKE '%" . $searchemail. "%')";
            }
            if($search_phone_number){ 
                $appendSql .= "  and (`cdp_user`.`phone_number` LIKE '%" . $search_phone_number. "%')";                    
            }
            if($order_number){
                //$appendSql .= "  and (`cdp_user_subscription`.`order_txn_number` LIKE '%".$order_number."%')";                    
            } 
            if($transaction_id){
                //$appendSql .= "  and (`cdp_user_subscription`.`subscription_id` LIKE '%$transaction_id%')";  
            }
            if($search_status){ 
                $appendSql .= "  and (`cdp_user`.`account_status` LIKE '%" .$search_status. "%')";                    
            }
            if($search_logged_status){ 
                $appendSql .= "  and (`cdp_user`.`account_locked` LIKE '%" .$search_logged_status. "%')";                   
            }
            if($search_businessname){ 
                $appendSql .= "  and (`cdp_user`.`businessname` LIKE '%" .$search_businessname. "%')";                     
            }
            if($subscription_categoryid && $subscriptionsid){ 
                    
                $user_subscription = DB::table('cdp_user_subscription')->select('cdp_user_subscription.user_id')->where('user_subscription_id', '=', $subscriptionsid)->get();
                foreach($user_subscription as $usubscription){                                
                    $appendSql .= "  or ( `cdp_user`.`id` = '".$usubscription->user_id."')";
                }
                    
                
                                   
            } else if($subscription_categoryid){ 
                $query1 = DB::table('cdp_subscription')->select('cdp_subscription.subscription_id')->where('subscription_category_id', 'LIKE', '%'.$subscription_categoryid.'%');
                $countquery1 = $query1; 
                $countresult = $countquery1->count();
                //echo $countresult;die();
                if($countresult > 0){
                    $subscription_id = $query1->get();
                    foreach($subscription_id as $subid){
                        $subscription_id = $subid->subscription_id;
                        $user_subscription = DB::table('cdp_user_subscription')->select('cdp_user_subscription.user_id')->where('user_subscription_id', '=', $subscription_id)->get();
                        foreach($user_subscription as $usubscription){                                
                            $appendSql .= "  or ( `cdp_user`.`id` = '".$usubscription->user_id."')";
                        }
                    }
                    
                } 
                                   
            } 
            if($subscription_expiry){ 
                //$appendSql .= "  and (`cdp_user_subscription`.`expiry_date` LIKE '%$subscription_expiry%')";                     
            }
            if($sortby){    
                $appendSql .= "  and (`cdp_user`.`given_name` LIKE '%".$sortby."%')";                   
            }

            $searchResult = DB::select("select `cdp_user`.`id`,
				`cdp_user`.`customer_type`,
				`cdp_user`.`businessname`,
				`cdp_user`.`hospitalname`,
				`cdp_user`.`title`,
                `cdp_user`.`given_name`,
                `cdp_user`.`parent_user`,
                `cdp_user`.`phone_number`,
                 `cdp_user`.`email`,                         
                 `cdp_user`.`street_address`,
                 `cdp_user`.`suburb`,
                 `cdp_user`.`state`,
                 `cdp_user`.`postal_code`,
                 `cdp_user`.`country`,
                 `cdp_user`.`occupation`,                         
                 `cdp_user`.`surname` ,
                 `cdp_user`.`account_status` ,
				 `cdp_user`.`account_locked` ,
                 `cdp_user_role`.`role_id`
                 from `cdp_user` LEFT JOIN `cdp_user_role`
                  on `cdp_user_role`.`user_id`=`cdp_user`.`id`                         
                   where 1  $appendSql");
                             
            if($searchResult){ 
                foreach ($searchResult as $sresult) {
                       $resultarr['id'] = $sresult->id;
					   $resultarr['customer_type'] = $sresult->customer_type;
                       $resultarr['businessname'] = $sresult->businessname;
                       $resultarr['hospitalname'] = $sresult->hospitalname;
                       $resultarr['title'] = $sresult->title;
                       $resultarr['given_name'] = $sresult->given_name;
                       $resultarr['parent_user'] = $sresult->parent_user;
                       $resultarr['phone_number'] = $sresult->phone_number;
                       $resultarr['email'] = $sresult->email;
                       $resultarr['street_address'] = $sresult->street_address;
                       $resultarr['suburb'] = $sresult->suburb;
                       $resultarr['state'] = $sresult->state;
                       $resultarr['postal_code'] = $sresult->postal_code;
                       $resultarr['country'] = $sresult->country;
                       $resultarr['occupation'] = $sresult->occupation;
                       $resultarr['surname'] = $sresult->surname;
                       $resultarr['account_status'] = $sresult->account_status;
					   $resultarr['account_locked'] = $sresult->account_locked;
                       $resultarr['role_id'] = $sresult->role_id;                                                             
                       array_push($resultarray, $resultarr);
                }                       
                
                
            } else{
                $resultarray['msg'] = 'No data found.';
                $resultarray['code'] = '400';                        
            } 
            return json_encode($resultarray);
        }
    } 
	public function customerAdd(){
       
        $customer_type = (isset($_REQUEST['customer_type'])) ? $_REQUEST['customer_type'] : '';
        $business_name = (isset($_REQUEST['business_name'])) ? $_REQUEST['business_name'] : '';
        $hospital_name = (isset($_REQUEST['hospital_name'])) ? $_REQUEST['hospital_name'] : '';
        $occupation = (isset($_REQUEST['occupation'])) ? $_REQUEST['occupation'] : '';        
        $title = (isset($_REQUEST['title'])) ? $_REQUEST['title'] : '';
        $given_name = (isset($_REQUEST['given_name'])) ? $_REQUEST['given_name'] : '';
        $surname = (isset($_REQUEST['surname'])) ? $_REQUEST['surname'] : '';
        $state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
        $country = (isset($_REQUEST['country'])) ? $_REQUEST['country'] : '';
        $flag = (isset($_REQUEST['flag'])) ? $_REQUEST['flag'] : '';
        $address = (isset($_REQUEST['address'])) ? $_REQUEST['address'] : '';
        $subrub = (isset($_REQUEST['subrub'])) ? $_REQUEST['subrub'] : '';
        $postal_code = (isset($_REQUEST['postal_code'])) ? $_REQUEST['postal_code'] : '';
        $phone_number = (isset($_REQUEST['phone_number'])) ? $_REQUEST['phone_number'] : '';
        $email = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
        $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : ''; 

        if($customer_type&&$title&&$given_name&&$surname&&$email&&$occupation&&$address&&$subrub&&$state&&$postal_code&&$phone_number&&$country&&$user_id){
            $check_email = $this->checkDuplicateEmail ( $email );
            if($check_email){
                $resultarray['msg'] = 'Email already exists.';
                $resultarray['code'] = '400';
                return json_encode($resultarray);
            }
            $insertData = array (                                
                'customer_type' => $customer_type,                
                'occupation' => $occupation,
                'title' => $title,
                'given_name' => $given_name,
                'surname' => $surname,
                'state' => $state,
                'country' => $country,                 
                'street_address' => $address,
                'suburb' => $subrub,                
                'postal_code' => $postal_code,
                'phone_number' => $phone_number,
                'email' => $email,                
                'username' => $email,                
                'version' => '0' 
            );
            if($business_name){
                $insertData['businessname'] = $business_name;
            } 
            if($hospital_name){
                $insertData['hospitalname'] = $hospital_name;
            } 
            if($flag){
                $insertData['flags'] = $flag;
            }
            $insertData ['created_by'] = $user_id;
            $insertData ['created_date'] = date ( "Y-m-d H:i:s" );

            $userid = strtolower ( substr ( (substr ( trim ( $given_name ), 0, 1 )) . trim ( $surname ), 0, 15 ) );
            if (! empty ( $businessname ) && $customer_type == 'Organisation') {
                $userid = strtolower ( substr ( str_replace ( ' ', '', $businessname ), 0, 10 ) );
            }
            
            if (! empty ( $hospitalname ) && $customer_type == 'Hospital') {
                $userid = strtolower ( substr ( str_replace ( ' ', '', $hospitalname ), 0, 10 ) );
            }
            
            $userid = preg_replace ( '/[^\\w]/i', '', $userid );
            $actual_user_id = $userid;
            $next_trim_point = strlen ( $userid ) - 1;
            
            $check = false;
            $index = 1;
            do {
                
                $check = $this->checkDuplicateUserid ( $userid );
                
                if ($check) {
                    $userid = (chop ( $userid, $index - 1 )) . $index ++;
                    continue;
                }
                
                $check = $this->checkDuplicateUseridInMyob ( $userid );
                
                if ($check) {
                    $userid = (chop ( $userid, $index - 1 )) . $index ++;
                }
                if (strlen ( $userid ) > 15) {
                    $userid = substr ( $actual_user_id, 0, $next_trim_point -- );
                    $index = 1;
                    $check = true;
                }
            } while ( $check );
            
            $insertData ['id'] = $userid;
            $random_password = substr ( str_shuffle ( 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ), 0, 10 );
            $insertData ['password'] = $this->createPassword ( $random_password );
            if(DB::table('cdp_user')->insert( $insertData )){
				 DB::table('cdp_user_role')->insert( array (
                        'user_id' => $userid,
                        'role_id' => 6 
                ) );
                $resultarray['msg'] = 'Customer created.';
                $resultarray['code'] = '200';
                return json_encode($resultarray);
            }
            
        }else{
            $resultarray['msg'] = 'Required field not empty.';
            $resultarray['code'] = '400';
            return json_encode($resultarray);
        }

    }
	public function customerEmailUpdate(){
        $userid = (isset($_REQUEST['userid'])) ? $_REQUEST['userid'] : '';        
        $email = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
        $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : ''; 
        if($userid&&$email&&$user_id){
            $check_userid = $this->checkUserId ( $userid );
            if($check_userid){
                $resultarray['msg'] = 'User not exists.';
                $resultarray['code'] = '400';
                return json_encode($resultarray);
            }
            $check_email = $this->checkDuplicateEmail ( $email );
            if($check_email){
                $resultarray['msg'] = 'Email already exists.';
                $resultarray['code'] = '400';
                return json_encode($resultarray);
            }
            $updateData = array (
                'email' => $email,                
                'username' => $email
            );
            $updateData ['lastupdated_by'] = $user_id;
            $updateData ['lastupdated_date'] = date ( "Y-m-d H:i:s" );
            if(DB::table('cdp_user')->where('id', '=', $userid)->update( $updateData )){
                $resultarray['msg'] = 'Email updated.';
                $resultarray['code'] = '200';
                return json_encode($resultarray);
            }

        }else{
            $resultarray['msg'] = 'Required field not empty.';
            $resultarray['code'] = '400';
            return json_encode($resultarray);
        }
    }
    public function customerUpdate(){        
        $userid = (isset($_REQUEST['userid'])) ? $_REQUEST['userid'] : '';
        $customer_type = (isset($_REQUEST['customer_type'])) ? $_REQUEST['customer_type'] : '';
        $business_name = (isset($_REQUEST['business_name'])) ? $_REQUEST['business_name'] : '';
        $hospital_name = (isset($_REQUEST['hospital_name'])) ? $_REQUEST['hospital_name'] : '';
        $occupation = (isset($_REQUEST['occupation'])) ? $_REQUEST['occupation'] : '';        
        $title = (isset($_REQUEST['title'])) ? $_REQUEST['title'] : '';
        $given_name = (isset($_REQUEST['given_name'])) ? $_REQUEST['given_name'] : '';
        $surname = (isset($_REQUEST['surname'])) ? $_REQUEST['surname'] : '';
        $state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
        $country = (isset($_REQUEST['country'])) ? $_REQUEST['country'] : '';
        $flag = (isset($_REQUEST['flag'])) ? $_REQUEST['flag'] : '';
        $address = (isset($_REQUEST['address'])) ? $_REQUEST['address'] : '';
        $subrub = (isset($_REQUEST['subrub'])) ? $_REQUEST['subrub'] : '';
        $postal_code = (isset($_REQUEST['postal_code'])) ? $_REQUEST['postal_code'] : '';
        $phone_number = (isset($_REQUEST['phone_number'])) ? $_REQUEST['phone_number'] : '';
        $email = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
        $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';   
        
        //$logged_status = (isset($_REQUEST['logged_status'])) ? $_REQUEST['logged_status'] : '';

        if($userid&&$customer_type&&$title&&$given_name&&$surname&&$email&&$occupation&&$address&&$subrub&&$state&&$postal_code&&$phone_number&&$country&&$user_id){
            $check_userid = $this->checkUserId ( $userid );
            if($check_userid){
                $resultarray['msg'] = 'User not exists.';
                $resultarray['code'] = '400';
                return json_encode($resultarray);
            }
            $updateData = array (                                
                'customer_type' => $customer_type,                
                'occupation' => $occupation,
                'title' => $title,
                'given_name' => $given_name,
                'surname' => $surname,
                'state' => $state,
                'country' => $country,                 
                'street_address' => $address,
                'suburb' => $subrub,                
                'postal_code' => $postal_code,
                'phone_number' => $phone_number,
                'email' => $email,                
                'username' => $email,                
                'version' => '0' 
            );
            if($business_name){
                $updateData['businessname'] = $business_name;
            } 
            if($hospital_name){
                $updateData['hospitalname'] = $hospital_name;
            } 
            if($flag){
                $updateData['flags'] = $flag;
            }
            $updateData ['lastupdated_by'] = $user_id;
            $updateData ['lastupdated_date'] = date ( "Y-m-d H:i:s" );

            
            if(DB::table('cdp_user')->where('id', '=', $userid)->update( $updateData )){
                $resultarray['msg'] = 'Customer updated.';
                $resultarray['code'] = '200';
                return json_encode($resultarray);
            }
            //print_r($insertData);die();
        }else{
            $resultarray['msg'] = 'Required field not empty.';
            $resultarray['code'] = '400';
            return json_encode($resultarray);
        }

    }
    public function checkUserId($userid) {
        
        $results = DB::table('cdp_user')->select('id')->where('id', '=', $userid)->count();
        
        if ($results > 0) {
            return false;
        } else {
            return true;
        }
    }
    public function checkDuplicateEmail($email) {
        
        $results = DB::table('cdp_user')->select('email')->where('email', '=', $email)->count();
        
        if ($results > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function checkDuplicateUserid($userid) {
        
        $results = DB::table('cdp_user')->select('id')->where('id', '=', $userid)->count();
        
        if ($results > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function checkDuplicateUseridInMyob($userid) {
        $results = DB::table('cdp_myob_ext_user')->select('user_id')->where('user_id', '=', $userid)->count();      
        
        if ($results > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function createPassword ( $random_password ){
        $salt = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
        return md5 ( $salt . $random_password );
    }
	public function userOrder(){
        $userid = (isset($_REQUEST['userid'])) ? $_REQUEST['userid'] : '';
		$check_userid = $this->checkUserId ( $userid );
		if($check_userid){
			$resultarray['msg'] = 'User not exists.';
			$resultarray['code'] = '400';
			return json_encode($resultarray);
		}
        if($userid){
            $data = array ();
            $books = $this->getBookSubscriptions();
            if ($books) {
                foreach ( $books as $subkey => $book ) {
                    $data ['subscriptions'] [$subkey] = $book;
                    $data ['subscriptions'] [$subkey] ->price = ($this->getSubscriptionPrice ( $book ->subscription_id ))?$this->getSubscriptionPrice ( $book ->subscription_id ):array();
                }
            } else {
                $data ['subscriptions'] = array ();
            }

            $mobileapps = $this->getMobileappsSubscriptions ();        
            if ($mobileapps) {
                foreach ( $mobileapps as $makey => $mobileapp ) {
                    $data ['mobileapp'] [$makey] = $mobileapp;
                    $data ['mobileapp'] [$makey] ->price = ($this->getSubscriptionPrice ( $mobileapp ->subscription_id ))?$this->getSubscriptionPrice ( $mobileapp ->subscription_id ):array();
                }
            }

            $cdofflines = $this->getCdofflinesSubscriptions ( );        
            if ($cdofflines) {                
                foreach ( $cdofflines as $makey => $cdoffline ) {
                    $data ['cdoffline'] [$makey] = $cdoffline;
                    $data ['cdoffline'] [$makey] ->price = ($this->getSubscriptionPrice ( $cdoffline ->subscription_id ))?$this->getSubscriptionPrice ( $cdoffline ->subscription_id ):array();
                }
            }

            $etgproductsind = $this->getEtgproductsindSubscriptions ( );        
            if ($etgproductsind) {
                foreach ( $etgproductsind as $etgkey => $etgproduct ) {
                    $data ['etgproduct_ind'] [$etgkey] = $etgproduct;
                    $data ['etgproduct_ind'] [$etgkey] ->price = ($this->getSubscriptionPrice ( $etgproduct ->subscription_id ))?$this->getSubscriptionPrice ( $etgproduct ->subscription_id ):array();
                }
            }

            $etgproductsmulti = $this->getEtgproductsmultiSubscriptions (  );        
            if ($etgproductsmulti) {
                foreach ( $etgproductsmulti as $etgkey1 => $etgproduct1 ) {
                    $data ['etgproduct_multi'] [$etgkey1] = $etgproduct1;
                    $data ['etgproduct_multi'] [$etgkey1] ->price = ($this->getSubscriptionPrice ( $etgproduct1 ->subscription_id ))?$this->getSubscriptionPrice ( $etgproduct1 ->subscription_id ):array();
                }
            }

            $etgproductsinst = $this->getEtgproductsinstSubscriptions ( );
            if ($etgproductsinst) {
                foreach ( $etgproductsinst as $etgkey2 => $etgproduct2 ) {
                    $data ['etgproduct_inst'] [$etgkey2] = $etgproduct2;
                    $data ['etgproduct_inst'] [$etgkey2] ->price = ($this->getSubscriptionPrice ( $etgproduct2 ->subscription_id ))?$this->getSubscriptionPrice ( $etgproduct2 ->subscription_id ):array();
                }
            }

            $data ['user_id'] = $userid;
            $userdata = DB::table('cdp_user')->where('id', '=', $userid)->get();
            foreach($userdata as $val){
                $data ['result'] = $val;
            }
            $data ['shipping_address'] = array();
            $shipping_address_data = DB::table('cdp_user_shipping_addrs')->where('user_id', '=', $userid)->get();
            foreach($shipping_address_data as $val1){                
                $data ['shipping_address'] = ($val1)?$val1:'';
            }
            return json_encode($data);
            //echo "<pre>";print_r($data);die();
        }
    }
    public function getBookSubscriptions(){        
        $query = $this->commonQuery();
        $finalquery = $query->where('cdp_product.composite','!=', '1');       
        
        if($finalquery->count() > 0){
            $results = $finalquery->get();
            foreach($results as $result){            
                $result_array[] = $result;
            }
            return $result_array;
        }
        
        
    }
    public function getMobileappsSubscriptions(){
        
        $query = $this->commonQuery();
        $finalquery = $query->where('cdp_subscription.guideline_title','=', 'ETG_MOBILE_APP_ADDON');       
        
        if($finalquery->count() > 0){
            $results = $finalquery->get();
            foreach($results as $result){            
                $result_array[] = $result;
            }
            return $result_array;
        }
        
    }
    public function getCdofflinesSubscriptions(){
        
        $query = $this->commonQuery();
        $finalquery = $query->where('cdp_subscription.guideline_title','=', 'ETG_CD_OFFLINE_ADDON');       
        
        if($finalquery->count() > 0){
            $results = $finalquery->get();
            foreach($results as $result){            
                $result_array[] = $result;
            }
            return $result_array;
        }
        
    }
    public function getEtgproductsindSubscriptions(){
        
        $query = $this->commonQuery();
        $finalquery = $query->where('cdp_subscription.guideline_title','=', 'All');       
        $finalquery = $finalquery->where('cdp_subscription.subscription_category_id','=', '4');
        //$finalquery = $finalquery->where('cdp_product_format_mst.product_format_id','=', '4');

        if($finalquery->count() > 0){
            $results = $finalquery->get();
            foreach($results as $result){            
                $result_array[] = $result;
            }
            return $result_array;
        }
        
    }
    public function getEtgproductsmultiSubscriptions(){
        
        $query = $this->commonQuery();
        $finalquery = $query->where('cdp_subscription.guideline_title','=', 'All');       
        $finalquery = $finalquery->where('cdp_subscription.subscription_category_id','=', '6');

        if($finalquery->count() > 0){
            $results = $finalquery->get();
            foreach($results as $result){            
                $result_array[] = $result;
            }
            return $result_array;
        }
        
    }
    public function getEtgproductsinstSubscriptions(){
        
        $query = $this->commonQuery();
        $finalquery = $query->where('cdp_subscription.guideline_title','=', 'ETG_MOBILE_APP_INST');       
        $finalquery = $finalquery->where('cdp_subscription.subscription_category_id','=', '8');
        $finalquery = $finalquery->where('cdp_product_format_mst.product_format_id','=', '10');

        if($finalquery->count() > 0){
            $results = $finalquery->get();
            foreach($results as $result){            
                $result_array[] = $result;
            }
            return $result_array;
        }
        
    }
    public function getSubscriptionPrice($sid, $quantity = 1, $currency = 'AU', $region = 'AU'){        
        $query = DB::table('cdp_subscription_price')->select('cdp_subscription_price.*','cdp_currency_mst.currency_code as currency_code','cdp_currency_mst.currency_symbol as currency_symbol')->leftJoin('cdp_currency_mst','cdp_currency_mst.currency_id','=','cdp_subscription_price.currency_id')->where('cdp_subscription_price.subscription_id', $sid)->where('cdp_currency_mst.currency_code', $currency);

        if($sid != 168){
           $query->where('cdp_subscription_price.user_count', $quantity);
        }
        $results = $query->get();
        if($sid == 168){
            if($results){
                foreach($results as $result){                     
                    $data[$result->user_count] ['text'] = $result->currency_symbol . '' . number_format ( $result->price, 2 );
                    $data[$result->user_count] ['value'] = $result->price;
                    $data[$result->user_count] ['renew'] = $result->renew_price;                
                }
                return $data;                
            }

        }
        if($results){
            foreach($results as $result){                
                $data ['text'] = $result->currency_symbol . '' . number_format ( $result->price, 2 );
                $data ['value'] = $result->price;
                $data ['renew'] = $result->renew_price;
            return $data;
            }
            
        }     
        
        return false;
    }
    public function commonQuery(){
        return $query = DB::table('cdp_subscription')->select('cdp_subscription.*', 'cdp_publisher_mst.publisher_name as publisher_name','cdp_subscription_type_mst.subscription_type as subscription_type','cdp_subscription_category_mst.subscription_category_name as subscription_category_name', 'cdp_subscription_product.product_id as product_ids','cdp_product.ordering as ordering','cdp_product.product_title as products','cdp_product_guideline.guideline_id as guideline_id','cdp_guideline_content.is_management_guideline as is_management_guideline','cdp_guideline_content.is_management_guideline as is_print_guideline','cdp_guideline_content.is_set_of_guideline as is_set_of_guideline','cdp_product_format_mst.format_name as product_formats')->leftJoin('cdp_publisher_mst', 'cdp_subscription.publisher_id', '=', 'cdp_publisher_mst.publisher_id')->leftJoin('cdp_subscription_type_mst', 'cdp_subscription.subscription_type_id', '=', 'cdp_subscription_type_mst.subscription_type_id')->leftJoin('cdp_subscription_category_mst','cdp_subscription.subscription_category_id','=','cdp_subscription_category_mst.subscription_category_id')->leftJoin('cdp_subscription_product','cdp_subscription.subscription_id','=','cdp_subscription_product.subscription_id')->leftJoin('cdp_product','cdp_subscription_product.product_id','=','cdp_product.product_id')->leftJoin('cdp_product_guideline','cdp_product_guideline.product_id','=','cdp_product.product_id')->leftJoin('cdp_guideline_content','cdp_guideline_content.guideline_title','=','cdp_subscription.guideline_title')->leftJoin('cdp_product_format_mst','cdp_product_format_mst.product_format_id','=','cdp_product.product_format_id')->where('cdp_subscription.status','!=', '2')->where('cdp_subscription.status','=', '1');
    }




}