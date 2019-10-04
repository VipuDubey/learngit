<?php

namespace App\Http\Controllers;

use App\Region;

class RegionController extends Controller
{

    public function getRegionSelect()
    {

        $regiondata = array();
        $regiondatas = array();

        $Region = Region::select('region_id', 'region_name')
            ->where('display_status', '1')
            ->orderBy('region_name', 'asc')
            ->get();
        foreach ($Region as $Regions) {
            $regiondata['regionId'] = $Regions->region_id;
            $regiondata['regionName'] = $Regions->region_name;
            $regiondatas = array();
            array_push($regiondatas, $regiondata);
        }
        return json_encode($regiondatas);
    }

}
