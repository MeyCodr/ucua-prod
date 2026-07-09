<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    public static function alert($message, $alertColor){
        session()->flash('message', $message);
        session()->flash('alertColor', $alertColor);
    }
}
