<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller {

    public function getnotificationlist(){
        header('Access-Control-Allow-Origin: *');
        $notificationarray=array();
        $notificationarray1=array();
        $Notification=DB::select("SELECT `u`.`id` AS `id`, 
                                `u`.`title` AS `title`,
                                `u`.`institution_id` AS `institution_id`, 
                                `u`.`account_status` AS `account_status`,
                                `u`.`email` AS `email`, 
                                `u`.`occupation` AS `occupation`,
                                `u`.`parent_user` AS `parent_user`, 
                                `u`.`given_name` AS `given_name`,
                                `u`.`surname` AS `surname`,
                                `u`.`password_hint` AS `password_hint`,
                                `u`.`phone_number` AS `phone_number`,
                                `u`.`postal_code` AS `postal_code`, 
                                `u`.`street_address` AS `street_address`, 
                                Group_Concat(DISTINCT ur.role_id) AS `role_id`,
                                Group_Concat(DISTINCT user_sub.subscription_id) AS `subscription_id`,
                                Group_Concat(DISTINCT subs.subscription_category_id) AS `subscription_category_id`,
                                Group_Concat(DISTINCT r.name) AS `role_code`,
                                Group_Concat(DISTINCT r.description) AS `role_name`, 
                                Group_Concat(DISTINCT order.transaction_id) AS `transaction_id`
                                FROM `cdp_user` AS `u`
                                 LEFT JOIN `cdp_user_role` AS `ur`
                                ON `ur`.`user_id` = `u`.`id`
                                LEFT JOIN `cdp_user_subscription` AS `user_sub`
                                 ON `u`.`id` = `user_sub`.`user_id` 
                                 LEFT JOIN `cdp_subscription` AS `subs`
                                  ON `user_sub`.`subscription_id` = `subs`.`subscription_id` 
                                  LEFT JOIN `cdp_role` AS `r` ON `r`.`id` = `ur`.`role_id` LEFT JOIN `cdp_order_transaction_master` AS `order` ON `u`.`id` = `order`.`user_id` WHERE ur.role_id not in (1,3,5,7) GROUP BY `u`.`id` ORDER BY `u`.`created_date` DESC");
                                
    }



}





?>