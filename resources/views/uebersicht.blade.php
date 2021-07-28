@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">alle Lieferscheine</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Lieferschein</th>
                                <th scope="col">#</th>
                                <th scope="col">Kunde</th>
                                <th scope="col">Liefertag</th>
                                <th scope="col">Bestellnummer</th>
                                <th scope="col">Spedition</th>
                                <th scope="col">Bearbeiter</th>
                                <th scope="col">Buchungsnummer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lieferscheine as $lieferschein)
                                <tr @if (!$lieferschein->buchungsnummer) style="background-color: #ffebeb" @endif>
                                    <th scope="row">{{ $lieferschein->lieferschein }}</th>
                                    <td>{{ $lieferschein->kundennummer }}</td>
                                    <td>{{ $lieferschein->kundenname }}</td>
                                    <td>{{ date('d.m.Y', strtotime($lieferschein->liefertag)) }}</td>
                                    <td>{{ $lieferschein->bestellnummer }}</td>
                                    <td>{{ $lieferschein->spedition }}</td>
                                    <td>{{ $lieferschein->name }}</td>
                                    <td>{{ $lieferschein->buchungsnummer }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                    {{ $lieferscheine->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
