@component('components.head_bus_list')

    @slot('title')
        Travel Finder
    @endslot

@endcomponent

@if (count($data_external) > 0)
<form>
    @foreach($data_external as $key => $item)
        @if ($key === 0)

            <ul class="item-list">
                <h1>Passagens de ônibus de {{ $item['from_name'] }} - para: {{ $item['to_name'] }}</h1>
        @endif
        <li class="item">

                <div class="container-item-list">
                    <div class="container-item-details">
                        <input type="hidden" id = "id" name="id" value="{{ $item['id'] }}">
                        <input type="hidden" id = "company_id" name="company_id" value="{{ $item['company_id'] }}">
                        <input type="hidden" id = "from_id" name="from_id" value="{{ $item['from_id'] }}">
                        <input type="hidden" id = "to_id" name="to_id" value="{{ $item['to_id'] }}">

                        <div class="location">
                            Empresa: {{ $item['company_name'] }} -
                            De: {{ $item['from_name'] }} - Para: {{ $item['to_name'] }} <br>
                        </div>

                        @php
                            $timestamp = strtotime( $item['departure_date']);
                            $departure_date = date('d/m/Y', $timestamp);
                            $timestamp = strtotime($item['arrival_date']);
                            $arrival_date = date('d/m/Y H:i', $timestamp);
                        @endphp

                        <div class="departure">
                            <strong>Data e Hora de Saída: </strong> {{ $departure_date }} às  {{ $item['departure_time'] }} -
                            <strong>Data e Hora de Chegada: </strong> {{ $arrival_date }} às {{ $item['arrival_time'] }}
                        </div>

                        <div class="duration">
                            <strong>Duration:</strong> {{ $item['travelDuration'] }}
                        </div>

                        <div class="class">
                            <strong>Classe:</strong> {{ $item['seatClass'] }}
                        </div>

                        <div class="class">
                            <strong>Disponíveis:</strong> {{ $item['availableSeats'] }}
                        </div>

                        <div class="price">
                            <strong>Preço:</strong> {{ $item['price_price'] }}
                        </div>
                    </div>{{--  end of div container-item-details --}}
                    <div class="button-to-choose-the-trip">
                        @component('components.input_submit')
                            @slot('type_submit')
                                submit
                            @endslot
                            @slot('id_input_submit')
                                ida
                            @endslot
                            @slot('name_input_submit')
                                ida
                            @endslot
                            @slot('value_input_submit')
                                Escolher Ida
                            @endslot
                            @slot('class_input_submit')
                                btn-ida
                            @endslot
                        @endcomponent
                    </div>{{--  end of div button-to-choose-the-trip --}}
                </div>{{--  end of div container-item-list --}}

        </li>
    @endforeach
</form>
@else
    <div class="centered-div-ops">
        <div>
            <i class="fa fa-frown-o" aria-hidden="true"></i>
        </div>
        <div>
            <p class="text">Ops, não encontramos nenhum resultado para a data desejada.</p>
        </div>
    </div>
@endif
</ul>
<hr>
<div class="item-list">
    <h3>Informações gerais - Tipos de Poltrona</h3>
    <p>
        <h4>Leito</h4>
        Ônibus com poltrona do tipo leito (ou leito-cama) possuem uma inclinação quase que em 180º, imitando uma cama, equipados com ar condicionado, e banheiro. Também pode haver água mineral.
    </p>
    <p>
        <h4>Convencional</h4>
        Ônibus com poltronas do tipo convencional geralmente possuem uma inclinação até 45º e banheiro.
    </p>
    <p>
        <h4>Executivo</h4>
        Ônibus com poltronas do tipo Executivo possuem por sua maioria, inclinação e apoio para os pés, sanitário, ar condicionado. Também pode haver água mineral.
    </p>
</div>
<hr>

<div class="baseboard">
    @component('components.footer_home_travel')
        @slot('message')
            Queremos que sua experiência com nossas viagens seja maravilhosa
        @endslot
    @endcomponent
</div>
