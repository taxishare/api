<?php 
function calculatePath(){

        if(date('H') < 6 || date('H') > 22)
        {
            if( date('N', time() ) > 6 )
                $quota = 4.5;
            else
                $quota = 3.0;
        }
        else
        {
            $quota = 6.5;
        }
        

        

        $lat1  = $_GET['lat1'];
        $long1 = $_GET['long1'];
        $lat2  = $_GET['lat2'];
        $long2 = $_GET['long2'];
        
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=it-IT";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_a = json_decode($response, true);
            $dist = @$response_a['rows'][0]['elements'][0]['distance']['text'];
            $time = @$response_a['rows'][0]['elements'][0]['duration']['text'];
        

        if(distanceGeoPoints(41.794977, 12.252183, $lat1, $long1) < 0.1 && distanceGeoPoints(41.823252, 12.414329, $lat2, $long2) < 0.1)
        {
            // dall'aeroporto di fiumicino a castello della magliana
            $price = 30;
        }
        elseif(distanceGeoPoints(41.794977, 12.252183, $lat2, $long2) < 0.1 && distanceGeoPoints(41.823252, 12.414329, $lat1, $long1) < 0.1)
        {
            // da castello della magliana all'aeroporto di fiumicino
            $price = 30;
        }
        elseif(distanceGeoPoints(41.794977, 12.252183, $lat1, $long1) < 0.1 && distanceGeoPoints(41.806254, 12.326192, $lat2, $long2) < 0.1)
        {
            // dall'aeroporto di fiumicino a nuova fiera di Roma
            $price = 25;
        }
        elseif(distanceGeoPoints(41.794977, 12.252183, $lat2, $long2) < 0.1 && distanceGeoPoints(41.806254, 12.326192, $lat1, $long1) < 0.1)
        {
            // da nuova fiera di Roma all'aeroporto di fiumicino
            $price = 25;
        }
        elseif(distanceGeoPoints(41.794977, 12.252183, $lat1, $long1) < 0.1 && distanceGeoPoints(41.797220, 12.587744, $lat2, $long2) < 0.1)
        {
            // dall'aeroporto di fiumicino a ciampino
            $price = 50.00;
        }
        elseif(distanceGeoPoints(41.794977, 12.252183, $lat2, $long2) < 0.1 && distanceGeoPoints(41.797220, 12.587744, $lat1, $long1) < 0.1)
        {
            // dall'aeroporto di ciampino a fiumicino
            $price = 50.00;
        }
        elseif(distanceGeoPoints(41.794977, 12.252183, $lat1, $long1) < 0.1 && distanceGeoPoints(41.909828, 12.530372, $lat2, $long2) < 0.1)
        {
            // dall'aeroporto di fiumicino alla stazione tiburtina
            $price = 55.00;
        }
        elseif(distanceGeoPoints(41.794977, 12.252183, $lat2, $long2) < 0.1 && distanceGeoPoints(41.909828, 12.530372, $lat1, $long1) < 0.1)
        {
            // dalla stazione tiburtina all'aeroporto di fiumicino
            $price = 55.00;
        } 
        elseif(distanceGeoPoints(41.797220, 12.587744, $lat1, $long1) < 0.1 && distanceGeoPoints(41.909828, 12.530372, $lat2, $long2) < 0.1)
        {
            // dall'aeroporto di ciampino a stazione tiburtina 
            $price = 35.00;
        }
        elseif(distanceGeoPoints(41.797220, 12.587744, $lat1, $long1) < 0.1 && distanceGeoPoints(41.909828, 12.530372, $lat2, $long2) < 0.1)
        {
            // dalla stazione tiburtina  all'aeroporto di ciampino
            $price = 35.00;
        }  
        elseif(distanceGeoPoints(41.797220, 12.587744, $lat1, $long1) < 0.1 && distanceGeoPoints(41.874142, 12.484175, $lat2, $long2) < 0.1)
        {
            // dall'aeroporto di ciampino a stazione ostiense 
            $price = 35.00;
        }
        elseif(distanceGeoPoints(41.797220, 12.587744, $lat2, $long2) < 0.1 && distanceGeoPoints(41.874142, 12.484175, $lat1, $long1) < 0.1)
        {
            // dall'aeroporto di ciampino a stazione ostiense 
            $price = 35.00;
        }
        else
        {
            $price = $quota + @$dist*1.3;
        }
        
            





        $searchString = $_GET['userid'] . "|" . $lat1 . ";" . $long1  . ";" .  $lat2  . ";" . $long2 ; 
        $compare = comparePath($searchString);
        saveData($searchString);


        if(!$compare){$compare=NULL;}

        echo json_encode(
            array(
                'distance' => $dist, 
                'time' => $time, 
                'price' => number_format($price, '2', '.', ''),
                'share' => $compare
            ));


    }



    function distanceGeoPoints($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 3958.75;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $dist = $earthRadius * $c;

        return $dist;
    }


    function saveData($content){
        file_put_contents(LOG, $content);
    }

    



    function comparePath($pathString){
        
        global $users;

        $current = file_get_contents(LOG);
        $current_arr = explode("|",$current);

        $pathString = explode("|",$pathString);


        if( $current_arr[0] != $pathString[0]  AND  $current_arr[1] == $pathString[1] ){  
            
            $user = setUserData($current_arr[0]);

            return $user; 
        }
        else{
            return FALSE;
        }
    }
