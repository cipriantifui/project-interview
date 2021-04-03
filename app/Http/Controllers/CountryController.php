<?php

namespace App\Http\Controllers;


use App\Services\XmlServices\XmlServicesInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    private $xmlServices;

    /**
     * CountryController constructor.
     * @param XmlServicesInterface $xmlServices
     */
    public function __construct(XmlServicesInterface $xmlServices)
    {
        $this->xmlServices = $xmlServices;
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function index()
    {
        $countries = $this->xmlServices->readXml();
        $euroZoneCountries = $countries->xpath('country/currency[@code="EUR"]/../name');
        $countryZones = $countries->xpath('country[not(@zone=preceding-sibling::country/@zone)]/@zone');
        return view('countries.index', compact('countries', 'euroZoneCountries', 'countryZones'));
    }

    /**
     * Get countries from specific zone
     * @param Request $request
     * @return JsonResponse
     */
    public function filterCountryByZone(Request $request)
    {
        $filterZoneCountry = $this->xmlServices->filterCountryByZone($request->input('zone'));
        return response()->json(['success' => true, 'countries' => $filterZoneCountry]);
    }

    /**
     * Get countries sorted by zone
     * @param Request $request
     * @return JsonResponse
     */
    public function sortCountryByZone(Request $request)
    {
        $sortCountryByZone = $this->xmlServices->sortCountryByZone($request->input('descendent'));
        return response()->json(['success' => true, 'countries' => $sortCountryByZone]);
    }
}
