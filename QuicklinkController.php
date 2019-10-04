<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class QuicklinkController extends Controller
{
    public function searchQuicklinks()
    {
        $linkarray = array();
        $linkarray1 = array();
        $appendsql = '';
        if (!empty($_REQUEST['linkname'])) {
            $linkname = $_REQUEST['linkname'];
            $appendsql .= " and `cdp_quicklinks`.`link_name` LIKE '%$linkname%'";
        }
        if (!empty($_REQUEST['status'])) {
            $status=$_REQUEST['status'];
            $appendsql .= " and `cdp_quicklinks`.`link_status`='$status'";
        }
        $quicklink = DB::select("select `cdp_quicklinks` . `link_name`,
                                `cdp_quicklinks`.`quicklink_id`,
                                `cdp_quicklinks` . `link_path`,
                                `cdp_quicklinks` . `link_url`,
                                `cdp_quicklinks` . `created_date`,
                                `cdp_quicklinks` . `link_status`
                                from `cdp_quicklinks` where 1 $appendsql"
        );
        if ($quicklink) {

            foreach ($quicklink as $quicklinks) {
                $linkarray['linkid'] = $quicklinks->quicklink_id;
                $linkarray['linkname'] = $quicklinks->link_name;
                $linkarray['linkurl'] = $quicklinks->link_url;
                $linkarray['linkpath'] = $quicklinks->link_path;
                $linkarray['createddate'] = $quicklinks->created_date;
                $linkarray['linkstatus'] = $quicklinks->link_status;
                array_push($linkarray1, $linkarray);
            }
        } else {
            $linkarray1['msg'] = 'empty result';
        }
        return json_encode($linkarray1);

    }

    public function createquickLink(){
        $messagearray=array();
        if(empty($_REQUEST['quicklinkid'])){

            $insertquicklink=DB::table('cdp_quicklinks')->insert(['link_name'=>$_REQUEST['linkname'],
            'link_url'=>$_REQUEST['linkurl'],
            'link_path'=>$_REQUEST['linkpath'],
            'created_date'=>date ( "Y-m-d H:i:s" ),
            'link_status'=>$_REQUEST['status'],

        
        ]);
        $quicklinkid = DB::getPdo()->lastInsertId();;
        $messagearray['msg']='inserted successfully';
        }else{

            $updatequicklink=DB::table('cdp_quicklinks')->where('quicklink_id',$_REQUEST['quicklinkid'])
                                ->update(['link_name'=>$_REQUEST['linkname'],
                                'link_url'=>$_REQUEST['linkurl'],
                                'link_path'=>$_REQUEST['linkpath'],
                                'created_date'=>date ( "Y-m-d H:i:s" ),
                                'link_status'=>$_REQUEST['status'],]);

                                $quicklinkid=$_REQUEST['quicklinkid'];
                                $messagearray['msg']='inserted successfully';
        }
        return json_encode( $messagearray);
        
    }
    public function deletequickLink(){
        $array=array();
              $deletequicklink=DB::table('cdp_quicklinks')->where('quicklink_id',$_REQUEST['quicklinkid'])
                                           ->update(['link_status' => '2']);
                                                      
              if($deletequicklink)
              {
      
                      $array['response']='successfully deleted';
                      $array['code']='200';
              }else{
      
                  $array['response']='not Updated';
                  $array['code']='406';
              }
                  return json_encode($array);
    }
}
