$(function () {
    var cities = [];
    var id_cities = [];
    var type_cities = [];
    var url_cities = [];

    async function searchLocations() {
        console.log("cheguei em searchLocations, string recebida: ");
        const urlSearchStop = window.location.origin + "/searchstops";
        // alert(urlSearchStop);
        console.log("URL atual: ", urlSearchStop);
        try {
            const response = await fetch(urlSearchStop, {
                method: "GET",
            });
            if (response.status !== 200) {
                alert(
                    "Sistema indisponível no momento, aguarde um pouco e tente novamente."
                );
                return false;
            } else {
                const data = await response.json();
                if (data.length === 0) {
                    alert("Nenhuma viagem disponível para este trajeto.");
                    cities = [];
                    id_cities = [];
                    type_cities = [];
                    url_cities = [];
                    return false;
                }
                console.log(
                    "searchLocations (response da origem) => response do fetch: "
                );
                // console.log(data);
                cities = data.body.name;
                id_cities = data.body.id;
                type_cities = data.body.type;
                url_cities = data.body.url;
                console.log("cities: ");
                console.log(cities);
                console.log("id_cities: ");
                console.log(id_cities);
                console.log("type_cities: ");
                console.log(type_cities);
                console.log("url_cities: ");
                console.log(url_cities);
                return true;
            }
        } catch (error) {
            console.log("Erro na busca:", error);
            return false;
        }
    }

    cities = cities.filter(function (city, index, self) {
        return self.indexOf(city) === index;
    });

    cities.sort();

    function citiesObtainedRequest(cities) {
        console.log("cheguei em citiesObtainedRequest");
        console.log("citiesObtainedRequest: ", cities);
    }

    function handleAutocomplete(element) {
        element.autocomplete({
            source: function (request, response) {
                var term = request.term.toLowerCase();
                var filteredCities = cities.filter(function (city) {
                    return city.toLowerCase().indexOf(term) !== -1;
                });
                if (filteredCities.length === 0) {
                    alert("Cidade e/ou Estado ainda não está disponível");
                }
                response(filteredCities);
            },
            minLength: 2,
            select: function (event, ui) {
                $(this).val(ui.item.label);
                return false;
            },
            open: function (event, ui) {
                var menu = $(this).data("ui-autocomplete").menu.element;

                // Displays a maximum of 10 items in the list
                menu.css("max-height", "200px");

                // Adiciona uma barra de rolagem se necessário
                var showScrollBar = menu.height() < menu.prop("scrollHeight");
                menu.css("overflow-y", showScrollBar ? "scroll" : "hidden");
            },
        });
    }

    function formatDate(date) {
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        return day + "/" + month + "/" + year;
    }

    $(".datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        minDate: 0,
        beforeShow: function (input, inst) {
            var inputOffset = $(input).offset();
            setTimeout(function () {
                var dpDivHeight = inst.dpDiv.outerHeight();
                var topPosition =
                    inputOffset.top +
                    $(input).outerHeight() / 2 -
                    dpDivHeight / 2;
                inst.dpDiv.css({
                    top: topPosition + "px",
                    left: inputOffset.left + "px",
                });
            }, 0);
        },
        onSelect: function (dateText) {
            $(this).val(dateText);
        },
    });

    var timeoutId;
    var lastTypedValue = "";

    async function loadCities() {
        searchLocations().then((response_search) => {
            return response_search;
        });
    }

    async function sendCities(
        cities_func,
        id_cities_func,
        type_cities_func,
        url_cities_func
    ) {
        var urlSendCities = window.location.origin + "/toreceive";
        var send_cities = {
            cities: cities_func,
            id_cities: id_cities_func,
            type: type_cities_func,
            url_cities: url_cities_func,
        };
        const options = {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                _token: $("#_token").val(),
            },
            body: JSON.stringify(send_cities),
        };
        try {
            const response = await fetch(urlSendCities, options);
            const data = await response.json();
            console.log("getSearch => response do fetch: ", data);
            return data;
        } catch (error) {
            console.log("Erro na busca:", error);
            alert(
                "Não foi possível enviar viagens, aguarde alguns minutos e tente novamente."
            );
            return false;
        }
    }

    if ($("#ponto-partida").val().length == 0) loadCities();

    handleAutocomplete($("#ponto-partida"));
    handleAutocomplete($("#destino"));

    const button = $("#search");

    button.click(function (event) {
        event.preventDefault();
        var boarding = $("#ponto-partida").val();
        var landing = $("#destino").val();
        if (boarding.length === 0 || landing.length === 0) {
            alert(
                "Para ver as viagens disponíveis é necessário informar informe um local de embarque e de desembarque"
            );
            return false;
        }
        var boarding_index = cities.indexOf($("#ponto-partida").val());
        var landing_index = cities.indexOf($("#destino").val());
        var departure_date = $("#data-ida").val();
        var back_date = $("#data-volta").val();
        var parts_departure = departure_date.split("/");
        var parts_back = back_date.split("/");
        departure_date =
            parts_departure[2] +
            "-" +
            parts_departure[1] +
            "-" +
            parts_departure[0];
        back_date = parts_back[2] + "-" + parts_back[1] + "-" + parts_back[0];
        boarding_index = id_cities[boarding_index];
        landing_index = id_cities[landing_index];

        window.location.href =
            "/search?from=" +
            encodeURIComponent(boarding_index.toString()) +
            "&to=" +
            encodeURIComponent(landing_index.toString()) +
            "&travelDate=" +
            encodeURIComponent(departure_date.toString()) +
            "&backDate=" +
            encodeURIComponent(back_date.toString()) +
            "&include_connections=false";
        // = "{{ route('search') }}" method="POST"
    });
});
