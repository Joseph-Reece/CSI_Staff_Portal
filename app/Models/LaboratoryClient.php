<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryClient extends Model
{
    use HasFactory;
    
    public static function wsName(){
        return "QyLaboratoryClients";
    }
}
