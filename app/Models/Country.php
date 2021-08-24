<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $primaryKey = 'pkidcountry';
    protected $table      = 'countries';
    protected $fillable = [
        'country_name'
    ];
}
