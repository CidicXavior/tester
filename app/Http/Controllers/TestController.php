<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{

    function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function searchFor($pattern,$subject){
        preg_match($pattern,$subject,$matches);
        return $matches;
    }
    
    /** Ping parts of the site to make sure its up **/
    public function siteup(){

        // homepage test.
        $test_url = 'http://www.seadream.com';
        $search_string = "<h1><b>IT'S YACHTING,<\/b> NOT CRUISING <span class=\"pipe-tag\">\|<\/span> ENJOY THE DIFFERENCE<\/h1>";
        $this->testIt($test_url,$search_string);

        // Voyage search test.
        $test_url = 'http://www.seadream.com/voyages/';
        $search_string = "<h1>Voyages<\/h1>";
        $this->testIt($test_url,$search_string);

        // Voyage Overview.
        $test_url = 'http://www.seadream.com/voyages/21746';
        $search_string = "<h1>Voyage Overview<\/h1>";
        $this->testIt($test_url,$search_string);

        echo "Checks complete.  ";
    }

    protected function testIt($test_url, $search_string){
        $page = $this->get_data($test_url);
        $matches = $this->searchFor('/'.$search_string.'/',$page);
        if(count($matches) > 0){

            // dont need to do anything if running in cron cli
            //echo "<span style='color:green'>Pass</span>: $test_url<br>";

        }else{
            $params = ['test_url' => $test_url, 'search_string' => $search_string];

            // Email me if something fails.
            Mail::send('email.testFailed', $params, function ($email_message) {
                $email_message->to('rarevalo@seadream.com');
                $email_message->from('do-not-reply@seadream.com', 'Test Failed');
                $email_message->subject('Test Failed');
            });

            // dont need to do anything if running in cron cli
            //echo "<span style='color:red'>Fail</span>: $test_url<br>";
        }
    }
}
