<?php

namespace App\Http\Services;

use App\Http\Repositories\HomeTravelRepository;

class HomeTravelService
{
    private $repository;
    private $id = array();
    private $name = array();
    private $type  = array();
    private $url  = array();

    public function __construct(HomeTravelRepository $repository)
    {
        $this->repository = $repository;
    }

    public function searchStops()
    {

        $response = $this->repository->searchStops();

        if ($response["error"]) {
            return ["error" => $response["error"], "statusCode" => $response["statusCode"], "mensagem" => $response["mensagem"]];
        }

        foreach (json_decode($response["body"]) as $item) {
            if (strpos($item->name, 'TODOS') !== false) {
                if (isset($item->substops) && is_array($item->substops)) {
                    foreach ($item->substops as $substopsItem) {
                        $contemPalavras = strpos($substopsItem->name, ', SP') !== false || strpos($substopsItem->name, ', PR') !== false;
                        $idContePalavras = strpos($item->id, 'CIT_') === false ;
                        if ($contemPalavras) {
                            $this->name[] = $substopsItem->name;
                            $this->id[]   = $substopsItem->id;
                            $this->type[] = $substopsItem->type;
                            $this->url[]  = $substopsItem->url;
                        }
                    }
                }
            } else {
                $contemPalavras = strpos($item->name, ', SP') !== false || strpos($item->name, ', PR') !== false;
                $idContePalavras = strpos($item->id, 'CIT_') === false ;
                if ($contemPalavras && $idContePalavras) {
                    $this->name[] = $item->name;
                    $this->id[] = $item->id;
                    $this->type[] = $item->type;
                    $this->url[] = $item->url;
                }
            }
        }

        return [
            "error" => false,
            "statusCode" => $response["statusCode"],
            "body" => [
                "name" => $this->name,
                "id" => $this->id,
                "type" => $this->type,
                "url" => $this->url
            ]
        ];
    }

    public function search(
        string $from,
        string $to,
        string $travelDate,
        string $backDate,
        $include_connections
    )
    {

    $response  = $this->repository->search($from, $to, $travelDate, $backDate, $include_connections);

    if ($response["error"]) {
        return ["error" => $response["error"], "statusCode" => $response["statusCode"], "mensagem" => $response["mensagem"]];
    }

    $data_external = array();
    $contador = 0;
    foreach ($response["body"] as $key => $item) {
        $data_external[$contador]["id"] = $item['id'];
        $data_external[$contador]["company_id"] = $item['company']['id'];
        $data_external[$contador]["company_name"] = $item['company']['name'];
        $data_external[$contador]["from_id"] = $item['from']['id'];
        $data_external[$contador]["from_name"] = $item['from']['name'];
        $data_external[$contador]["to_id"] = $item['to']['id'];
        $data_external[$contador]["to_name"] = $item['to']['name'];
        $data_external[$contador]["availableSeats"] = $item['availableSeats'];
        $data_external[$contador]["withBPE"] =  $item['withBPE'] ? 'Yes' : 'No';
        $data_external[$contador]["departure_date"] = $item['departure']['date'];
        $data_external[$contador]["departure_time"] = $item['departure']['time'];
        $data_external[$contador]["arrival_date"] = $item['arrival']['date'];
        $data_external[$contador]["arrival_time"] = $item['arrival']['time'];
        $hours = floor($item['travelDuration'] / 3600);
        $minutes = floor(($item['travelDuration'] % 3600) / 60);
        $timeDuration = gmdate('H:i', mktime($hours, $minutes));
        $data_external[$contador]["travelDuration"] = $timeDuration;
        $data_external[$contador]["seatClass"] = $item['seatClass'];
        $data_external[$contador]["price_seatPrice"] = $item['price']['seatPrice'];
        $data_external[$contador]["price_taxPrice"] = $item['price']['taxPrice'];
        $data_external[$contador]["price_price"] = $item['price']['price'];
        $data_external[$contador]["insurance"] = $item['insurance'];
        $data_external[$contador]["allowCanceling"] = $item['allowCanceling'] ? 'Yes' : 'No';
        $data_external[$contador]["travelCancellationLimitDate"] = $item['travelCancellationLimitDate'];
        $contador++;
        }

        return [
            "error" => false,
            "statusCode" => $response["statusCode"],
            "body" => $data_external
        ];

    }

    public function seatSearch(string $travelId)
    {
        $response = $this->repository->seatSearch($travelId);

        if ($response["error"]) {
            return ["error" => $response["error"], "statusCode" => $response["statusCode"], "mensagem" => $response["mensagem"]];
        }

        $data_external = $this->orderTheSeats($response["body"]);

        return [
            "error" => false,
            "statusCode" => $response["statusCode"],
            "body" => $data_external
        ];
    }

    public function orderTheSeats($compositionOfTheBus)
    {
        $temp = array();
        if (count($compositionOfTheBus) ===0)
            return $compositionOfTheBus;
        for ($x = 0 ; $x < count($compositionOfTheBus); $x++) {
            for ($y = 0 ; $y < count($compositionOfTheBus); $y++) {
                if ($compositionOfTheBus[$x]['seat'] < $compositionOfTheBus[$y]['seat']) {
                    $temp = $compositionOfTheBus[$x];
                    $compositionOfTheBus[$x] = $compositionOfTheBus[$y];
                    $compositionOfTheBus[$y] = $temp;
                }
            }
        }
        return $compositionOfTheBus;
    }
}