<?php

namespace App\Http\Controllers;

use App\Models\Buch_Kopf;
use App\Models\Buch_Positionen;
use Illuminate\Http\Request;
use Session;

class BuchPositionenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $buch_kopf = new Buch_Kopf;
        $buch_kopf->name = $request->name;
        $buch_kopf->spedition = $request->spedition;
        $buch_kopf->buchungsnummer = $request->buchungsnummer;
        $buch_kopf->save();
        $id = $buch_kopf->id;
        $i = 0;

        foreach ($request->lieferscheine as $item) {
            $buch_pos = new Buch_Positionen;
            $buch_pos->buch_kopf_id = $id;
            $buch_pos->ls_nummer = $item;
            $buch_pos->save();
            $i++;
        }
        
        Session::flash('message', "$i Lieferschein(e) wurden erfolgreich erfasst!"); 
        Session::flash('alert-class', 'alert-success'); 

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Buch_Positionen  $buch_Positionen
     * @return \Illuminate\Http\Response
     */
    public function show(Buch_Positionen $buch_Positionen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Buch_Positionen  $buch_Positionen
     * @return \Illuminate\Http\Response
     */
    public function edit(Buch_Positionen $buch_Positionen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Buch_Positionen  $buch_Positionen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Buch_Positionen $buch_Positionen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Buch_Positionen  $buch_Positionen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Buch_Positionen $buch_Positionen)
    {
        //
    }
}