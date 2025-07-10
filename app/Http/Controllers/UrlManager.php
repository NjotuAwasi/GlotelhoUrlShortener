<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;

class UrlManager extends Controller
{
    //this function is used to create the short URL
    function createShortUrl(Request $request){
        //original url is required
        $request->validate(['original_url'=>'required']);

        //this line is to check if the url actually exists and gives the result to the variable $url
        $url = Url::where("original_url", $request->original_url)->first();

        //This code block will run if the url dose not exist
        if(!$url){

            //we generete a shortcode from the "generateShortcode" function in the URL model
            $shortCode = Url::generateShortCode();

            //we create a new url object to insert data to the database
            $url = new Url();

            //we insert the original url and shortcode into a datbase row and save it
            $url->original_url = $request->original_url;
            $url->short_code = $shortCode;
            $url->save();
        }
        return response()->json([
            'short_url' => url(path:'/').'/'.$shortCode, 'original_url'.$url, 'short_code'.$shortCode
        ]);
    }
}
