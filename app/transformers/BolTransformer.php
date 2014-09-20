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
            'id'       => $bolData['id'],
            'key'      => 'bol-' . $bolData['id'],
            'title'    => $bolData['title'],
            'provider' => 'bol',
            'description' => $bolData['longDescription'],
        ];
    }
}