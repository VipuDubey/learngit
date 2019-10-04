<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParcelController extends Controller{

    public function getparcels(){

        $parcelarray=array();
        $parcelarray1=array();
        $appendsql='';
      
        if(!empty($_REQUEST['parcel_low_range'])){
            $parcel_low_range=$_REQUEST['parcel_low_range'];
            $appendsql .=" and `cdp_postal_parcel_dimension`.`parcel_low_range`='$parcel_low_range'";


        }
        if(!empty($_REQUEST['parcel_high_range'])){
            $parcel_high_range=$_REQUEST['parcel_high_range'];
            $appendsql .=" and `cdp_postal_parcel_dimension`.`parcel_high_range`='$parcel_high_range'";


        }

        if(!empty($_REQUEST['servicetype'])){
            $servicetype=$_REQUEST['servicetype'];
            $appendsql .=" and `cdp_postal_parcel_services`.`is_domestic`='$servicetype'";


        }


        $parcellisting=DB::select("select
                                    `cdp_postal_parcel_dimension`.`parcel_low_range`,
                                    `cdp_postal_parcel_dimension`.`parcel_high_range`,
                                    `cdp_postal_parcel_dimension`.`parcel_length`,
                                    `cdp_postal_parcel_dimension`.`parcel_width`,
                                    `cdp_postal_parcel_dimension`.`parcel_height`,
                                    `cdp_postal_parcel_dimension`.`postal_service_id`,
                                    `cdp_postal_parcel_dimension`.`created_date`,
                                    `cdp_postal_parcel_dimension`.`parcel_id`,
                                    `cdp_postal_parcel_services`.`service_description`,
                                    `cdp_postal_parcel_services`.`is_domestic`,
                                    `cdp_postal_parcel_services`.`maximum_weight`,
                                    `cdp_postal_parcel_services`.`handling_charges`
                                    from `cdp_postal_parcel_dimension`
                                    join  `cdp_postal_parcel_services` 
                                    ON `cdp_postal_parcel_services`.`postal_service_id`=`cdp_postal_parcel_dimension`.`postal_service_id`
                                    where 1 $appendsql
                                    ");
        if($parcellisting){


            foreach($parcellisting as $parcellisting1){
                $parcelarray['parcelid'] =$parcellisting1->parcel_id;
                $parcelarray['parcel_low_range'] =$parcellisting1->parcel_low_range;
                $parcelarray['parcel_high_range'] =$parcellisting1->parcel_high_range;
                $parcelarray['parcel_length'] =$parcellisting1->parcel_length;
                $parcelarray['parcel_width'] =$parcellisting1->parcel_width;
                $parcelarray['parcel_height'] =$parcellisting1->parcel_height;
                $parcelarray['created_date'] =$parcellisting1->created_date;
                $parcelarray['postal_service_id'] =$parcellisting1->postal_service_id;
                $parcelarray['service_name'] =$parcellisting1->service_description;
                $parcelarray['service_name'] =$parcellisting1->service_description;
                $parcelarray['service_type'] =$parcellisting1->is_domestic;
                $parcelarray['maximum_weight'] =$parcellisting1->maximum_weight;
                $parcelarray['handling_charges'] =$parcellisting1->handling_charges;
                array_push($parcelarray1,$parcelarray);
            }      
            //$parcelarray1['msg']='success';
                }else{

                    $parcelarray1['msg']='empty result';
                }
                            return json_encode($parcelarray1);
            
            
                            

            }


            public function createparcel(){
                $parceldata=array();
               $range=explode('-',$_REQUEST['search_status']);
                if(empty($_REQUEST['parcelid'])){
                    $insertmessage=DB::table('cdp_postal_parcel_dimension')
                    ->insert([
                        'postal_service_id'=>$_REQUEST['postal_service_id'],
                        'parcel_low_range'=>$range[0],
                        'parcel_high_range'=>$range[1],
                        'parcel_length'=>$_REQUEST['parcel_length'],
                        'parcel_height'=>$_REQUEST['parcel_height'],
                        'parcel_width'=>$_REQUEST['parcel_width']
                    
                    
                    ]);
                    $parcelid = DB::getPdo()->lastInsertId();;
                        $parceldata['msg']="successfully inserted";
                }
                else{

                    $updatemessage=DB::table('cdp_postal_parcel_dimension')->where('parcel_id',$_REQUEST['parcelid'])
                    ->update( [
                        'postal_service_id'=>$_REQUEST['postal_service_id'],
                        'parcel_low_range'=>$range[0],
                        'parcel_high_range'=>$range[1],
                        'parcel_length'=>$_REQUEST['parcel_length'],
                        'parcel_height'=>$_REQUEST['parcel_height'],
                        'parcel_width'=>$_REQUEST['parcel_width']
                    
                    
                    ]);
                    $parceldata['msg']="successfullyupdated";
                }
               
                    return json_encode( $parceldata);

            }

            public function deleteparcel(){
                if((!empty($_REQUEST['parcelid']))){
                $array=array();
                $deleteparcel=DB::table('cdp_postal_parcel_dimension')->where('parcel_id',$_REQUEST['parcelid'])
                                             ->update(['status' => '0']);
                                                        
                    if($deleteparcel)
                    {
            
                            $array['response']='successfully deleted';
                    }else{
            
                        $array['response']='not Updated';
                }
            }else{
                    $array['code']='500';
                    $array['response']='something went wrong !';

            }
    }

    } 


?>