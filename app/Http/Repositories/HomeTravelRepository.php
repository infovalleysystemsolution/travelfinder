<?php

namespace App\Http\Repositories;

use GuzzleHttp\Client;

class HomeTravelRepository
{
    private $client;
    private $response;
    private $encrypted_authentication;

    public function __construct()
    {
        $this->client = new Client();
        $this->encrypted_authentication = base64_encode(
            env('USER_QUERO_PASSAGEM').":".env('PASSWORD_QUERO_PASSAGEM')
        );
    }

    public function searchStops()
    {
        $statusCode = 200;
        try {
            $this->response = $this->client->get('https://queropassagem.qpdevs.com/ws_v4/stops', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic ' . $this->encrypted_authentication,
                ],
            ]);
        } catch(\Exception $e) {
            // $statusCode = $this->response->getStatusCode();
            return ["error" => true, "statusCode" => 500, "mensagem" => "Erro na requisição de busca das paradas.".$e->getMessage()];
        }

        $statusCode = $this->response->getStatusCode();
        if ($statusCode!==200) {
            return ["error" => true, "statusCode" => $statusCode, "mensagem" => "Não foi possível obter as cidades. Erro na requisição."];
        }

        $body = ($this->response->getBody()->getContents());

        return ["error" => false, "statusCode" => $statusCode, "body" => $body];
    }

    public function search(
        string $from,
        string $to,
        string $travelDate,
        string $backDate,
        $include_connections
    )
    {
        $url = "https://queropassagem.qpdevs.com/ws_v4/new/search";
        $affiliateCode = "DEE";
        $headers =  [
            "Content-Type" => "application/json",
            "Accept" => "application/json",
            "Authorization" => "Basic " . $this->encrypted_authentication,
        ];
        $body = json_encode([
            "from" => "$from",
            "to" => "$to",
            "travelDate" => "$travelDate",
            "affiliateCode" => "$affiliateCode",
            "include-connections" => $include_connections
        ]);

        try {
            $response = $this->client->post($url, [
                "headers" => $headers,
                "body" => $body
            ]);
        } catch(\Exception $e) {
            return ["error" => true, "statusCode" => 500, "mensagem" => "Não foi possível obter os detalhes das cidades. ".$e->getMessage()];
        }

        $data = json_decode($response->getBody(), true);

        return ["error" => false, "statusCode" => 200, "body" => $data];
    }

    public function seatSearch(string $travelId)
    {
        $url = "https://queropassagem.qpdevs.com/ws_v4/new/seats";

        $headers = [
            "Content-Type" => "application/json",
            "Accept" => "application/json",
            "Authorization" => "Basic " . $this->encrypted_authentication
        ];

        $body = [
            "travelId" => "$travelId",
            "orientation" => "horizontal",
            "type" => "list"
        ];

        try {
            $response = $this->client->post($url, [
                'headers' => $headers,
                'json' => $body
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);

        } catch (\Exception $e) {
            return ["error" => true, "statusCode" => 500, "mensagem" => "Não foi possível obter os detalhes das poltronas disponíveis. ".$e->getMessage()];
        }

        return ["error" => false, "statusCode" => 200, "body" => $data];
    }


}