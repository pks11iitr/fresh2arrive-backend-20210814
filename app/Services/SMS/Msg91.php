<?php


namespace App\Services\SMS;


class Msg91
{

    protected static $authkey='368950AlfLlIyh617943fdP1';

    public static function send($mobile, $message, $dlt_id){

        //return true;
        $curl = curl_init();

        $url = 'https://api.msg91.com/api/sendhttp.php?authkey='.self::$authkey.'&sender=FRSHAR&mobiles=91'.$mobile.'&route=4&message='.urlencode($message).'&DLT_TE_ID='.$dlt_id;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        //var_dump($response);die;
        if ($err) {
          return false;
        } else {
          return true;
        }
    }
}
