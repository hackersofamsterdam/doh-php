<?php

class Product extends Eloquent {
    public function user()
    {
        return $this->belongsTo('User');
    }
}