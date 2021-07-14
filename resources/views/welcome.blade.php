@extends('layouts.app')

@section('content')
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Informationen erfassen</div>

                <div class="card-body">
                    <form method="POST" action="/">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Name">Ihr Name:</label>
                                <input type="text" class="form-control" id="Name" name="name" placeholder="Max" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="spedition">Spedition/Firma:</label>
                                <input type="text" class="form-control" id="Spedition" name="spedition" placeholder="..." required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Mercareon Buchungsnummer:</label>
                            <input type="number" class="form-control" id="buchungsnummer" name="buchungsnummer" placeholder="..." required>
                        </div>
                        <br>
                        <hr>
                        <br>
                        <div class="form-group" id="add_to_me">
                            <label for="lieferschein">Gemüsering Lieferscheine:</label>
                            <div class="input-group">
                                <input required type="number" class="form-control" min="100000" max="999999" placeholder="Lieferscheinnummer" aria-label="Lieferscheinnummer" name="lieferscheine[]" aria-describedby="basic-addon2">
                            </div>
                        </div>
                        <div style="margin-top: 20px">
                            <button style="float: right;" class="btn btn-outline-success" type="button" onclick="addCode(); return false;">hinzufügen</button>
                        </div>
                        <br><br>
                        <button type="submit" class="btn btn-primary">absenden</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var i = 0;
    function addCode() {
        i++;

        var d = document.getElementById("add_to_me");
        var htmlObject = document.createElement('div');

        var textnode =      '<div class="input-group" id="' + i + '" >' +
                                '<input required type="number" class="form-control" min="100000" max="999999" placeholder="Lieferscheinnummer" aria-label="Lieferscheinnummer" name="lieferscheine[]" aria-describedby="basic-addon2">' +
                                '<div class="input-group-append">' +
                                    '<button type="button" class="btn btn-outline-danger" onclick="delCode(' + i + ')">entfernen</button>' +
                                '</div>' +
                            '</div>';
        htmlObject.innerHTML = textnode;
        d.appendChild(htmlObject);
    }
    function delCode(i) {
        document.getElementById(i).remove();
    }
</script>
@endsection