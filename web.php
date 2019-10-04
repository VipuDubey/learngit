<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::match(array('GET', 'POST'), '/getkey', function() { return 'Hello World'; });
Route::match(array('GET', 'POST'), '/getkey/{key_code}','SchoolInfoController@getkey');
//Route::match(array('GET', 'POST'),'/getsearch/{school_type}/{grade_type}/{board_type}/{location}','SchoolInfoController@fetchsearchdeatails');
Route::get('getkeyforhomepage/{key_code}','SchoolInfoController@getkeyforhomepage');
Route::match(array('GET', 'POST'),'/getsearch/','SchoolInfoController@fetchsearchdeatails');
Route::match(array('GET', 'POST'),'/getlocation/','SchoolInfoController@getlocation');
Route::match(array('GET', 'POST'),'/getcity/','SchoolInfoController@getcity');
Route::match(array('GET', 'POST'),'/getpopularschool/','SchoolInfoController@getpopularschool');
Route::match(array('GET', 'POST'),'/getfeaturelist/','SchoolInfoController@getfeaturelist');
Route::match(array('GET', 'POST'),'/gettestimonial/','SchoolInfoController@gettestimonial');
Route::match(array('GET', 'POST'),'/schooldetails/','SchoolInfoController@schooldetails');
Route::match(array('GET', 'POST'),'/sendemail/','SchoolInfoController@sendemail');
Route::match(array('GET', 'POST'),'/getlockey/','SchoolInfoController@getlockey');
Route::match(array('GET', 'POST'),'/getaddlink/','SchoolInfoController@getaddlink');
Route::match(array('GET', 'POST'),'/adduserinfo/','SchoolInfoController@adduserinfo');
Route::match(array('GET','POST'), '/test/', 'TestController@search');
Route::match(array('GET','POST'), '/Country/', 'CountryController@search');
Route::match(array('GET', 'POST'), '/institute/', 'InstituteController@getinstitute');
Route::match(array('GET', 'POST'), '', 'OrderController@getorder'); 
