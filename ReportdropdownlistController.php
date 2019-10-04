<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class ReportdropdownlistController extends Controller
{
    public function getmarketingropdown()
    {
        $reportarray=array();
        $reportarray1=array();

        $reportdropdown=DB::select("select * from  `cdp_marketing_dropdown` where status='1'");
                foreach($reportdropdown as $reportdropdown1){
                        $reportarray['report_option']=$reportdropdown1->report_option;
                        $reportarray['report_name']=$reportdropdown1->report_name;
                            array_push($reportarray1,$reportarray);
                }
                    $reportarray1['msg']='success';
                    return json_encode($reportarray1);
    }

  
    public function customersegment(){

        $customersegmentarray=array();
        $customersegmentarray1=array();

        $segmentdropdown=DB::select("select * from `cdp_customer_segment_dropdown` where status='1'");
            foreach($segmentdropdown as $segmentdata){

                $customersegmentarray['customer_segment_option']=$segmentdata->customer_segment_option;
                $customersegmentarray['customer_segment_name']=$segmentdata->customer_segment_name;

                    array_push($customersegmentarray1,$customersegmentarray);
            }

               

                    return json_encode($customersegmentarray1);
    }

    public function yeardropdown(){
        $yeararray=array();
        $yeararray1=array();
        $yearlist=DB::select("select `cdp_year_list`.`year_name`,`cdp_year_list`.`status` from `cdp_year_list` where `cdp_year_list`.`status`='1'");
        
                foreach($yearlist as $yeardata){
                  
                  
                    $yeararray['year_name'] = $yeardata->year_name;
                    $yeararray['status'] = $yeardata->status;
                            array_push($yeararray1, $yeararray);
                }
                    return json_encode($yeararray1);

    }
}
