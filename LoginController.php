<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Auth\PasswordController;
use Validator;
use App\Login;
use Session;
use Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SendMailable;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;

class LoginController extends Controller
{

  public function generateRandomString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
  public function postlogin(Request $request)
  {
    $requestarr = $request->all(); 
    $loginarray = array();
    $loginstatus = array();   
    $username = $requestarr['root'];
    $password = $requestarr['root'];
    if($username&&$password){
        $hashPassword = $this->hashPassword ( $password );
        //echo $hashPassword;die();
        $Login = DB::select("select `cdp_user`.`id`, 
            `cdp_user`.`created_date`,
            `cdp_user`.`created_by`,
            `cdp_user`.`occupation`,
            CONCAT(`cdp_user`.`given_name`,`cdp_user`.`surname`) AS fullname 
            from `cdp_user` where `cdp_user`.`email`='" . $username . "' and  `cdp_user`.`password`='" . $hashPassword . "'");
        if (empty($Login)) {
          $loginarray['msg'] = 'invalid username/password';
          $loginarray['code'] = 400;
        } else {
          foreach ($Login as $Logins) {
            $loginarray['name'] = $Logins->fullname;
            $loginarray['Joining Date'] = date('d M y', strtotime($Logins->created_date));
            $loginarray['Profile'] = $Logins->occupation;
            $loginarray['id'] = $Logins->id;
            $loginarray['msg'] = 'Success';
            $loginarray['code'] = 200;

            $loginarray['accesstoken'] = $this->generateRandomString();

          }
        }
    } else{
      $loginarray['msg'] = 'Required field must not be blank!';
      $loginarray['code'] = 400;
    }
    array_push($loginstatus, $loginarray);
    return json_encode($loginstatus);
    
  }
  public function forgotPassword(Request $request){
      $requestarr = $request->all();
      $loginarray = array();
      $loginstatus = array();  
      $username = $requestarr['username'];
      if($username){
          $usernum = DB::table('cdp_user')->where('email', '=', $username)->count();
          if($usernum == 1){
                $random_password = substr ( str_shuffle ( 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ), 0, 10 );
                $hashPassword = $this->hashPassword ( $random_password );
                $updateData ['password'] = $hashPassword;
                $result = DB::table('cdp_user')->where('email', '=', $username)->update($updateData);
                if($result){
                    echo "Password: ".$random_password;die();
                    //$subject ="Forgot Password";
                    $email = $username;
                    //$from="rakeshkrc.nav@gmail.com";
                    //$message = "Password:  ".$random_password;
                    //$headers = "From:".$from;
                    //mail($email,$subject,$message,$headers);
                    Mail::to($email)->send(new SendMailable($random_password));
                    $loginarray['msg'] = 'Please check your email.';
                    $loginarray['code'] = 200;
                }

          }else{
               $loginarray['msg'] = 'Unauthorized access!';
               $loginarray['code'] = 400;
              }
          
      } else{
      $loginarray['msg'] = 'Required field must not be blank!';
      $loginarray['code'] = 400;
    }
    array_push($loginstatus, $loginarray);
    return json_encode($loginstatus);
  }
  public function hashPassword ( $password ){
        $salt = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXX';        
        return md5 ( $salt . $password );       

    }
  
}