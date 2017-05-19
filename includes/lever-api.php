<?php

class LeverAPI
{
    private $apiUrl = 'https://api.lever.co/v1/postings?state=published';

    function getOffers()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "fji+bih6XHrECwevd0zPjqGWFV2LFyoUj5CuqRYLsBcJMRgA:");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}



