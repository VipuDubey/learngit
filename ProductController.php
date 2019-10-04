<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{


    
    public function searchproduct(Request $request)
    {
        header('Access-Control-Allow-Origin: *');

        $product = array();
        $productlist = array();
        $appendSql = '';
        try {


            if (empty($_REQUEST)) {

                $appendSql .= '';

            }
            if ((!empty($_REQUEST['product_title']))) {

                $title = $_REQUEST['product_title'];
                $appendSql .= "and (`cdp_product`.`product_title` IN ('$title') or `cdp_product`.`product_title` LIKE '%$title%')";

            }
            if ((!empty($_REQUEST['subscription_category']))) {
                $category = $_REQUEST['subscription_category'];
                $appendSql .= "and cdp_subscription_category_mst.subscription_category_name IN ('$category')";
            }
            if ((!empty($_REQUEST['product_format']))) {
                $format = $_REQUEST['product_format'];
                $appendSql .= "and cdp_product_format_mst.format_name IN('$format')";
            }

            if (!empty($_REQUEST['status'])) {
                $status = $_REQUEST['status'];
                $appendSql .= "and `cdp_product`.`product_status`='$status'";

            }
            $Product = DB::select("Select  `cdp_product`.`product_id`,
                                `cdp_product`.`product_status`,
                                `cdp_product`.`product_format_id`,
                                `cdp_product`.`product_title`,
                                `cdp_product`.`composite`,
                                `cdp_product`.`ordering`,
                                `cdp_product`.`created_by`,
                                `cdp_product`.`lastupdated_date`,
                                `cdp_product_format_mst`.`format_name`,
                                `cdp_subscription_category_mst`.`subscription_category_name`,
                                `cdp_subscription`.`status`,
                                `cdp_subscription`.`subscription_id`,
                                `cdp_subscription`.`duration`,
                                `cdp_subscription`.`duration_type`,
                                `cdp_subscription`.`guideline_title`,
                                `cdp_subscription`.`grace_period`,
                                `cdp_subscription`.`grace_period_type`,
                                `cdp_subscription`.`myob_item_number`,
                                `cdp_subscription`.`version`,
                                `cdp_subscription`.`description`,
                                `cdp_subscription`.`subscription_category_id`
                                from  `cdp_product`  JOIN `cdp_product_format_mst`
                                ON `cdp_product_format_mst`.`product_format_id`=`cdp_product`.`product_format_id`
                                join   `cdp_subscription_product` ON `cdp_subscription_product`.`product_id`=`cdp_product`.`product_id`
                                join `cdp_subscription` ON `cdp_subscription`.`subscription_id`=`cdp_subscription_product`.`subscription_id`
                                join `cdp_subscription_category_mst` ON `cdp_subscription_category_mst`.`subscription_category_id`=`cdp_subscription`.`subscription_category_id`
                                where 1 $appendSql group by product_id");

            foreach ($Product as $Products) {
                $product['product_id'] = $Products->product_id;
                $product['ordering'] = $Products->ordering;
                $product['product_title'] = $Products->product_title;
                $product['product_format'] = $Products->product_format_id;
				 $product['product_format_value'] = $Products->format_name;
                $product['subscription_category'] = $Products->subscription_category_id;
				 $product['subscription_category_value'] = $Products->subscription_category_name;
                $product['duration'] = $Products->duration;
                $product['duration_type'] = $Products->duration_type;
                $product['status'] = $Products->product_status;
                $product['guideline_title'] = $Products->guideline_title;
                $product['grace_period'] = $Products->grace_period;
                $product['myob_item_number'] = $Products->myob_item_number;
                $product['version'] = $Products->version;
                $product['description'] = $Products->description;
                $product['subscription_category_id'] = $Products->subscription_category_id;
                $product['subscription_id'] = $Products->subscription_id;
                $product['msg'] = 'Success';
                array_push($productlist, $product);
            }

        } catch (Exception $e) {
            report($e);

            return false;
        }
        return json_encode($productlist);
    }

    public function saveGuideline()
    {

        if ($subscription_id) {
           
            $guidelineInfo = $this->getGuidelineTitle($product_title);
            if (count($guidelineInfo) > 0) {
                $updateGuidelinedata = DB::table('cdp_guideline_content')
                    ->where('guideline_id', $productModel['guideline_ids'])
                    ->update(['guideline_version' => '1',
                        'is_set_of_guideline' => '1',
                        'guideline_publishing_year' => date('Y'),
                    ]);
            } else {

                $postGuidelinedata = DB::table('cdp_guideline_content')->insert(['guideline_title' => $product_title,
                    'guideline_version' => '1',
                    'is_set_of_guideline' => '1',
                    'guideline_publishing_year' => date('Y'),
                    'created_by' => '',
                    'created_date' => date('Y-m-d H:i:s'),

                ]);
            }

        }
    }

 
    public function getProductById($product_id)
    {

            $prodvalue=array();
            $getProductresult = DB::select("SELECT `cdp_product`.*,
            `cdp_product_format_mst`.`format_name` AS `format_name`,
            Group_Concat(`cdp_product_guideline`.`guideline_id`) AS `guideline_ids`,
            Group_Concat(`cdp_guideline_content`.`guideline_title`) AS `guideline_names`
             FROM `cdp_product` INNER JOIN `cdp_product_format_mst`
             ON `cdp_product`.`product_format_id` = `cdp_product_format_mst`.`product_format_id`
             LEFT JOIN `cdp_product_guideline` ON `cdp_product`.`product_id` = `cdp_product_guideline`.`product_id`
              LEFT JOIN `cdp_guideline_content` ON `cdp_product_guideline`.`guideline_id` = `cdp_guideline_content`.`guideline_id`
              WHERE `cdp_product`.`product_id`='$product_id' GROUP BY `cdp_product`.`product_id`");
                foreach($getProductresult as $product){

                    $prodvalue['format_name'] = $product->format_name;
                    $prodvalue['guideline_ids'] = $product->guideline_ids;
                    $prodvalue['guideline_names'] = $product->guideline_names; 
                    $prodvalue['composite'] = $product->composite;  

                }
                  
                  return $prodvalue;
               
    }
    
    public function productdetails()
    {
        ini_set('display_errors', 1);
        $result=array();
        $guideline = (isset($_REQUEST['guideline'])) ? $_REQUEST['guideline'] : '';
        $duration = (isset($_REQUEST['duration'])) ? $_REQUEST['duration'] : '0';
        $grace_period = (isset($_REQUEST['grace_period'])) ? $_REQUEST['grace_period'] : '0';
        $ordering = (isset($_REQUEST['ordering'])) ? $_REQUEST['ordering'] : '';
        $subscription_category_id= (isset($_REQUEST['subscription_category'])) ? $_REQUEST['subscription_category'] : '';
        $product_title= (isset($_REQUEST['product_title'])) ? $_REQUEST['product_title'] : '';
        $product_format= (isset($_REQUEST['product_format'])) ? $_REQUEST['product_format'] : '';
        $description= (isset($_REQUEST['description'])) ? $_REQUEST['description'] : '';
        $version= (isset($_REQUEST['version'])) ? $_REQUEST['version'] : '';
        $myob_item_number= (isset($_REQUEST['myob_item_number'])) ? $_REQUEST['myob_item_number'] : '';
        $duration_type= (isset($_REQUEST['duration_type'])) ? $_REQUEST['duration_type'] : '';
        $grace_period_type= (isset($_REQUEST['grace_period_type'])) ? $_REQUEST['grace_period_type'] : '';
        $status= (isset($_REQUEST['status'])) ? $_REQUEST['status'] : '';
        $price=(isset($_REQUEST['price'])) ? $_REQUEST['price'] : '';
        $subscription_id=(isset($_REQUEST['subscription_id'])) ? $_REQUEST['subscription_id'] : '';
        $product_id=(isset($_REQUEST['product_id'])) ? $_REQUEST['product_id'] : '';
        $renewal=(isset($_REQUEST['renew_price'])) ? $_REQUEST['renew_price'] : '';
        $user_id=(isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';
        $currency_id=(isset($_REQUEST['currency_id'])) ? $_REQUEST['currency_id'] : '';
        $region_id=(isset($_REQUEST['region_id'])) ? $_REQUEST['region_id'] : '';
        $num_user=(isset($_REQUEST['num_user'])) ? $_REQUEST['num_user'] : '';
       
            if(($guideline&&$duration&&$ordering&&$subscription_category_id&&$product_title&&$product_format&&$description&&$version&&$myob_item_number&&$duration_type&&$status&&$price&&$renewal&& $num_user&&$currency_id&&$region_id&&$num_user&&$user_id)||($product_format&&$subscription_category_id&&$guideline&&$version&&$status&&$product_title&&$description&&$myob_item_number&&$ordering&&$region_id&&$currency_id&&$num_user&&$price&&$renewal&&$user_id)){
          
                $product_id = $this->saveProductInfor();
                if ($product_id) {
                   
                    $productModel = $this->getProductById($product_id);
                    $guideline_name = $productModel['guideline_names'];
                    $guidelinecount = explode(",", $guideline_name);
                    if ($productModel['composite'] == 1) {
                        $guideline_name = 'All';
                    } else {
                        $guidelinequery = DB::table('cdp_guideline_content')
                            ->where('guideline_id', $productModel['guideline_ids'])
                            ->update(['guideline_publication_status' => $_REQUEST['status'],
        
                            ]);
                    }
                    if ($productModel['composite'] == 1 && $productModel['format_name'] == 'CD offline') {
                        $guideline_name = 'ETG_CD_OFFLINE_ADDON';
                    }
                    if ($productModel['composite'] == 1 && $productModel['format_name'] == 'Mobile app') {
                        $guideline_name = 'ETG_MOBILE_APP_ADDON';
                    }
                    if ($productModel['composite'] == 1 && $productModel['format_name'] == 'Institutional mobile app') {
                        $guideline_name = 'ETG_MOBILE_APP_INST';
                    }
                    if (count($guidelinecount) > 1) {
                        $guideline_id = $this->saveGuideline();
                        $guideline_name = $product_title;
                    }
        
                }
           
              
        
                if ($subscription_id) {
        
                    $updatecdpsub = DB::table('cdp_subscription')->where('subscription_id', $subscription_id)
                        ->update(['publisher_id' => '2',
                            'subscription_category_id`' => $subscription_category_id,
                            'subscription_type_id' => '6',
                            'guideline_title' => $guideline_name,
                            'title' => isset($title) ? $title : $product_title,
                            'description' => $description,
                            'version' => $version,
                            'myob_item_number' => $myob_item_number,
                            'duration' => $duration,
                            'duration_type' => $duration_type,
                            'grace_period' => $grace_period,
                            'grace_period_type' => $grace_period_type,
                            'effective_start_date' =>  date ( 'Y-m-d h:i:s' ),
                            'effective_end_date' =>  date ( 'Y-m-d h:i:s' ),
                            'status' => $status,
                            'lastupdated_by' =>  $user_id,
                            'lastupdated_date' => date('Y-m-d h:i:s'),
        
                        ]);
                            if($updatecdpsub){
                                    $result['msg']='successfully Product updated';
                                    $result['code']='200';
        
                            }
        
                } else {
                    $cdpsub = DB::table('cdp_subscription')->insert(['publisher_id' => '2',
                        'subscription_category_id' =>$subscription_category_id,
                        'subscription_type_id' => '6',
                        'guideline_title' => $guideline_name,
                        'title' => isset($title) ? $title : $product_title,
                        'description' => $description,
                        'version' => $version,
                        'myob_item_number' => $myob_item_number,
                        'duration' => $duration,
                        'duration_type' => $duration_type,
                        'grace_period' => $grace_period,
                        'grace_period_type' => $grace_period_type,
                        'effective_start_date' =>  date ( 'Y-m-d h:i:s' ),
                        'effective_end_date' =>  date ( 'Y-m-d h:i:s' ),
                        'status' => $status,
                        'created_by' => $user_id,
                        'created_date' => date('Y-m-d h:i:s'),
                    ]);
                            if($cdpsub){
                                $result['msg']='successfully Product created';
                                    $result['code']='200';
        
                        }
                }
               
                if( $subscription_id){
                    $subscription_id= $subscription_id;
                }else{
        
                    $subscription_id = DB::getPdo()->lastInsertId();
                }
                if($product_id){
                    $product_id =  $product_id;
        
                }else{
                    $product_id = $this->saveProductInfor();
                }
                if (count($product_id) > 0) {
                    $delete = DB::table('cdp_subscription_product')->where('subscription_id', $subscription_id)->delete();
                }
               
        
                    $product_data = DB::table('cdp_subscription_product')->insert(['product_id' => $product_id,
                        'subscription_id' => $subscription_id,
                        'created_by' =>$user_id,
                        'created_date' => date('Y-m-d h:i:s')]);
        
                    if($price){
        
                        $priceprod=$price;
                    
                    }
                if ($priceprod[0] != '') {
        
                    $delete = DB::table('cdp_subscription_price')->where('subscription_id', $subscription_id)->delete();
        
                    foreach ($priceprod as $key => $price1) {
        
                        $price_data = DB::table('cdp_subscription_price')->insert(['subscription_id' => $subscription_id,
                            'price' => $price1,
                            'renew_price' => $renewal[$key],
                            'user_count' => $num_user[$key],
                            'currency_id' => $currency_id[$key],
                            'region_id' => $region_id[$key],
                            'created_by' => $user_id,
                            'created_date' => date('Y-m-d hi:s'),
                        ]);
                    }
                }
            }
            else{
                        
                    $result['msg']='access denied/some parameters are missing';
                    $result['code']='400';
    
            }

       

        
       
    

                    return json_encode($result);



    }
    public function saveProductInfor()
    {

        $product_title= (isset($_REQUEST['product_title'])) ? $_REQUEST['product_title'] : '';
        $product_format= (isset($_REQUEST['product_format'])) ? $_REQUEST['product_format'] : '';
        $status= (isset($_REQUEST['status'])) ? $_REQUEST['status'] : '';
        $ordering = (isset($_REQUEST['ordering'])) ? $_REQUEST['ordering'] : '';
        $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';
        $guideline = (isset($_REQUEST['guideline'])) ? $_REQUEST['guideline'] : '';
        if($guideline){
            $guideline=$guideline;

        }
        
        if ($guideline && $guideline == 'all') {
            $composite = 1;

        } else {

            $composite = 0
            ;
        }
        if (count($guideline) > 1) {
            $is_set_guideline = '1';

        } else {

            $is_set_guideline = '0';

        }

        if (empty($product_id)) {

            $Productsave = DB::table('cdp_product')->insert(
                ['product_format_id' => $product_format,
                    'product_title' => $product_title,
                    'product_status' =>$status,
                    'ordering' => $ordering,
                    'composite' => $composite,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d h:i:s'),
                ]);
            $product_id = DB::getPdo()->lastInsertId();
        } else {

            $updateProduct = DB::table('cdp_product')
                ->where('product_id', $product_id)
                ->update(['product_format_id' =>  $product_format,
                    'product_title' => $product_title,
                    'product_status' => $status,
                    'composite' => $composite,
                    'ordering' => $ordering,
                    'lastupdated_by' => $user_id,
                    'lastupdated_date' => date('Y-m-d h:i:s'),
                ]);
            $product_id = $product_id;
        }
        if ($guideline) {
         
            if($guideline!='all'){
                $guidelinearray = (explode(",", $guideline));

                $deleteguideline = DB::table('cdp_product_guideline')->where('product_id', $product_id)->delete();
                foreach ($guidelinearray as $gkey => $gvalue) {
                    $productguideline = DB::table('cdp_product_guideline')
                        ->insert(['product_id' => $product_id,
                            'guideline_id' => $gvalue,
                            'is_set_of_guideline' => $is_set_guideline,
    
                        ]);
    
                }

            }
          
        }
        return $product_id;
    }

    public function deleteProduct(Request $request)
    {
        //print_r($_REQUEST);
        //exit;
        $array = array();
        if (!empty($_REQUEST['productid'])) {
            $deleteproduct = DB::table('cdp_product')->where('product_id', $_REQUEST['productid'])
                ->update(['product_status' => '2']);

            if ($deleteproduct) {

                $array['response'] = 'successfully deleted';
            } else {

                $array['response'] = 'not Updated';
            }
        }
        return json_encode($array);
    }


     
    public function getSubscriptionPriceById(){
        $subscriptionprice=array();
        $subscriptionprice1=array();
        if(!empty($_REQUEST['subscription_id'])){

            $subscription_id=$_REQUEST['subscription_id'];
            $getprice=DB::select("select `cdp_subscription_price`.`price`,
                                    `cdp_subscription_price`.`renew_price`,
                                    `cdp_subscription_price`.`region_id`,
                                    `cdp_subscription_price`.`user_count`,
                                    `cdp_subscription_price`.`currency_id`
                                             from `cdp_subscription_price` 
                                         where  `cdp_subscription_price`.`subscription_id`=$subscription_id ");
            
                foreach ($getprice as $val) {
                 $subscriptionprice['price'] = $val->price;
                 $subscriptionprice['renew_price'] = $val->renew_price;
                   $subscriptionprice['region_id'] = $val->region_id;
                   $subscriptionprice['user_count'] = $val->user_count;
                   $subscriptionprice['currency_id'] = $val->currency_id;
                    array_push($subscriptionprice1,$subscriptionprice);
            }
        
        }
        else{
                $subscriptionprice1['msg']='subscriptionId parameter is missing';
                $subscriptionprice1['code']='400';
             }
                return  $subscriptionprice1;

    }
}
