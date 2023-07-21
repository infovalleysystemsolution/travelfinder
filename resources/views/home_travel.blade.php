@component('components.head_home_travel')

    @slot('title')
        Travel Finder
    @endslot

@endcomponent

<div class="form-container">
    <div class="form-wrapper">
        @if ($errors->any())
            <div class="custom-alert custom-alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1>Encontre a sua passagem de ônibus</h1>
        <form>
            <input type="hidden" id = "_token" name="_token" value="{{ csrf_token() }}">
            @component('components.input_search')
                @slot('text_label')
                    Embarque
                @endslot
                @slot('type_input')
                    text
                @endslot
                @slot('id_input')
                    ponto-partida
                @endslot
                @slot('name_input')
                    ponto-partida
                @endslot
                @slot('hint_input')
                    Partindo de
                @endslot
                @slot('value_input')
                @endslot
                @slot('class_icon')
                    fas fa-map-marker-alt
                @endslot
            @endcomponent

            @component('components.input_search')
                @slot('text_label')
                    Desembarque
                @endslot
                @slot('type_input')
                    text
                @endslot
                @slot('id_input')
                    destino
                @endslot
                @slot('name_input')
                    destino
                @endslot
                @slot('hint_input')
                    Indo para
                @endslot
                @slot('value_input')
                @endslot
                @slot('class_icon')
                    fas fa-map-marker-alt
                @endslot
            @endcomponent

            @component('components.calendar_datepicker')
                @slot('text_label_data_picker')
                    Data de Saída
                @endslot
                @slot('type_data_picker')
                    text
                @endslot
                @slot('id_data_picker')
                    data-ida
                @endslot
                @slot('name_data_picker')
                    data-ida
                @endslot
                @slot('class_data_picker')
                    datepicker
                @endslot
                @slot('icon_data_picker')
                    far fa-calendar-alt
                @endslot
            @endcomponent

            @component('components.calendar_datepicker')
                @slot('text_label_data_picker')
                    Data de Retorno
                @endslot
                @slot('type_data_picker')
                    text
                @endslot
                @slot('id_data_picker')
                    data-volta
                @endslot
                @slot('name_data_picker')
                    data-volta
                @endslot
                @slot('class_data_picker')
                    datepicker
                @endslot
                @slot('icon_data_picker')
                    far fa-calendar-alt
                @endslot
            @endcomponent

            @component('components.input_submit')
                @slot('type_submit')
                    submit
                @endslot
                @slot('id_input_submit')
                    search
                @endslot
                @slot('name_input_submit')
                    search
                @endslot
                @slot('value_input_submit')
                    Buscar
                @endslot
                @slot('class_input_submit')
                    btn-search
                @endslot
            @endcomponent

        </form>
    </div>
</div>

{{--
@if (session('success'))
    <div class="custom-alert custom-alert-success">
        {{ session('success') }}
    </div>
@endif
--}}

@component('components.footer_home_travel')
    @slot('message')
        Queremos que sua experiência com nossas viagens seja maravilhosa
    @endslot
@endcomponent

