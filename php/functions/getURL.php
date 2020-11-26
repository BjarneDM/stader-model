<?php
function getURL ( $URL )
{   global $wwwURL , $userAgent ;

// få fat i kildekoden til siden
    $ch = curl_init(str_replace(' ', '%20', $URL));

    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    curl_setopt($ch, CURLOPT_REFERER, $wwwURL);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

//     curl_setopt($ch, CURLOPT_VERBOSE, TRUE);

    $curlTry = 0 ;
    do
    {
        $wwwHTML = curl_exec($ch);
        $wwwInfo = curl_getinfo($ch) ;
        if (curl_errno($ch) != 0 || $wwwInfo['http_code'] != 200)
        {
//             echo "Curl error: " . curl_errno($ch) . "\n";
//             print_r($wwwInfo) ; echo "\n" ;

            $curlTry++ ;
            sleep(10) ;
        }
    } while ( curl_errno($ch) != 0 && $wwwInfo['http_code'] != 200 && $curlTry < 10 ) ;
    curl_close($ch);

// hiv kildekoden ind 
    $doc = new DOMDocument();
    $status = $doc->loadHTML($wwwHTML);
return [ $status , $doc ] ; }
?>
