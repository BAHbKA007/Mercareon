<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\gromas_lieferschein;
use Illuminate\Support\Facades\DB;

class GromasLieferscheinController extends Controller
{
    public function push_to_database(Request $request)
    {
        $gromas_data_array = json_decode($request[0]);
        $i = 0;

        foreach ($gromas_data_array as $item){
            $lieferschein = gromas_lieferschein::find($item->lieferschein);

            if (is_null($lieferschein)){
                $new_lieferschein = new gromas_lieferschein;
                $new_lieferschein->lieferschein = $item->lieferschein;
                $new_lieferschein->kundennummer = $item->kundennummer;
                $new_lieferschein->kundenname = $item->kundenname;
                $new_lieferschein->bestellnummer = $item->bestellnummer;
                $new_lieferschein->liefertag = $item->liefertag;
                $new_lieferschein->direktlieferant_nummer = $item->direktlieferant;
                $new_lieferschein->direktlieferant_name = $item->direktlieferant_nr;
                $new_lieferschein->save();
            } else {
                $lieferschein->kundennummer = $item->kundennummer;
                $lieferschein->kundenname = $item->kundenname;
                $lieferschein->bestellnummer = $item->bestellnummer;
                $lieferschein->liefertag = $item->liefertag;
                $lieferschein->direktlieferant_nummer = $item->direktlieferant;
                $lieferschein->direktlieferant_name = $item->direktlieferant_nr;
                $lieferschein->save();
            }
            
            $i++;
        }

        return "Vorgänge verarbeitet: $i";
    }
}
