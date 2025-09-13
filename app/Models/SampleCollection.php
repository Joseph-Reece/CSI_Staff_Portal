<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleCollection extends Model
{
    use HasFactory;
    public static function wsName(){
        return "QySampleCollectionHeader";
    }
    public static function tableDesc(){
        $data =  [
            'tableID' => 52203220,
        ];
        return $data;
    }
}
