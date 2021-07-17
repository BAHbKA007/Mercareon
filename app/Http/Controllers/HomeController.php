<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        function get_color($zahl){
            if ($zahl > 0 and $zahl <= 33) {
                return "red";
            } elseif ($zahl > 33 and $zahl <= 66) {
                return "yellow";
            } elseif ($zahl > 66 and $zahl <= 100) {
                return "green";
            }
        }

        $ls_gemeldet = DB::select('   SELECT 
                                        COUNT(lieferschein) AS "LS" 
                                    FROM gromas_lieferscheins 
                                    LEFT JOIN buch__positionens ON buch__positionens.ls_nummer = gromas_lieferscheins.lieferschein 
                                    LEFT JOIN buch__kopfs ON buch__kopfs.id = buch__positionens.buch_kopf_id 
                                    WHERE buch__kopfs.buchungsnummer IS NOT NULL')[0];
        $ls_gesamt = DB::select('   SELECT 
                                        COUNT(lieferschein) AS "LS" 
                                    FROM gromas_lieferscheins 
                                    LEFT JOIN buch__positionens ON buch__positionens.ls_nummer = gromas_lieferscheins.lieferschein 
                                    LEFT JOIN buch__kopfs ON buch__kopfs.id = buch__positionens.buch_kopf_id')[0];
        $ls_prozent = ($ls_gesamt->LS == 0) ? round($ls_gemeldet->LS / ($ls_gesamt->LS / 100), 2) : 0;
        $ls_nach_lager = DB::select("SELECT 
                                        REPLACE(REPLACE(gromas_lieferscheins.kundenname, 'Kaufland ', ''), ' ab 03/19', '') AS Kunde,
                                        COUNT(lieferschein) AS 'LS'
                                    FROM gromas_lieferscheins
                                    LEFT JOIN buch__positionens ON buch__positionens.ls_nummer = gromas_lieferscheins.lieferschein
                                    LEFT JOIN buch__kopfs ON buch__kopfs.id = buch__positionens.buch_kopf_id
                                    WHERE buch__kopfs.buchungsnummer IS NULL
                                    GROUP BY gromas_lieferscheins.kundenname
                                    ORDER BY 1");

        # erstellen BarChart Strings mit Kundennamen
        $barchart_kundennamen = "[";
        foreach ($ls_nach_lager as $item) {
            $barchart_kundennamen = $barchart_kundennamen . "\"" . $item->Kunde . "\",";
        };
        $barchart_kundennamen = substr($barchart_kundennamen, 0, -1);
        $barchart_kundennamen = $barchart_kundennamen."]";

        # erstellen BarChart Strings mit Summen
        $barchart_count = "[";
        foreach ($ls_nach_lager as $item) {
            $barchart_count = $barchart_count . "\"" . $item->LS . "\",";
        };
        $barchart_count = substr($barchart_count, 0, -1);
        $barchart_count = $barchart_count."]";

        return view('home', [
            'ls_gesamt' => $ls_gesamt->LS,
            'ls_gemeldet' => $ls_gemeldet->LS,
            'ls_prozent' => $ls_prozent,
            'barchart_kundennamen' => $barchart_kundennamen,
            'barchart_count' => $barchart_count,
            'prozent_color' => get_color($ls_prozent)
        ]);
    }

    public function uebersicht()
    {
        $lieferscheine = DB::table('gromas_lieferscheins')
                ->select('lieferschein', 'kundennummer', 'kundenname', 'bestellnummer', 'liefertag', 'name', 'spedition', 'buchungsnummer')
                ->leftJoin('buch__positionens', 'buch__positionens.ls_nummer', '=', 'gromas_lieferscheins.lieferschein')
                ->leftJoin('buch__kopfs', 'buch__kopfs.id', '=', 'buch__positionens.buch_kopf_id')
                ->orderBy('liefertag', 'desc')
                ->orderBy('lieferschein', 'desc')
                ->paginate(100);
        
        return view('uebersicht', [
            'lieferscheine' => $lieferscheine,

        ]);
    }
}
