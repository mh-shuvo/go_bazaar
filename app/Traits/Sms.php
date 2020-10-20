<?php
namespace App\Traits;

trait Sms {

    public static function sendSms($mobile_no, $message)
    {
        $destination = "http://quicksmsbd.com/bulksms/index.php/Smsapi?key=59C4B806C84829390AA2447BB1FC71EB&username=gobazar&mobile=$mobile_no&msg=" . urlencode($message);

        // call cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $destination);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec($ch);

		curl_close($ch);

		return $output;
    }
}