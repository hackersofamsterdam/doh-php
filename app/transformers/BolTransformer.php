<?php

class BolTransformer extends \League\Fractal\TransformerAbstract
{
    /**
     * @param $bolData
     * @return array
     */
    public function transform($bolData)
    {
        return [
            'id'          => $bolData['id'],
            'token'       => 'bol-' . $bolData['id'],
            'title'       => $bolData['title'],
            'provider'    => 'bol',
            'description' => $bolData['longDescription'],
            'price'       => $bolData['offerData']['offers'][0]['price'],
            'target'      => $bolData['urls'][0]['value']
        ];
    }
}