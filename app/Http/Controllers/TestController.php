<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TestController extends Controller
{

    /** Ping parts of the site to make sure its up **/
    public function siteup(){
        echo "hello";
    }
}
