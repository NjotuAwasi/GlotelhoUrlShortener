<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $table = 'urls';

    protected $fillable = [
        'original_url',
        'short_code',
        'click_count',
    ];

    // this function is to generate a short code for the url
    // the short code lenght will be 6 characters
    public static function generateShortCode(){
        return substr(md5(uniqid(rand(), true)), 0, 6);
    }
}
