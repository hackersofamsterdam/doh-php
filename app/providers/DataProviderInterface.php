<?php

Interface DataProviderInterface
{
    public function catalog($q);

    public function product($id);
}