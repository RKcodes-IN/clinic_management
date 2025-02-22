<?php

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\base\Component;
use app\modules\admin\models\Orders;
use app\modules\admin\models\WebSetting;


class RazorPay extends Component
{

    public function header()
    {
      $setting = new WebSetting();  
      $razorpay_key_id = $setting->getSettingBykey('razorpay_key_id');
      $razorpay_key_secret = $setting->getSettingBykey('razorpay_key_secret');


        return array(
            'Authorization: Basic ' . base64_encode($razorpay_key_id.':'.$razorpay_key_secret),
             'content-type: application/json',
        );
    }



    public function CreateOrder($amount)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "amount": ' . $amount*100 . ',
            "currency": "INR"
    
   
    }',

      CURLOPT_HTTPHEADER => $this->header(),

    
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    // Get Payment 

    public function checkPaymentByPayId($payId)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/payments/' . $payId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $this->header(),

        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    // Capture payment

    public function CapturePayment($amount, $payId)
    {


        $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/payments/' . $payId . '/capture',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "amount": ' . $amount . ',
            "currency": "INR"
        }',
        CURLOPT_HTTPHEADER => $this->header(),

        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
