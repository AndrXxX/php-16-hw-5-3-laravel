<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = array('first_name', 'last_name', 'patronymic_name', 'phone_number');
}
