<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstituteController extends Controller
{
    public function searchinstitute()
    {
        $instarray = array();
        $insta1array = array();
        $appendsql = '';
        if (!empty($_REQUEST['userid'])) {
            $userid = $_REQUEST['userid'];
            $appendsql .= "and `cdp_institution_account_user`.`user_id`='$userid'";
        }
        if (!empty($_REQUEST['institutionname'])) {
            $institution_name = $_REQUEST['institutionname'];

            $appendsql .= "and (`cdp_institution_details`.`institution_name` LIKE '%$institution_name%')";
        }
        if (!empty($_REQUEST['ipaddress'])) {
            $ipaddress = $_REQUEST['ipaddress'];
            $appendsql .= " and ('" . $ipaddress . "' between `cdp_ip_authentication`.`low_ip` and `cdp_ip_authentication`.`high_ip`)";
        }
        if (!empty($_REQUEST['institutioncode'])) {
            $institutioncode = $_REQUEST['institutioncode'];
            $appendsql .= "and (`cdp_institution_details`.`institution_code` like '%" . $institutioncode . "%')";
        }
        if (!empty($_REQUEST['status'])) {
            $status = $_REQUEST['status'];
            $appendsql .= "and`cdp_institution_details`.`institution_status`='$status'";
        } 

        $institute = DB::select("SELECT `cdp_institution_details`.`institution_id` AS `institution_id`,
								 `cdp_institution_details`.`institution_code` AS `institution_code`, 
								 `cdp_institution_details`.`institution_name` AS `institution_name`,
								 `cdp_institution_details`.`institution_type` AS `institution_type`,
								 `cdp_institution_details`.`institution_status` AS `institution_status`,
								 `cdp_institution_details`.`created_date` AS `created_date`, 
								 Group_Concat(DISTINCT cdp_institution_account_user.user_id) 
								 AS `user_id` FROM `cdp_institution_details` 
								 LEFT JOIN `cdp_institution_account_user`
								 ON `cdp_institution_account_user`.`institution_id` = `cdp_institution_details`.`institution_id`
								 LEFT JOIN `cdp_ip_authentication` ON `cdp_ip_authentication`.`institution_id` = `cdp_institution_details`.`institution_id` 
								 LEFT JOIN `cdp_ins_pregenkey` ON `cdp_ins_pregenkey`.`ins_id` = `cdp_institution_details`.`institution_id`
								 LEFT JOIN `cdp_inst_user_token` ON `cdp_inst_user_token`.`token_id` = `cdp_ins_pregenkey`.`id`
								 where 1 $appendsql
								 GROUP BY `cdp_institution_details`.`institution_id` ORDER BY `created_date` DESC");
			
        if ($institute) {

            foreach ($institute as $institutes) {
                $instarray['institution_name'] = $institutes->institution_name;
                $instarray['institution_code'] = $institutes->institution_code;
                $instarray['institution_type'] = $institutes->institution_type;
				 $instarray['institution_status'] = $institutes->institution_status;
                //$instarray['ipaddress'] = $institutes->low_ip.$institutes->high_ip;
                $instarray['status'] = $institutes->institution_status;
                $instarray['userid'] = $institutes->user_id;
                $instarray['institution_id'] = $institutes->institution_id;

                array_push($insta1array, $instarray);
            }
        }

        return json_encode($insta1array);
    }

    public function deleteinstitution()
    {

        // print_r($_REQUEST);
        if (!empty($_REQUEST['institutionid'])) {
            $array = array();
            $deleteinstitution = DB::table('cdp_institution_details')->where('institution_id', $_REQUEST['institutionid'])
                ->update(['institution_status' => '2']);

            if ($deleteinstitution) {

                $array['code'] = '200';
                $array['response'] = 'successfully deleted';
            } else {
                $array['code'] = '406';
                $array['response'] = 'not Updated';
            }
        } else {
            $array['code'] = '200';
            $array['response'] = 'something went wrong';
        }
        return json_encode($array);
    }
	

    public function createinstitution()
    {
		
        $createinsta = array();
        $createinsta1 = array();
        $publisherid = (isset($_REQUEST['publisher_id'])) ? $_REQUEST['publisher_id'] : '';
		
        $institution_name =(isset($_REQUEST['institution_name'])) ? $_REQUEST['institution_name'] : '';
        $institution_code =(isset($_REQUEST['institution_code'])) ? $_REQUEST['institution_code'] : '';
        $institution_type = (isset($_REQUEST['institution_type'])) ? $_REQUEST['institution_type'] : '';
        $contact_title = (isset($_REQUEST['contact_title'])) ? $_REQUEST['contact_title'] : '';
        $contact_firstname =(isset($_REQUEST['contact_firstname'])) ? $_REQUEST['contact_firstname'] : '';
        $contact_lastname = (isset($_REQUEST['contact_lastname'])) ? $_REQUEST['contact_lastname'] : '';
        $contact_street_address = (isset($_REQUEST['contact_street_address'])) ? $_REQUEST['contact_street_address'] : '';
        $contact_state = (isset($_REQUEST['contact_state'])) ? $_REQUEST['contact_state'] : '';
        $contact_country = (isset($_REQUEST['contact_country'])) ? $_REQUEST['contact_country'] : '';
        $contact_email = (isset($_REQUEST['contact_email'])) ? $_REQUEST['contact_email'] : '';
        $contact_city = (isset($_REQUEST['contact_city'])) ? $_REQUEST['contact_city'] : '';
        $contact_postal_code = (isset($_REQUEST['contact_postal_code'])) ? $_REQUEST['contact_postal_code'] : '';
        $contact_phone = (isset($_REQUEST['contact_phone'])) ? $_REQUEST['contact_phone'] : '';
        $biiAdd_isContactAddr =(isset($_REQUEST['biiAdd_isContactAddr'])) ? $_REQUEST['biiAdd_isContactAddr'] : '';
        $bill_title =(isset($_REQUEST['bill_title'])) ? $_REQUEST['bill_title'] : '';
        $billing_inst_name = (isset($_REQUEST['billing_inst_name'])) ? $_REQUEST['billing_inst_name'] : '';
        $billing_firstname = (isset($_REQUEST['billing_firstname'])) ? $_REQUEST['billing_firstname'] : '';
        $billing_lastname = (isset($_REQUEST['billing_lastname'])) ? $_REQUEST['billing_lastname'] : '';
        $billing_street_address = (isset($_REQUEST['billing_street_address'])) ? $_REQUEST['billing_street_address'] : '';
        $billing_state =(isset($_REQUEST['billing_state'])) ? $_REQUEST['billing_state'] : '';
        $billing_country =(isset($_REQUEST['billing_country'])) ? $_REQUEST['billing_country'] : '';
        $billing_email = (isset($_REQUEST['billing_email'])) ? $_REQUEST['billing_email'] : '';
        $billing_city =(isset($_REQUEST['billing_city'])) ? $_REQUEST['billing_city'] : '';
        $billing_postal_code = (isset($_REQUEST['billing_postal_code'])) ? $_REQUEST['billing_postal_code'] : '';
        $billing_phone = (isset($_REQUEST['billing_phone'])) ? $_REQUEST['billing_phone'] : '';
        $shipAdd_isBillAddr = (isset($_REQUEST['shipAdd_isBillAddr'])) ? $_REQUEST['shipAdd_isBillAddr'] : '';
        $ship_title = (isset($_REQUEST['ship_title'])) ? $_REQUEST['ship_title'] : '';
        $shipping_inst_name = (isset($_REQUEST['shipping_inst_name'])) ? $_REQUEST['shipping_inst_name'] : '';
        $shipping_firstname = (isset($_REQUEST['shipping_firstname'])) ? $_REQUEST['shipping_firstname'] : '';
        $shipping_lastname =(isset($_REQUEST['shipping_lastname'])) ? $_REQUEST['shipping_lastname'] : '';
        $shipping_street_address =(isset($_REQUEST['shipping_street_address'])) ? $_REQUEST['shipping_street_address'] : '';
        $shipping_state =(isset($_REQUEST['shipping_state'])) ? $_REQUEST['shipping_state'] : '';
        $shipping_country =(isset($_REQUEST['shipping_country'])) ? $_REQUEST['shipping_country'] : '';
        $shipping_email =(isset($_REQUEST['shipping_email'])) ? $_REQUEST['shipping_email'] : '';
        $shipping_city =(isset($_REQUEST['shipping_city'])) ? $_REQUEST['shipping_city'] : '';
        $shipping_postal_code =(isset($_REQUEST['shipping_postal_code'])) ? $_REQUEST['shipping_postal_code'] : '';
        $shipping_phone =(isset($_REQUEST['shipping_phone'])) ? $_REQUEST['shipping_phone'] : '';
        $comment =(isset($_REQUEST['comment'])) ? $_REQUEST['comment'] : '';
        $concurrent_users =(isset($_REQUEST['concurrent_users'])) ? $_REQUEST['concurrent_users'] : '';
        $institution_status =(isset($_REQUEST['institution_status'])) ? $_REQUEST['institution_status'] : '';
        $institution_id = (isset($_REQUEST['institution_id'])) ? $_REQUEST['institution_id'] : '';
		 $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';
        /*if (isset($_REQUEST['logo']) && $_REQUEST['logo'] != '') {
            $logo = $_REQUEST['logo'];
        }*/
        if ($publisherid&&$institution_name&&$institution_code&&$institution_type&&$contact_title&&$contact_firstname&&$contact_lastname&&$contact_street_address&&$contact_state&&$contact_country&&$contact_email&&$contact_city&&$contact_postal_code&&$contact_phone&&$biiAdd_isContactAddr&&$bill_title&&$billing_inst_name&&$billing_firstname&&$billing_lastname&&$billing_street_address&&$billing_state&&$billing_country&&$billing_country&&$billing_email&&$billing_city&&$billing_postal_code&&$billing_phone&&$shipAdd_isBillAddr&&$ship_title&&$shipping_inst_name&&$shipping_firstname&&$shipping_lastname&&$shipping_street_address&&$shipping_state&&$shipping_country&&$shipping_email&&$shipping_city&&$shipping_postal_code&&$shipping_phone&&$comment&&$concurrent_users&&$institution_status) {
            $createinstitution = DB::table('cdp_institution_details')->insert(
                [
                    'publisher_id' => $publisherid,
                    'institution_name' => $institution_name,
                    'institution_code' => $institution_code,
                    'institution_type' => $institution_type,
                    'contact_title' => $contact_title,
                    'contact_firstname' => $contact_firstname,
                    'contact_lastname' => $contact_lastname,
                    'contact_street_address' => $contact_street_address,
                    'contact_state' => $contact_state,
                    'contact_country' => $contact_country,
                    'contact_email' => $contact_email,
                    'contact_city' => $contact_city,
                    'contact_postal_code' => $contact_postal_code,
                    'contact_phone' => $contact_phone,
                    'biiAdd_isContactAddr' => $biiAdd_isContactAddr,
                    'bill_title' => $bill_title,
                    'billing_inst_name' => $billing_inst_name,
                    'billing_firstname' => $billing_firstname,
                    'billing_lastname' => $billing_lastname,
                    'billing_street_address' => $billing_street_address,
                    'billing_state' => $billing_state,
                    'billing_country' => $billing_country,
                    'billing_email' => $billing_email,
                    'billing_city' => $billing_city,
                    'billing_postal_code' => $billing_postal_code,
                    'billing_phone' => $billing_phone,
                    'shipAdd_isBillAddr' => $shipAdd_isBillAddr,
                    'ship_title' => $ship_title,
                    'shipping_inst_name' => $shipping_inst_name,
                    'shipping_firstname' => $shipping_firstname,
                    'shipping_lastname' => $shipping_lastname,
                    'shipping_street_address' => $shipping_street_address,
                    'shipping_state' => $shipping_state,
                    'shipping_country' => $shipping_country,
                    'shipping_email' => $shipping_email,
                    'shipping_city' => $shipping_city,
                    'shipping_postal_code' => $shipping_postal_code,
                    'shipping_phone' => $shipping_phone,
                    'comment' => $comment,
                    'concurrent_users' => $concurrent_users,
                    'institution_status' => $institution_status,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d h:i:s')

                ]
            );
            $institution_id = DB::getPdo()->lastInsertId();
			$createinsta['msg']='successfully created';
			$createinsta['institute_id']=$institution_id;
         
    } 
	
	
	
	else{
			$createinsta['msg']='Access Denied';
			$createinsta['code']='400';
		
	}
			return json_encode($createinsta);
} 
		public function updateinstitution(){
			
			
		$updateinsta = array();
        $updateinsta = array();
        $publisherid = (isset($_REQUEST['publisher_id'])) ? $_REQUEST['publisher_id'] : '';
		
        $institution_name =(isset($_REQUEST['institution_name'])) ? $_REQUEST['institution_name'] : '';
        $institution_code =(isset($_REQUEST['institution_code'])) ? $_REQUEST['institution_code'] : '';
        $institution_type = (isset($_REQUEST['institution_type'])) ? $_REQUEST['institution_type'] : '';
        $contact_title = (isset($_REQUEST['contact_title'])) ? $_REQUEST['contact_title'] : '';
        $contact_firstname =(isset($_REQUEST['contact_firstname'])) ? $_REQUEST['contact_firstname'] : '';
        $contact_lastname = (isset($_REQUEST['contact_lastname'])) ? $_REQUEST['contact_lastname'] : '';
        $contact_street_address = (isset($_REQUEST['contact_street_address'])) ? $_REQUEST['contact_street_address'] : '';
        $contact_state = (isset($_REQUEST['contact_state'])) ? $_REQUEST['contact_state'] : '';
        $contact_country = (isset($_REQUEST['contact_country'])) ? $_REQUEST['contact_country'] : '';
        $contact_email = (isset($_REQUEST['contact_email'])) ? $_REQUEST['contact_email'] : '';
        $contact_city = (isset($_REQUEST['contact_city'])) ? $_REQUEST['contact_city'] : '';
        $contact_postal_code = (isset($_REQUEST['contact_postal_code'])) ? $_REQUEST['contact_postal_code'] : '';
        $contact_phone = (isset($_REQUEST['contact_phone'])) ? $_REQUEST['contact_phone'] : '';
        $biiAdd_isContactAddr =(isset($_REQUEST['biiAdd_isContactAddr'])) ? $_REQUEST['biiAdd_isContactAddr'] : '';
        $bill_title =(isset($_REQUEST['bill_title'])) ? $_REQUEST['bill_title'] : '';
        $billing_inst_name = (isset($_REQUEST['billing_inst_name'])) ? $_REQUEST['billing_inst_name'] : '';
        $billing_firstname = (isset($_REQUEST['billing_firstname'])) ? $_REQUEST['billing_firstname'] : '';
        $billing_lastname = (isset($_REQUEST['billing_lastname'])) ? $_REQUEST['billing_lastname'] : '';
        $billing_street_address = (isset($_REQUEST['billing_street_address'])) ? $_REQUEST['billing_street_address'] : '';
        $billing_state =(isset($_REQUEST['billing_state'])) ? $_REQUEST['billing_state'] : '';
        $billing_country =(isset($_REQUEST['billing_country'])) ? $_REQUEST['billing_country'] : '';
        $billing_email = (isset($_REQUEST['billing_email'])) ? $_REQUEST['billing_email'] : '';
        $billing_city =(isset($_REQUEST['billing_city'])) ? $_REQUEST['billing_city'] : '';
        $billing_postal_code = (isset($_REQUEST['billing_postal_code'])) ? $_REQUEST['billing_postal_code'] : '';
        $billing_phone = (isset($_REQUEST['billing_phone'])) ? $_REQUEST['billing_phone'] : '';
        $shipAdd_isBillAddr = (isset($_REQUEST['shipAdd_isBillAddr'])) ? $_REQUEST['shipAdd_isBillAddr'] : '';
        $ship_title = (isset($_REQUEST['ship_title'])) ? $_REQUEST['ship_title'] : '';
        $shipping_inst_name = (isset($_REQUEST['shipping_inst_name'])) ? $_REQUEST['shipping_inst_name'] : '';
        $shipping_firstname = (isset($_REQUEST['shipping_firstname'])) ? $_REQUEST['shipping_firstname'] : '';
        $shipping_lastname =(isset($_REQUEST['shipping_lastname'])) ? $_REQUEST['shipping_lastname'] : '';
        $shipping_street_address =(isset($_REQUEST['shipping_street_address'])) ? $_REQUEST['shipping_street_address'] : '';
        $shipping_state =(isset($_REQUEST['shipping_state'])) ? $_REQUEST['shipping_state'] : '';
        $shipping_country =(isset($_REQUEST['shipping_country'])) ? $_REQUEST['shipping_country'] : '';
        $shipping_email =(isset($_REQUEST['shipping_email'])) ? $_REQUEST['shipping_email'] : '';
        $shipping_city =(isset($_REQUEST['shipping_city'])) ? $_REQUEST['shipping_city'] : '';
        $shipping_postal_code =(isset($_REQUEST['shipping_postal_code'])) ? $_REQUEST['shipping_postal_code'] : '';
        $shipping_phone =(isset($_REQUEST['shipping_phone'])) ? $_REQUEST['shipping_phone'] : '';
        $comment =(isset($_REQUEST['comment'])) ? $_REQUEST['comment'] : '';
        $concurrent_users =(isset($_REQUEST['concurrent_users'])) ? $_REQUEST['concurrent_users'] : '';
        $institution_status =(isset($_REQUEST['institution_status'])) ? $_REQUEST['institution_status'] : '';
        $institution_id = (isset($_REQUEST['institution_id'])) ? $_REQUEST['institution_id'] : '';
		 $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';
			if($publisherid&&$institution_name&&$institution_code&&$institution_type&&$contact_title&&$contact_firstname&&$contact_lastname&&$contact_street_address&&$contact_state&&$contact_country&&$contact_email&&$contact_city&&$contact_postal_code&&$contact_phone&&$biiAdd_isContactAddr&&$bill_title&&$billing_inst_name&&$billing_firstname&&$billing_lastname&&$billing_street_address&&$billing_state&&$billing_country&&$billing_country&&$billing_email&&$billing_city&&$billing_postal_code&&$billing_phone&&$shipAdd_isBillAddr&&$ship_title&&$shipping_inst_name&&$shipping_firstname&&$shipping_lastname&&$shipping_street_address&&$shipping_state&&$shipping_country&&$shipping_email&&$shipping_city&&$shipping_postal_code&&$shipping_phone&&$comment&&$concurrent_users&&$institution_status&&$institution_id){
			$updateinstitution = DB::table('cdp_institution_details')->where('institution_id', $institute_id)
                   ->update(['publisher_id' => $publisherid,
                    'institution_name' => $institution_name,
                    'institution_code' => $institution_code,
                    'institution_type' => $institution_type,
                    'contact_title' => $contact_title,
                    'contact_firstname' => $contact_firstname,
                    'contact_lastname' => $contact_lastname,
                    'contact_street_address' => $contact_street_address,
                    'contact_state' => $contact_state,
                    'contact_country' => $contact_country,
                    'contact_email' => $contact_email,
                    'contact_city' => $contact_city,
                    'contact_postal_code' => $contact_postal_code,
                    'contact_phone' => $contact_phone,
                    'biiAdd_isContactAddr' => $biiAdd_isContactAddr,
                    'bill_title' => $bill_title,
                    'billing_inst_name' => $billing_inst_name,
                    'billing_firstname' => $billing_firstname,
                    'billing_lastname' => $billing_lastname,
                    'billing_street_address' => $billing_street_address,
                    'billing_state' => $billing_state,
                    'billing_country' => $billing_country,
                    'billing_email' => $billing_email,
                    'billing_city' => $billing_city,
                    'billing_postal_code' => $billing_postal_code,
                    'billing_phone' => $billing_phone,
                    'shipAdd_isBillAddr' => $shipAdd_isBillAddr,
                    'ship_title' => $ship_title,
                    'shipping_inst_name' => $shipping_inst_name,
                    'shipping_firstname' => $shipping_firstname,
                    'shipping_lastname' => $shipping_lastname,
                    'shipping_street_address' => $shipping_street_address,
                    'shipping_state' => $shipping_state,
                    'shipping_country' => $shipping_country,
                    'shipping_email' => $shipping_email,
                    'shipping_city' => $shipping_city,
                    'shipping_postal_code' => $shipping_postal_code,
                    'shipping_phone' => $shipping_phone,
                    'comment' => $comment,
                    'concurrent_users' => $concurrent_users,
                    'institution_status' => $institution_status,
                    'updated_by' => $user_id,
                    'updated_date' => date('Y-m-d h:i:s')
    
                    ]);
					if($updateinstitution){
						$updateinsta['msg']='Successfully updated';
						$updateinsta['code']='200';
					}
			}else{
					$updateinsta['msg']='Access denied';
					$updateinsta['code']='400';
				
			}
			return json_encode($updateinsta);
			
		}
		public function instituterecord(){
			
			$instrec=array();
			$instrec1=array();
			$institution_id=(isset($_REQUEST['institution_id'])) ? $_REQUEST['institution_id'] : '';
			if($institution_id){
			$getinstituterecord = DB::table('cdp_institution_details')
					->select('cdp_institution_details.*',DB::raw('GROUP_CONCAT(DISTINCT cdp_institute_consortia.consortia_id) as `consortia_ids`'),DB::raw('GROUP_CONCAT(DISTINCT consortia_name) as `consortia`'),DB::raw('GROUP_CONCAT(DISTINCT method_name) as `authentication_method`'))
					->leftjoin('cdp_institute_consortia','cdp_institute_consortia.istitute_id','=','cdp_institution_details.institution_id')
					->leftjoin('cdp_consortia','cdp_consortia.consortia_id','=','cdp_institute_consortia.consortia_id')
					->leftjoin('cdp_institution_authentication_method','cdp_institution_authentication_method.institution_id','=','cdp_institution_details.institution_id')
					->where('cdp_institution_details.institution_id', '=', $institution_id)->get();
					
				/*$getinstituterecord=DB::select("SELECT `cdp_institution_details`.*, 
												GROUP_CONCAT(DISTINCT cdp_institute_consortia.consortia_id) AS `consortia_ids`, 
												GROUP_CONCAT(DISTINCT consortia_name) AS `consortia`, 
												GROUP_CONCAT(DISTINCT method_name) AS `authentication_method`
												FROM `cdp_institution_details` 
												LEFT JOIN `cdp_institute_consortia` ON `cdp_institute_consortia`.`istitute_id` = `cdp_institution_details`.`institution_id`
												LEFT JOIN `cdp_consortia` ON `cdp_consortia`.`consortia_id` = `cdp_institute_consortia`.`consortia_id`
												LEFT JOIN `cdp_institution_authentication_method` 
												ON `cdp_institution_authentication_method`.`institution_id` = `cdp_institution_details`.`institution_id`
												WHERE cdp_institution_details.institution_id = $institution_id");*/
				foreach($getinstituterecord as $record){
					
					$instrec['institution_name'] = $record->institution_name ;
					$instrec['institution_code']=$record->institution_code;
					$instrec['institution_type']=$record->institution_type ;
					$instrec['contact_title']=$record->contact_title ;
					$instrec['contact_firstname']=$record->contact_firstname;
					$instrec['contact_lastname']=$record->contact_lastname ;
					$instrec['contact_street_address']=$record->contact_street_address ;
					$instrec['contact_state']=$record->contact_state;
					$instrec['contact_country']=$record->contact_country ;
					$instrec['contact_email']=$record->contact_email ;
					$instrec['contact_city']=$record->contact_city ;
					$instrec['contact_postal_code']=$record->contact_postal_code ;
					$instrec['contact_phone']=$record->contact_phone ;
					$instrec['biiAdd_isContactAddr']=$record->biiAdd_isContactAddr ;
					$instrec['bill_title']=$record->bill_title ;
					$instrec['billing_inst_name']=$record->billing_inst_name ;
					$instrec['billing_firstname']=$record->billing_firstname ;
					$instrec['billing_lastname']=$record->billing_lastname ;
					$instrec['billing_street_address']=$record->billing_street_address ;
					$instrec['billing_state']=$record->billing_state ;
					$instrec['billing_country']=$record->billing_country ;
					$instrec['billing_email']=$record->billing_email ;
					$instrec['billing_city']=$record->billing_city ;
					$instrec['billing_postal_code']=$record->billing_postal_code ;
					$instrec['billing_phone']=$record->billing_phone;
					$instrec['shipAdd_isBillAddr']=$record->shipAdd_isBillAddr ;
					$instrec['ship_title']=$record->ship_title ;
					$instrec['shipping_inst_name']=$record->shipping_inst_name;
					$instrec['shipping_firstname']=$record->shipping_firstname ;
					$instrec['shipping_lastname']=$record->shipping_lastname;
					$instrec['shipping_street_address']=$record->shipping_street_address ;
					$instrec['shipping_state']=$record->shipping_state ;
					$instrec['shipping_country']=$record->shipping_country ;
					$instrec['shipping_email']=$record->shipping_email ;
					$instrec['shipping_city']=$record->shipping_city ;
					$instrec['shipping_postal_code']=$record->shipping_postal_code ;
					$instrec['shipping_phone']=$record->shipping_phone ;
					$instrec['comment']=$record->comment ;
					$instrec['concurrent_users']=$record->concurrent_users ;
					$instrec['institution_status']=$record->institution_status ;
					$instrec['institution_id']=$record->institution_id ;
					$instrec['authentication_method']=$record->authentication_method ;
					$instrec['consortia_name']=$record->consortia ;
					$instrec['consortia_ids']=$record->consortia_ids ;
							array_push($instrec1,$instrec);
				}					
			}else{
				
					$instrec['msg']='Institution id is missing';
					$instrec['code']='400';
			}
				return json_encode($instrec);
		}
		
		public function getinstitute(){
            $Institutedata=array();
            $Institutedatas=array();
			$Institute = DB::select('select *from cdp_occupation_mst');
            if($Institute){
                foreach($Institute as $Inst){ 
                    $Institutedata['occupation']= $Inst->occupation;
                    array_push($Institutedatas,$Institutedata);
                }

            }else{

                $Institutedatas['msg']='empty result';
                

            }
       
            return json_encode($Institutedatas);
        }  
    }

?>
			
        
        


			


		