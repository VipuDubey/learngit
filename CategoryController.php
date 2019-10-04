<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function categoryList()
    {
        header('Access-Control-Allow-Origin: *');
       
        $appendSql = '';
        if (empty($_REQUEST)) {

            $appendSql .= '';

        }
        if(!empty($_REQUEST['product_format_id'])){
                $product_format_id=$_REQUEST['product_format_id'];
    $appendSql .= "where `cdp_product_category_mapping`.product_format_id IN('$product_format_id')";

        }
        $category = array();
        $categorylist = array();
        $Category = DB::select("select 
                        `cdp_subscription_category_mst`.`subscription_category_id`,
                         `cdp_subscription_category_mst`.`subscription_category_name`, 
                         `cdp_subscription_category_mst`.`display_status`
                          from `cdp_subscription_category_mst` 
                          left join `cdp_product_category_mapping` 
                          ON `cdp_product_category_mapping`.`subscription_category_id`=`cdp_subscription_category_mst`.`subscription_category_id`
                          $appendSql order by `cdp_subscription_category_mst`.`subscription_category_name` asc");
           

        foreach ($Category as $Categorys) {
            $catgory['Subscription categoryId'] = $Categorys->subscription_category_id;
            $catgory['Subscription categoryName'] = $Categorys->subscription_category_name;
          
            array_push($categorylist, $catgory);
        }
        return json_encode($categorylist);
    }
}
