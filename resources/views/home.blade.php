@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            
            <div class="card">
                <div class="card-header">
                    <h1>Dashboard</h1>

                    <form class="row g-3" method="GET" action="/home">
                        <div class="col-md-6">
                            <label for="inputEmail4" class="form-label">von:</label>
                            <input type="date" class="form-control" id="von" name="von" {{$von != '1970-01-01' ? "value=$von" : ''}}>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword4" class="form-label">bis:</label>
                            <input type="date" class="form-control" id="bis" name="bis" {{$bis != '2999-01-01' ? "value=$bis" : ''}}>
                        </div>
                        <div class="col-12" style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">eingrenzen</button>
                        </div>
                    </form>


                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card" style="margin-bottom: 20px">
                                <div class="card-body">
                                    <h5 class="card-title">gemeldete Lieferscheine</h5>
                                    <h1 style="color:{{$prozent_color}}" class="card-title">{{$ls_prozent}}%</h1>
                                    <p class="card-text">Lieferscheine: <span style="font-weight: bold;">{{$ls_gesamt}}</span> davon gemeldet: <span style="font-weight: bold;">{{$ls_gemeldet}}</span></p>
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6" style="margin-bottom: 20px">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">fehlende Meldungen nach Lager</h5>
                                    <canvas id="bar-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">prozentual gemeldet nach Gromas Direktlieferant</h5>
                            <canvas id="bar-chart-direktlieferant"></canvas>
                        </div>
                    </div>
                    <table class="table table-striped" style="margin-top: 20px">
                        <thead>
                            <tr>
                                <th scope="col">Lieferant</th>
                                <th scope="col">Gesamt</th>
                                <th scope="col">fehlen</th>
                                <th scope="col">Prozent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prozentual_gemeldet_nach_direktlieferant as $item)
                                <tr>
                                    <th scope="row">{{$item->lieferant}}</th>
                                    <td>{{$item->gesamt}}</td>
                                    <td>{{$item->anzahl}}</td>
                                    <td>{{$item->Prozent}} %</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ URL::asset('js/chart.min.js') }}"></script>
<script>
    new Chart(document.getElementById("myChart"), {
        type: 'pie',
        data: {
        labels: ["gemeldet", "nicht gemeldet"],
        datasets: [{
                backgroundColor: ["#008000", "#FF0000"],
                data: [{{$ls_gemeldet}}, {{$ls_gesamt - $ls_gemeldet}}]
            }]
        },
        options: {
            title: {
                display: false,
                text: 'Predicted world population (millions) in 2050'
            }
        }
    });

    new Chart(document.getElementById("bar-chart"), {
        type: 'bar',
        data: {
        labels: {!!$barchart_kundennamen!!},
        datasets: [
                {
                    label: "fehlende Meldungen",
                    backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                    data: {!!$barchart_count!!}
                }
            ]
        },
        options: {
            indexAxis: 'y',
            legend: { display: false },
            title: {
                display: false,
                text: 'Predicted world population (millions) in 2050'
            }
        }
    });

    new Chart(document.getElementById("bar-chart-direktlieferant"), {
        type: 'bar',
        data: {
        labels: {!!$barchart_direktlieferant_namen!!},
        datasets: [
                {
                    label: "Meldungen in Prozent",
                    backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                    data: {!!$barchart_direktlieferant_prozent!!}
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive:true,
            legend: { display: false },
            title: {
                display: false,
                text: 'Predicted world population (millions) in 2050'
            }
        }
    });
</script>
@endsection
