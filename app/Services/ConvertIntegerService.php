<?php
namespace App\Services;


class ConvertIntegerService
{
    public function convertNumberInteger($number)
    {
        $cal = (int)($number /100);
        return $cal * 100;
    }
}