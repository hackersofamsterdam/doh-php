<?php

class Bol implements DataProviderInterface
{
    /**
     * @var \Guzzle\Http\Client
     */
    private $client;

    /**
     * @var string
     */
    private $provider = 'bol';

    /**
     * @var string
     */
    private $baseUrl = 'https://api.bol.com/catalog/v4/';

    public function __construct()
    {
        $this->client = new \Guzzle\Http\Client();
    }

    /**
     * Query catalog
     *
     * @param $q string
     * @return mixed
     */
    public function catalog($q)
    {
        $query = [
            'q' => $q,
            'dataoutput' => 'products',
            'apikey' => Config::get('providers.' . $this->provider)['apikey']
        ];

        $url = $this->baseUrl . 'search?' . http_build_query($query);

        $request = $this->client->get($url);

        $response = $request->send();

        return $response->json()['products'];
    }

    /**
     * Query product
     *
     * @param $productId
     * @return mixed
     */
    public function product($productId)
    {
        $query = [
            'apikey' => Config::get('providers.' . $this->provider)['apikey']
        ];

        $url = $this->baseUrl . "products/{$productId}?" . http_build_query($query);

        $request = $this->client->get($url);

        $response = $request->send();

        return $response->json()['products'][0];
    }
}