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

        //This code block will run if the url dose not already exist in the database
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
            'short_url' => url(path:'/').'/'.$shortCode,
            'original_url' => $url,
            'short_code' => $shortCode
        ]);
    }

    //this function is used to redirect the short url to the original url
    function redirectToOriginalUrl($code){

        //we first find the url in the database
        $url = Url::where("short_code", $code)->first();

        //if the url does not exist we throw a 404 error
        if(!$url){
            abort(404);
        }

        //this is to increment the click count by one and then redirect to the original url
        $url->increment('click_count');
        return redirect($url->original_url);

    }

    //this function is to return the stats of the url in json format
    function stats($code){

         //we first find the url in the database
        $url = Url::where("short_code", $code)->first();

        //if the url does not exist we throw a 404 error
        if(!$url){
            abort(404);
        }
        // return a json with url stats
        return response()->json([
            'original_url'=> $url->original_url,
            'short_code' => $url->short_code,
            'click_count' => $url->click_count,
            'Created_at' => $url->created_at
        ]);

    }
}
