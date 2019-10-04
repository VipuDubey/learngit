<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    public function getcontent()
    {
        header('Access-Control-Allow-Origin: *');
        
        $contarray = array();
        $contarray1 = array();
        $appendsql = '';
        if (!empty($_REQUEST['contentidentifier'])) {
            $contentidentifier = $_REQUEST['contentidentifier'];

            $appendsql = " and (`cdp_publisher_static_data`.`STATIC_DATA_KEY` LIKE'%$contentidentifier%')";
        }
        if (!empty($_REQUEST['description'])) {
            $description = $_REQUEST['description'];
            $appendsql = " and (`cdp_publisher_static_data`.`STATIC_DATA_TEXT` LIKE '%$description%')";
        }
        $contentdata = DB::select("select
                                 `cdp_publisher_static_data`.`PUBLISHER_STATIC_DATA_ID`,
                                 `cdp_publisher_static_data`.`PUBLISHER_ID`,
                                 `cdp_publisher_static_data`.`SHORT_DESCRIPTION`,
                                 `cdp_publisher_static_data`.`STATIC_DATA_KEY`,
                                 `cdp_publisher_static_data`.`STATIC_DATA_ORDER`,
                                 `cdp_publisher_static_data`.`STATIC_DATA_TEXT`,
                                 `cdp_publisher_static_data`.`page_url`
                                  from `cdp_publisher_static_data`
                                  where `cdp_publisher_static_data`.`status`='1' $appendsql
                                  order by `cdp_publisher_static_data`.`STATIC_DATA_ORDER` asc ");
        if ($contentdata) {
            foreach ($contentdata as $contentdatas) {
                $contarray['PUBLISHER_STATIC_DATA_ID'] = $contentdatas->PUBLISHER_STATIC_DATA_ID;
                $contarray['PUBLISHER_ID'] = $contentdatas->PUBLISHER_ID;
                $contarray['STATIC_DATA_KEY'] = $contentdatas->STATIC_DATA_KEY;
                $contarray['SHORT_DESCRIPTION'] = $contentdatas->SHORT_DESCRIPTION;
                $contarray['STATIC_DATA_TEXT'] = $contentdatas->STATIC_DATA_TEXT;
                $contarray['page_url'] = $contentdatas->page_url;
                $contarray['PUBLISHER_STATIC_DATA_ID'] = $contentdatas->PUBLISHER_STATIC_DATA_ID;
                array_push($contarray1, $contarray);

            }
           // $contarray1['msg'] = "Success";
           //$contarray1['code'] = 200;

        } else {

            $contarray1['msg'] = "empty result";
            //$contarray1['code'] = 406;

        }

        return json_encode($contarray1);

    }

    public function createcontent()
    {
        header('Access-Control-Allow-Origin: *');
        if(empty($_REQUEST['contentid'])){
            $chkpublisherid="select * from `cdp_publisher_static_data_unpublish where `";
            $insertcontent =DB::table('cdp_publisher_static_data')->insert(
                ['PUBLISHER_ID'=>$_REQUEST['publisherid'],
                    `STATIC_DATA_KEY`=>str_replace ( ' ', '_', strtolower ( trim ( $_REQUEST['shortdescription']) ) ),
                    `SHORT_DESCRIPTION`=>$_REQUEST['shortdescription'],
                    `STATIC_DATA_TEXT`=>$_REQUEST['description'],
                    `page_url`=>$_REQUEST['pageurl']
                ]);
                $contentid = DB::getPdo()->lastInsertId();

        }
        else{
            $updatecontent=DB::table('cdp_publisher_static_data')->where('PUBLISHER_STATIC_DATA_ID',$_REQUEST['contentid'])
            ->update(['PUBLISHER_ID'=>$_REQUEST['publisherid'],
            `STATIC_DATA_KEY`=>str_replace ( ' ', '_', strtolower ( trim ( $_REQUEST['shortdescription']) ) ),
            `SHORT_DESCRIPTION`=>$_REQUEST['shortdescription'],
            `STATIC_DATA_TEXT`=>$_REQUEST['description'],
            `page_url`=>$_REQUEST['pageurl']
            ]);
            $contentid = $_REQUEST['contentid'];


        }
            return $contentid;
    }

        public function deletecontent(){
            header('Access-Control-Allow-Origin: *');
              $array=array();
              $deletecontent=DB::table('cdp_publisher_static_data')->where('PUBLISHER_STATIC_DATA_ID',$_REQUEST['contentid'])
                                           ->update(['status' => '2']);
                                                      
              if($deletecontent)
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

    ?>
