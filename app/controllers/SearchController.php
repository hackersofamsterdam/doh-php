<?php

class SearchController extends Controller
{
    /**
     * @var array
     */
    private $providers = [
        'bol'
    ];

    /**
     * @param $q string
     * @return \Illuminate\Http\JsonResponse
     */
    public function catalogAction($q)
    {
        $fractal  = new \League\Fractal\Manager;
        $products = [];

        foreach ($this->providers as $provider) {
            if ($result = (new $provider)->catalog($q)) {
                $class       = ucfirst($provider) . 'Transformer';
                $transformer = new $class;
                $resource    = new \League\Fractal\Resource\Collection($result, $transformer);

                $products[] = $fractal->createData($resource)->toArray()['data'];
            }
        }

        return Response::json(['data' => $products]);
    }

    /**
     * @param $provider
     * @param $productId
     * @return array
     */
    public function productAction($provider, $productId)
    {
        $result      = (new $provider)->product($productId);
        $fractal     = new \League\Fractal\Manager;
        $class       = ucfirst($provider) . 'Transformer';
        $transformer = new $class;
        $resource    = new \League\Fractal\Resource\Item($result, $transformer);

        return $fractal->createData($resource)->toArray();
    }
}