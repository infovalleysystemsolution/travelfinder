<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\HomeTravelRequest;
use App\Http\Services\HomeTravelService;

class HomeTravelController extends Controller
{

    private $id = array();
    private $name = array();
    private $type  = array();
    private $url  = array();
    private $service;

    public function __construct(HomeTravelService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('home_travel');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function searchStops()
    {
        return $this->service->searchStops();
    }

    public function search(HomeTravelRequest $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $travelDate = $request->input('travelDate');
        $backDate = $request->input('backDate');
        $include_connections = $request->input('include_connections');

        $data_external = array();
        $response = $this->service->search($from, $to, $travelDate, $backDate, $include_connections);

        if ($response["error"] === false) {
            $data_external = $response["body"];
            return view('bus_list', compact('data_external'));
        } else {
            return view('bus_list', compact('data_external'));
        }

    }


    public function seatSearch(Request $request)
    {

        $travelId = $request->input("travelId");
        $response = $this->service->seatSearch($travelId);
        $data_external = $response["body"];
        return view('bus_list_seat', compact('data_external'));

    }

}