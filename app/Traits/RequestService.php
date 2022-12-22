<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait RequestService
{
    /**
     * @param       $method
     * @param       $requestUrl
     * @param array $data
     * @param array $headers
     *
     * @return string
     */
    public function request($method, $requestUrl, $data = [], $headers = []) : string
    {           
        if (isset($this->bearer)) {
            $headers = [
                'Authorization' => 'Bearer ' . $this->bearer
            ];
        }
        $response = Http::withHeaders($headers)->$method($this->baseUri.$requestUrl, $data);
        return $response->getBody()->getContents();
    }
}
