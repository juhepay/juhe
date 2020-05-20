<?php

namespace App\Tool;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TestImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

    }

    public function array( array $array)
    {
        return $array;
    }
}
