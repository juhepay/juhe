<?php

namespace App\Common;
use App\Exceptions\CustomServiceException;
use Illuminate\Support\Str;

class Gateway{

    public static function __callStatic($method, $params)
    {
        $app = new self(...$params);

        return $app->create($method);
    }

    protected function create($method)
    {
        $gateway = 'App\Gateway\\'.Str::studly($method).'Api';
        if (class_exists($gateway)) {
            return self::make($gateway);
        }

        throw new CustomServiceException("Gateway [{$method}Api] Not Exists");
    }

    protected function make($gateway)
    {
        $app = new $gateway();
        return $app;
    }

}
