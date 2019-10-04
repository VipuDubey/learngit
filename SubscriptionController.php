<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller{

    public function GetSubscription(){

        $subarray=array();
        $subarray1=array();

        $Subscription=DB::select("select *
                                 from cdp_subscription 
                                 join cdp_subscription_category_mst
                                ON  cdp_subscription_category_mst.subscription_category_id=cdp_subscription.subscription_category_id 
                                where cdp_subscription.status=1 and cdp_subscription.subscription_category_id NOT IN (5) and cdp_subscription.guideline_title NOT IN ('Mini TTG') order by cdp_subscription.title ASC ");
            foreach($Subscription as $Subscription1){

                    $subarray['subscriptionid']=$Subscription1->subscription_id;
                    $subarray['subscriptiontitle']=$Subscription1->title;
                    array_push($subarray1,$subarray);
            }

                return json_encode($subarray1);

    }

}



?>