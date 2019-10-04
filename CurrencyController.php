<?php

namespace App\Http\Controllers;

use App\Currency;

class CurrencyController extends Controller
{

    public function getCurrency()
    {

        $currencydata = array();
        $currencydatas = array();

        $Currency = Currency::select('currency_id', 'currency_name')
            ->where('display_status', '1')
            ->orderBy('currency_name', 'asc')

            ->get();
        foreach ($Currency as $Currencys) {
            $currencydata['currencyId'] = $Currencys->currency_id;
            $currencydata['currencyName'] = $Currencys->currency_name;

            $currencydata['currencyName'] = $Currencys->currency_name;
            array_push($currencydatas, $currencydata);
        }
        return json_encode($currencydatas);
    }

}
