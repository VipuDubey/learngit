<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{

    public function searchcoupon()
    {
        //header('Access-Control-Allow-Origin: *');

        $couparray = array();
        $coupnsarray = array();
        $appendSql = '';
        $status=(isset($_REQUEST['status'])) ? $_REQUEST['status'] : '';
       

        if ((!empty($_REQUEST['couponname']))) {
            $couponname = $_REQUEST['couponname'];
            $appendSql .= "and `cdp_coupon`.`coupon_name`IN('$couponname')";

        }

     
        if ($status=='0') {
            $appendSql .= "and `cdp_coupon`.`status`='0'";

        }if($status=='1'){

            $appendSql .= "and `cdp_coupon`.`status`='1'"; 
        }
        

        if ((!empty($_REQUEST['couponcode']))) {
            $couponcode = $_REQUEST['couponcode'];
            $appendSql .= "and `cdp_coupon`.`coupon_code`IN('$couponcode')";

        }

        if ((!empty($_REQUEST['discounttype']))) {
            $discounttype = $_REQUEST['discounttype'];
            $appendSql .= "and `cdp_coupon`.`discount_type`='$discounttype'";

        }
        if ((!empty($_REQUEST['discountvalue']))) {
            $discountvalue = $_REQUEST['discountvalue'];
            $appendSql .= "and `cdp_coupon`.`discount_value`='$discountvalue'";

        }

        $Coupon = DB::select("select * from cdp_coupon
                             left join `cdp_coupon_subscription` ON  `cdp_coupon`.`coupon_id`=`cdp_coupon_subscription`.`coupon_id`
                            left join `cdp_subscription` ON `cdp_coupon_subscription`.`subscription_id`=`cdp_subscription`.`subscription_id`
                         where 1 $appendSql  order by `cdp_coupon`.`created_date` DESC
                         ");

        //print_r($Coupon);exit;
        foreach ($Coupon as $Coupons) {
            $couparray['couponId'] = $Coupons->coupon_id;
            $couparray['couponName'] = $Coupons->coupon_name;
            $couparray['couponCode'] = $Coupons->coupon_code;
            $couparray['coupontype'] = $Coupons->coupon_type;
            $couparray['usesperCoupon'] = $Coupons->uses_per_coupon;
            $couparray['usedCounter'] = $Coupons->used_counter;
            $couparray['discountType'] = $Coupons->discount_type;
            $couparray['discountValue'] = $Coupons->discount_value;
            $couparray['total_amount']=$Coupons->total_amount;
            $couparray['startDate'] = date('Y-m-d', strtotime($Coupons->effective_start_date));
            $couparray['endDate'] = date('Y-m-d', strtotime($Coupons->effective_end_date));
            $couparray['status'] = $Coupons->status;

            array_push($coupnsarray, $couparray);
        }
        return json_encode($coupnsarray);

    }

    #Create Coupon#
    public function createcoupon(Request $request)
    {
        $requestarray = $request->all();
        $array = array();
        $coupon_name = $requestarray['coupon_name']?$requestarray['coupon_name']:'';
        $coupon_code = $requestarray['coupon_code']?$requestarray['coupon_code']:'';
        $uses_per_coupon = $requestarray['uses_per_coupon']?$requestarray['uses_per_coupon']:'';
        $coupon_type = $requestarray['coupon_type']?$requestarray['coupon_type']:'';
        $effective_start_date = $requestarray['effective_start_date']?$requestarray['effective_start_date']:'';
        $total_amount = $requestarray['total_amount']?$requestarray['total_amount']:'';
        $effective_end_date = $requestarray['effective_end_date']?$requestarray['effective_end_date']:'';
        $discount_type = $requestarray['discount_type']?$requestarray['discount_type']:'';
        $discount_value = $requestarray['discount_value']?$requestarray['discount_value']:''; 
        $status = $requestarray['status']?$requestarray['status']:'0';
        $duration = $requestarray['duration']?$requestarray['duration']:'0';
        $duration_type = $requestarray['duration_type']?$requestarray['duration_type']:'';
        $userid = $requestarray['userid']?$requestarray['userid']:'';
        $subscription = $requestarray['subscription']?$requestarray['subscription']:'';
        $associate_subscription = $requestarray['associate_subscription']?$requestarray['associate_subscription']:'';       
        $coupon_duration = $requestarray['coupon_duration']?$requestarray['coupon_duration']:'0';
        $coupon_du_hour = $requestarray['coupon_du_hour']?$requestarray['coupon_du_hour']:'0';
        $uses_per_customer = $requestarray['uses_per_customer']?$requestarray['uses_per_customer']:'';

        //echo $coupon_duration;die();
        if(($coupon_name&&$coupon_code&&$uses_per_coupon&&$coupon_type&&$effective_start_date&&$total_amount&&$effective_end_date&&$discount_type&&$discount_value&&$duration_type&&$userid&&$subscription&&$associate_subscription&&$discount_type&&$uses_per_customer)||($coupon_name&&$coupon_code&&$uses_per_coupon&&$coupon_type&&$effective_start_date&&$total_amount&&$effective_end_date&&$discount_type&&$duration_type&&$userid&&$subscription&&$associate_subscription&&$discount_type&&$uses_per_customer)){
            if (!empty($_REQUEST['couponid'])) {
                $id = $_REQUEST['couponid'];
                $updatecoupon = DB::table('cdp_coupon')->where('coupon_id','=', $id)
                    ->update(['status' => $status,
                        'coupon_name' => $coupon_name,
                        'coupon_code' => $coupon_code,
                        'coupon_type' => $coupon_type,
                        'uses_per_coupon' => $uses_per_coupon,
                        'associate_subscription' => $associate_subscription,
                        'uses_per_customer' => $uses_per_customer,
                        'effective_start_date' => $effective_start_date,
                        'effective_end_date' => $effective_end_date,
                        'total_amount' => $total_amount,
                        'discount_type' => $discount_type,
                        'discount_value' => $discount_value,
                        'modified_by' => $userid,
                        'modify_date' => date('Y-m-d h:i:s')]);
                if ($_REQUEST['subscription']) {
                    $subscription = $_REQUEST['subscription'];
                    $subscription = (explode(",", $_REQUEST['subscription']));
                    $delete = DB::table('cdp_coupon_subscription')->where('coupon_id', $id)->delete();
                    if ($_REQUEST['coupon_type'] == 'FA') {
                        $coupon_du_hour = $_REQUEST['coupon_du_hour'];
                    } else {
                        $coupon_du_hour = '0';
                        $coupon_duration = '0';
                    }
                    foreach ($subscription as $subscription_id) {
                        $createcoupon = DB::table('cdp_coupon_subscription')->insert([
                            'coupon_id' => $id,
                            'subscription_id' => $subscription_id,
                            'duration' => $coupon_duration,
                            'duration_type' => $duration_type,
                            'duration_hour' => $coupon_du_hour]);

                    }

                }
                $array['msg'] = 'updated data';                
                $array['id'] = $id;
                return json_encode($array);
            } else {
                $checkcoupon = $this->checkCoupon($coupon_name);
                if(!$checkcoupon){
                    $array['msg'] = 'Coupons already exists.';
                    $array['code'] = 400;
                    return json_encode($array);exit;
                }
                $createcoupon = DB::table('cdp_coupon')
                    ->insert(['status' => $status,
                        'coupon_name' => $coupon_name,
                        'coupon_code' => $coupon_code,
                        'coupon_type' => $coupon_type,
                        'uses_per_coupon' => $uses_per_coupon,
                        'associate_subscription' => $associate_subscription,
                        'uses_per_customer' => $uses_per_customer,
                        'effective_start_date' => $effective_start_date,
                        'effective_end_date' => $effective_end_date,
                        'total_amount' => $total_amount,
                        'discount_type' => $discount_type,
                        'discount_value' => $discount_value,
                        'created_by' => $userid,
                        'created_date' => date('Y-m-d h:i:s')]
                    );
                $id = DB::getPdo()->lastInsertId();
                if ($_REQUEST['subscription']) {
                    $subscription = $_REQUEST['subscription'];
                    $subscription = (explode(",", $_REQUEST['subscription']));
                    $delete = DB::table('cdp_coupon_subscription')->where('coupon_id', $id)->delete();
                    if ($_REQUEST['coupon_type'] == 'FA') {
                        $coupon_du_hour = $_REQUEST['coupon_du_hour'];
                    } else {
                        $coupon_du_hour = '0';
                        $coupon_duration = '0';
                    }
                    foreach ($subscription as $subscription_id) {
                        $createcoupon = DB::table('cdp_coupon_subscription')->insert([
                            'coupon_id' => $id,
                            'subscription_id' => $subscription_id,
                            'duration' => $coupon_duration,
                            'duration_type' => $duration_type,
                            'duration_hour' => $coupon_du_hour]);

                    }

                }
                $array['msg'] = 'Inserted data';
                $array['id'] = $id;
                return json_encode($array);
            }
            
            
        } else{
            $resultarray['msg'] = 'Required field not empty.';
            $resultarray['code'] = '400';
            return json_encode($resultarray);
        }
        

    }
    public function checkCoupon($coupon_name) {
        
        $results = DB::table('cdp_coupon')->select('coupon_name')->where('coupon_name', '=', $coupon_name)->count();
        
        if ($results > 0) {
            return false;
        } else {
            return true;
        }
    }
    public function deleteCoupon()
    {
        $respnsearray = array();
        //header('Access-Control-Allow-Origin: *');
        //print_r($_REQUEST);

        $couponId = $_REQUEST['couponid'];

        $checkcouponId = DB::select("SELECT `cdp_coupon`.*,
                                        Group_Concat(DISTINCT cdp_coupon_subscription.subscription_id) AS `subscriptions`,
                                        Group_Concat(DISTINCT cdp_coupon_subscription.duration) AS `duration`,
                                        Group_Concat(DISTINCT cdp_coupon_subscription.duration_type) AS `duration_type`
                                        FROM `cdp_coupon` LEFT JOIN `cdp_coupon_subscription`
                                        ON `cdp_coupon`.`coupon_id` = `cdp_coupon_subscription`.`coupon_id`
                                        WHERE cdp_coupon.coupon_id= $couponId  GROUP BY `cdp_coupon`.`coupon_id`");
        if ($checkcouponId) {

            $respnsearray['response']['msg'] = "You can't delete this coupon.Coupon in use !";
            $respnsearray['response']['status'] = "200";
        } else {

            $deletecoupon = DB::table('cdp_coupon')->where('coupon_id', $_REQUEST['couponid'])
                ->update(['status' => '2']);

            if ($deletecoupon) {

                $respnsearray['response'] = 'successfully deleted';
                $respnsearray['response']['status'] = "200";
            } else {

                $respnsearray['response'] = 'not Updated';
                $respnsearray['response']['status'] = "504";
            }
        }

        return json_encode($respnsearray);

    }

    public function getSubscriptionForCoupon()
    {
        $subcoupon = array();
        $subcoupon1 = array();
        $selectcoupsub = DB::select("select `s`.`subscription_id` AS `subscription_id`,
         `s`.`title` AS `title`,
         `sc`.`subscription_category_name` AS `subscription_category_name`
         FROM `cdp_subscription` AS `s`
          LEFT JOIN `cdp_subscription_category_mst` AS `sc`
          ON `sc`.`subscription_category_id` = `s`.`subscription_category_id`
        WHERE `s`.`status` = '1' and `s`.`subscription_category_id` NOT IN ('5')
        AND `s`.`guideline_title` NOT IN ('Mini TTG') ORDER BY `s`.`title` ASC");
        if ($selectcoupsub) {

            foreach ($selectcoupsub as $subcouponlist) {
                $subcoupon['subscription_id'] = $subcouponlist->subscription_id;
                $subcoupon['title'] = $subcouponlist->title;
                $subcoupon['subscription_category_name'] = $subcouponlist->subscription_category_name;
                array_push($subcoupon1, $subcoupon);
                $subcoupon1['status'] = '200';
            }
        } else {
            $subcoupon1['msg'] = 'empty result';
            $subcoupon1['status'] = '400';

        }

        return json_encode($subcoupon1);

    }

}
