<?php


namespace App\Services\XmlServices;


class XmlServices implements XmlServicesInterface
{
    /**
     * Get data from xml files
     * @return mixed
     */
    public function readXml()
    {
        $xmlString = file_get_contents(storage_path() . "/xml/countries.xml");
        $xmlObject = simplexml_load_string($xmlString);

        $this->appendCoordinates($xmlObject);

        return $xmlObject;
    }

    /**
     * This function filters the countries by the zone parameter
     * @param $zoneFilter
     * @return array
     */
    public function filterCountryByZone($zoneFilter)
    {
        $countriesXml = $this->readXml();
        $countriesFilter = $zoneFilter !== null ?
            $countriesXml->xpath('country[@zone="' . $zoneFilter . '"]')
            : $countriesXml->country;
        return $this->buildCountryArrayFromXml($countriesFilter);
    }

    /**
     * This function sort countries by zone
     * @param bool $descendent
     * @return array
     */
    public function sortCountryByZone($descendent = false)
    {
        $countriesXml = $this->readXml();
        $countriesSort = $this->buildCountryArrayFromXml($countriesXml);
        usort($countriesSort, function ($a, $b) use ($descendent) {
            if (filter_var($descendent, FILTER_VALIDATE_BOOLEAN)) {
                return strcasecmp($b['zone'], $a['zone']);
            }
            return strcasecmp($a['zone'], $b['zone']);
        });
        return $countriesSort;
    }

    /**
     * Extract coordinates from map_url tag and append to xml node
     * @param \SimpleXMLElement $xmlObject
     */
    private function appendCoordinates(\SimpleXMLElement $xmlObject)
    {
        foreach ($xmlObject as $country) {
            $pattern = "/(?<=@)(.*)(?=,[1-9]z)/";
            $out = preg_match($pattern, $country->map_url, $out) ? $out[0] : ',';
            $coordinates = explode(',', $out);
            $country->addChild('latitude', $coordinates[0]);
            $country->addChild('longitude', $coordinates[1]);
        }
    }

    /**
     * @param $countriesFilter
     * @return array
     */
    private function buildCountryArrayFromXml($countriesFilter)
    {
        $arrayCountries = [];
        foreach ($countriesFilter as $country) {
            $arrayCountries[] = [
                'zone' => json_decode(json_encode($country['zone']), true)[0],
                'name' => [
                    'name' => json_decode(json_encode($country->name), true)[0],
                    'native' => json_decode(json_encode($country->name['native']), true)[0]
                ],
                'language' => [
                    'language' => json_decode(json_encode($country->language), true)[0],
                    'native' => json_decode(json_encode($country->language['native']), true)[0]
                ],
                'currency' => [
                    'currency' => json_decode(json_encode($country->currency), true)[0],
                    'code' => json_decode(json_encode($country->currency['code']), true)[0]
                ],
                'map_url' => json_decode(json_encode($country->map_url), true)[0],
                'latitude' => json_decode(json_encode($country->latitude), true)[0],
                'longitude' => json_decode(json_encode($country->longitude), true)[0],
            ];
        }
        return $arrayCountries;
    }
}
