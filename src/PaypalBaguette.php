<?php

namespace Yoannky\Paypalbaguette;

use Session;
use Exception;
use Config;
use Illuminate\Http\Request;
class PaypalBaguette
{

    const BASE_URL_SANDBOX =  "https://api.sandbox.paypal.com";
    const BASE_URL =  "https://api.paypal.com";

    /**
     * Paypal express checkout method
     * @param  Float $amount
     * @param  String $currency
     * @param  String $succesUrl
     * @param  String $cancelUrl
     * @param  boolean $redirect
     * @return Paypal redirect url
     */
    public function pay($amount, $currency, $successUrl, $cancelUrl, $autoRedirect)
    {
        //List of allowed currency, workaround for PHP ver 5.5 and older
        define('ALLOWED_CURRENCY', serialize([
            'AUD',
            'BRL',
            'CAD',
            'CZK',
            'DKK',
            'EUR',
            'HKD',
            'HUF',
            'ILS',
            'JPY',
            'MYR',
            'MXN',
            'TWD',
            'NZD',
            'NOK',
            'PHP',
            'PLN',
            'GBP',
            'RUB',
            'SGD',
            'SEK',
            'CHF',
            'THB',
            'USD',
        ]));

        $token =Session::get('access_token', Self::getToken());

        if (!in_array(strtoupper($currency), unserialize(ALLOWED_CURRENCY))) {
            throw new Exception("This currency is not supported by paypal api", 1);
        }
        
        $header =  [
            'Authorization: Bearer '.$token,
            'Accept: application/json',
            'Content-Type : application/json'
        ];
        $url = "/v1/payments/payment";

        $data = array(
            "intent" => "sale",
            "redirect_urls" => array(
                "return_url" => $successUrl,
                "cancel_url" => $cancelUrl
            ),
            "payer" => array(
                "payment_method" => "paypal"
            ),
            "transactions" =>array(
                array("amount"=>array(
                    "total" => str_replace(',', '.', $amount),
                    "currency" => strtoupper($currency)
                ))
            )
        );

        $json = json_encode($data);
        

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_URL, (Config::get('paypalbaguette.env') == 'sandbox' ? Self::BASE_URL_SANDBOX : BASE_URL).$url);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);

        $jsonResponse = json_decode($result, 1);

        curl_close($ch);

        if (isset($jsonResponse["name"])) {
            throw new Exception("Invalid request", 1);
        }
        
        $url = $jsonResponse["links"][1]["href"];
        if ($autoRedirect) {
            ob_start();

            // clear out the output buffer
            while (ob_get_status()) {
                ob_end_clean();
            }

            header("Location:$url");
            exit();
        } else {
            return $url;
        }
    }

    /**
     * Paypal express checkout confirm method
     * @param  Request $request
     * @return json response
     */
    public function confirm(Request $request)
    {
        $payment = $request->get('paymentId');
        $payer = $request->get('PayerID');

        $token = Session::get('access_token', Self::getToken());

        $header =  [
            'Authorization: Bearer '.$token,
            'Accept: application/json',
            'Content-Type : application/json'
        ];
        $url = "/v1/payments/payment/".$payment."/execute/";

        $data = array(
            "payer_id" => $payer
        );

        $json = json_encode($data);
        

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_URL,  (Config::get('paypalbaguette.env') == 'sandbox' ? Self::BASE_URL_SANDBOX : BASE_URL).$url);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);

        $jsonResponse = json_decode($result, 1);    

        curl_close($ch);

        return $jsonResponse;
    }

    /**
     * request Paypal access token and store it in session
     * @param  none
     */
    private function getToken()
    {
        $client_id =  Config::get('paypalbaguette.client_id');
        $client_secret = Config::get('paypalbaguette.client_secret');
        var_dump($client_id);
        var_dump($client_secret);
        if (is_null($client_id) || is_null($client_id)) {
            throw new Exception("Either Paypal client id or client secret is missing", 1);
        }
        $url = "/v1/oauth2/token";
    
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, (Config::get('paypalbaguette.env') == 'sandbox' ? Self::BASE_URL_SANDBOX : BASE_URL).$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $client_secret);
    
        $result = curl_exec($ch);

        $json = json_decode($result, 1);

        curl_close($ch);

        Session::put('access_token', $json['access_token']);
    }
}
