<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TestController extends Controller{
	
	public function gettest(){
        $users = DB::select('select comment , author from cdp_comment');
       print_r($users);
    }
}
?>