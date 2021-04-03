<?php


namespace App\Services\XmlServices;


interface XmlServicesInterface
{
    /**
     * Get data from xml file
     * @return mixed
     */
    public function readXml();

    /**
     * This function filters the countries by the zone parameter
     * @param $zoneFilter
     * @return array
     */
    public function filterCountryByZone($zoneFilter);

    /**
     * This function sort countries by zone
     * @param bool $descendent
     * @return array
     */
    public function sortCountryByZone($descendent = false);
}
