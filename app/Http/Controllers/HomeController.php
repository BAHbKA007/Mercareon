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
    public function index(Request $request)
    {
        $von = isset($request->von) ? $request->von : '1970-01-01';
        $bis = isset($request->bis) ? $request->bis : '2999-01-01';

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
                                    WHERE buch__kopfs.buchungsnummer IS NOT NULL
                                    AND gromas_lieferscheins.liefertag BETWEEN :von AND :bis',  ["von" => $von, "bis" => $bis])[0];
        $ls_gesamt = DB::select('   SELECT 
                                        COUNT(lieferschein) AS "LS" 
                                    FROM gromas_lieferscheins 
                                    LEFT JOIN buch__positionens ON buch__positionens.ls_nummer = gromas_lieferscheins.lieferschein 
                                    LEFT JOIN buch__kopfs ON buch__kopfs.id = buch__positionens.buch_kopf_id
                                    WHERE gromas_lieferscheins.liefertag BETWEEN :von AND :bis',  ["von" => $von, "bis" => $bis])[0];
        $ls_prozent = ($ls_gesamt->LS != 0) ? round($ls_gemeldet->LS / ($ls_gesamt->LS / 100), 2) : 0;
        $ls_nach_lager = DB::select("SELECT 
                                        REPLACE(REPLACE(gromas_lieferscheins.kundenname, 'Kaufland ', ''), ' ab 03/19', '') AS Kunde,
                                        COUNT(lieferschein) AS 'LS'
                                    FROM gromas_lieferscheins
                                    LEFT JOIN buch__positionens ON buch__positionens.ls_nummer = gromas_lieferscheins.lieferschein
                                    LEFT JOIN buch__kopfs ON buch__kopfs.id = buch__positionens.buch_kopf_id
                                    WHERE buch__kopfs.buchungsnummer IS NULL
                                    AND gromas_lieferscheins.liefertag BETWEEN :von AND :bis
                                    GROUP BY gromas_lieferscheins.kundenname
                                    ORDER BY 1",  ["von" => $von, "bis" => $bis]);

        $prozentual_gemeldet_nach_direktlieferant = DB::select("SELECT 
                                                                    *,
                                                                    ROUND( 100 - (temp.anzahl / (temp.gesamt / 100)), 2) AS Prozent
                                                                FROM
                                                                (SELECT 
                                                                    CASE WHEN 
                                                                        LS.direktlieferant_name = '' 
                                                                        THEN 'Gemüsering' ELSE LS.direktlieferant_name 
                                                                    END AS lieferant,
                                                                    LS.direktlieferant_nummer AS Direktnummer,
                                                                    COUNT(LS.direktlieferant_name) AS gesamt,
                                                                        (SELECT COUNT(gromas_lieferscheins.direktlieferant_name) 
                                                                        FROM gromas_lieferscheins 
                                                                        LEFT JOIN buch__positionens ON buch__positionens.ls_nummer = gromas_lieferscheins.lieferschein
                                                                        LEFT JOIN buch__kopfs ON buch__kopfs.id = buch__positionens.buch_kopf_id
                                                                        WHERE gromas_lieferscheins.direktlieferant_nummer = Direktnummer AND buch__kopfs.buchungsnummer IS NULL
                                                                        AND gromas_lieferscheins.liefertag BETWEEN :von AND :bis) AS anzahl
                                                                FROM gromas_lieferscheins AS LS
                                                                LEFT JOIN buch__positionens ON buch__positionens.ls_nummer = LS.lieferschein
                                                                LEFT JOIN buch__kopfs ON buch__kopfs.id = buch__positionens.buch_kopf_id
                                                                WHERE LS.liefertag BETWEEN :von2 AND :bis2
                                                                GROUP BY lieferant, Direktnummer) temp
                                                                ORDER BY Prozent DESC",  ["von" => $von, "bis" => $bis, "von2" => $von, "bis2" => $bis]);


        # erstellen BarChart Strings mit Namen der Direktlieferanten
        $barchart_direktlieferant_namen = "[";
        foreach ($prozentual_gemeldet_nach_direktlieferant as $item) {
            $barchart_direktlieferant_namen = $barchart_direktlieferant_namen . "\"" . $item->lieferant . "\",";
        };
        $barchart_direktlieferant_namen = substr($barchart_direktlieferant_namen, 0, -1);
        $barchart_direktlieferant_namen = $barchart_direktlieferant_namen."]";

        # erstellen BarChart Strings mit Prozenten der Direktlieferanten
        $barchart_direktlieferant_prozent = "[";
        foreach ($prozentual_gemeldet_nach_direktlieferant as $item) {
            $barchart_direktlieferant_prozent = $barchart_direktlieferant_prozent . "\"" . $item->Prozent . "\",";
        };
        $barchart_direktlieferant_prozent = substr($barchart_direktlieferant_prozent, 0, -1);
        $barchart_direktlieferant_prozent = $barchart_direktlieferant_prozent."]";

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
            'prozent_color' => get_color($ls_prozent),
            'barchart_direktlieferant_namen' => $barchart_direktlieferant_namen,
            'barchart_direktlieferant_prozent' => $barchart_direktlieferant_prozent,
            'von' => $von,
            'bis' => $bis,
            'prozentual_gemeldet_nach_direktlieferant' => $prozentual_gemeldet_nach_direktlieferant
        ]);
    }

    public function uebersicht()
    {
        $lieferscheine = DB::table('gromas_lieferscheins')
                ->select('lieferschein', 'kundennummer', 'kundenname', 'bestellnummer', 'liefertag', 'name', 'spedition', 'buchungsnummer', 'direktlieferant_nummer', 'direktlieferant_name')
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
