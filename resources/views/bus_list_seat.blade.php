@component('components.head_bus_list')

    @slot('title')
        Travel Finder
    @endslot

@endcomponent


<div class="header-list-seats" style="width: 100%; text-align: center;margin-bootom: 20px;">
    <h1>Selecione a quantidade de poltronas deseja reservar de acordo com a quantidade de passagens deseja reservar</h1>
</div>

<hr>

<style>
    .seat {
        display: inline-block;
        width: 30px;
        height: 30px;
        border: 1px solid #000;
        text-align: center;
        margin-right: 5px;
    }

    .occupied {
        background-color: red;
        color: #fff;
    }
</style>


@if (count($data_external) > 0)
     @foreach($data_external as $key => $item)

        Seat: {{ $item['seat'] }}<br>
        Position: ( x: {{ $item['position']['x'] }}, y: {{ $item['position']['y'] }}, z: {{ $item['position']['z'] }} )<br>
        Occupied:
        @if ($item['occupied'])
            Occupied
        @else
            Not Occupied
        @endif
        <br>
        Type: {{ $item['type'] }}<br><br>

        <div class="seat @if ($item['occupied']) occupied @endif">
            @if ($item['occupied'])
                X
            @else
                {{ $item['seat'] }}
            @endif
        </div>
        <br>
        <br>
    @endforeach
@else
    <div class="centered-div-ops">
        <div>
            <i class="fa fa-frown-o" aria-hidden="true"></i>
        </div>
        <div>
            <p class="text">Ops, ocorreu um problema e não conseguimos carregar as poltronas, aguarde um pouco e tente novamente.</p>
        </div>
    </div>
@endif

</ul>
<br>
<br>
<br>
<hr>


<div class="baseboard">
    @component('components.footer_home_travel')
        @slot('message')
            Queremos que sua experiência com nossas viagens seja maravilhosa
        @endslot
    @endcomponent
</div>
