<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Login;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function getroleList()
    {
        $role1 = array();
        $role2 = array();

        $getrolelist = DB::table('cdp_role')
            ->whereIn('id', array(1, 3, 5, 7, 8))
            ->orderBy('description', 'asc')
            ->get();

        foreach ($getrolelist as $getrolelists) {
            $role1['id'] = $getrolelists->id;
            $role1['description'] = $getrolelists->description;

            array_push($role2, $role1);
        }
        return json_encode($role2);
    }

    public function searchUser()
    {
        $appendSql = '';
        $seracharray = array();
        $search1array = array();
        // print_r($_REQUEST);

        if (!empty($_REQUEST['name'])) {

            $name = $_REQUEST['name'];
            $appendSql .= "  and (`cdp_user`.`given_name` LIKE '%$name%' or `cdp_user`.`surname` LIKE '%$name%')";
        }
        if (!empty($_REQUEST['email'])) {
            $email = $_REQUEST['email'];
            $appendSql .= " and `cdp_user`.`email`LIKE '$email%'";
        }
        if (!empty($_REQUEST['role'])) {
            $role = $_REQUEST['role'];
            $appendSql .= " and `cdp_user_role`.`role_id`='$role'";
        }
        if (!empty($_REQUEST['status'])) {

            $appendSql .= " and `cdp_user`.`account_status`='1'";
        }
        $searchuser = DB::select("select `cdp_user`.`id`,
                         `cdp_user`.`institution_id`,
                         `cdp_user`.`email`,
                         `cdp_user`.`title`,
                         `cdp_user`.`password`,
                         `cdp_user`.`occupation`,
                         `cdp_user`.`given_name`,
                         `cdp_user`.`surname` ,
                         `cdp_user`.`account_status` ,
                         `cdp_user_role`.`role_id`,
                         `cdp_role`.`name` As `role_code`,
                         `cdp_role`.`description`As `role_name`
                         from `cdp_user` join `cdp_user_role`
                          on `cdp_user_role`.`user_id`=`cdp_user`.`id` 
                          join `cdp_role` on `cdp_role`.`id`=`cdp_user_role`.`role_id`
                           where 1  $appendSql");
        if ($searchuser) {
            foreach ($searchuser as $searchusers) {
                $searcharray['id'] = $searchusers->id;
                $searcharray['institution_id'] = $searchusers->institution_id;
                $searcharray['given_name'] = $searchusers->given_name;
                $searcharray['surname'] = $searchusers->surname;
                $searcharray['email'] = $searchusers->email;
                $searcharray['role_code'] = $searchusers->role_code;
                $searcharray['role_id'] = $searchusers->role_id;
                $searcharray['role_name'] = $searchusers->role_name;
                $searcharray['title'] = $searchusers->title;
                $searcharray['status'] = $searchusers->account_status;
                array_push($search1array, $searcharray);
            }
            $searcharray['msg'] = "Success";
            $searcharray['code'] = 200;
        } else {

            $searcharray['msg'] = "empty Result";
            $searcharray['code'] = 406;
        }


        return json_encode($search1array);
    }

    public function createuser()
    {
        $sendarray = array();
		//print_r($_REQUEST);
		$title=(isset($_REQUEST['title'])) ? $_REQUEST['title'] : '';
		$name=(isset($_REQUEST['name'])) ? $_REQUEST['name'] : '';
		$lastname=(isset($_REQUEST['lastname'])) ? $_REQUEST['lastname'] : '';
		$email=(isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
		$status=(isset($_REQUEST['status'])) ? $_REQUEST['status'] : '';
		$loginid=(isset($_REQUEST['loginid'])) ? $_REQUEST['loginid'] : '';
		$role=(isset($_REQUEST['role'])) ? $_REQUEST['role'] : '';
		$password=(isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';
		$email=(isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
		$institute_id=(isset($_REQUEST['institute_id'])) ? $_REQUEST['institute_id'] : '';
       
       
	   
       
	 
        if ($email) {
            $checkemail = DB::select("select `cdp_user`.`email` from `cdp_user` where `email`='$email'");
		}
            if($checkemail) {

                $sendarray['msg'] = 'Email already exists';
            } else {
                $userid = strtolower(substr((substr(trim($name), 0, 1)) . trim($lastname), 0, 15));
                $checkuser = DB::select("select `cdp_user`.`id`  from `cdp_user`   where `cdp_user`.`id`='$userid'");
                $actual_user_id = $userid;
                $next_trim_point = strlen($userid) - 1;
                $check = false;
                $index = 1;

                if ($checkuser) {
                    $userid = (chop($userid, $index - 1)) . $index++;

                    if (strlen($userid) > 15) {

                        $userid = substr($actual_user_id, 0, $next_trim_point--);
                        $index = 1;
                        $check = true;
                    }
                    $Userid = $userid;
                    $createuser = DB::table('cdp_user')->insert([
                        'title' => $title,
                        'given_name' => $name,
                        'surname' => $lastname,
                        'email' => $email,
                        'account_status' => $status,
                        'id' => $Userid,
                        'username' => $email,
                        'password' => $password,
                        'created_by' => $loginid,
                        'created_date' => date("Y-m-d H:i:s")
                    ]);
                    $id = DB::getPdo()->lastInsertId();

                    if ($createuser) {
                        if ($_REQUEST['role'] != '0') {
                            $insertroleid = DB::table('cdp_user_role')
                                ->insert([
                                    'role_id' => $role,
                                    'user_id' => $userid
                                ]);
                        }
                    }
                    $sendarray['msg'] = 'user created succesfully';
                } 
            }
        


        /* $actual_user_id = $userid;
            $next_trim_point = strlen($userid) - 1;
            $check = false;
            $index = 1;
            do {
                $check = $this->checkDuplicateUserid($userid);

                if ($check) {
                    $userid = (chop($userid, $index - 1)) . $index++;
                    continue;
                }

                $check = $this->checkDuplicateUseridInMyob($userid);

                if ($check) {
                    $userid = (chop($userid, $index - 1)) . $index++;
                }

                if (strlen($userid) > 15) {
                    $userid = substr($actual_user_id, 0, $next_trim_point--);
                    $index = 1;
                    $check = true;
                }
            } while ($check);*/


        if (!empty($_REQUEST['userid'])) {
            $userid =  $_REQUEST['userid'];
            $createuser = DB::table('cdp_user')
                ->where('id', $userid)
                ->update([
                    'title' => $title,
                    'given_name' => $name,
                    'surname' => $lastname,
                    'email' => $email,
                    'username' => $email,
                    'id' => $userid,
                    'account_status' => $status,
                    'lastupdated_by' => $loginid,

                    'lastupdated_date' => date("Y-m-d H:i:s")
                ]);

            if ($createuser) {
                if ($_REQUEST['role'] != '0') {
                    $updateroleid = DB::table('cdp_user_role')
                        ->where('user_id', '$userid')
                        ->update(['role_id' => '$role']);
                }
            }
            $sendarray['msg'] = 'successfully updated';
        }

        if ($institute_id) {
            if (!is_array($institute_id)) {
                $institute_id = array($institute_id);
            }
            $delete = DB::table('cdp_institution_account_user')->where('userid', $id)->delete();
            foreach ($institute_id as $lins) {
                $ins_usr_data     = DB::table('cdp_institution_account_user')
                    ->insert([
                        'institution_id' => $institute_id,
                        'user_id' => $userid


                    ]);
            }
            $sendarray['msg'] = 'institution successfully inserted';
        }

        return json_encode($sendarray);
    }

    public function checkemail()
    {
        $emailarray = array();
        $email = $_REQUEST['email'];
        $checkemail = DB::select("select email from cdp_user where email='$email'");
        // print_r($checkemail);
        if ($checkemail) {

            $emailarray['email'] = $checkemail;
            $emailarray['msg'] = 'success';
            $emailarray['code'] = '200';
        } else {
            $emailarray['msg'] = 'empty result';
            $emailarray['code'] = '406';
        }
        return json_encode($emailarray);
    }

    public function deleteuser()
    {
        $current_user = $_REQUEST['currentuser'];
        $array = array();
        $deleteuser = DB::table('cdp_user')->where('id', $_REQUEST['userid'])
            ->update([
                'status' => 'account_status',
                'lastupdated_date' => date('Y-m-d h:i:s'),
                'lastupdated_by' => $current_user

            ]);

        if ($deleteuser) {

            $array['response'] = 'successfully deleted';
        } else {

            $array['response'] = 'not Updated';
        }
        return json_encode($array);
    }
}