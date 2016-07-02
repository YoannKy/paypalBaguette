# LARAVEL PAYPALBAGUETTE - A PAYPAL PACKAGE FOR EXPRESS CHECKOUT

### Version
1.0.2


### Installation
#### In your composer.json
```sh
"repositories": [
        {
            "type": "git",
            "url": "https://github.com/YoannKy/paypalbaguette"
        }
    ],
    "require": {
       "Yoannky/paypalbaguette": "*"
    },
```
#### Add the service provider in config/app.php, in providers array
```sh
Yoannky\Paypalbaguette\PaypalBaguetteServiceProvider::class
```
#### Add the facade in config/app.php, in aliases array
```sh
'PaypalBaguette' =>  Yoannky\Paypalbaguette\Facades\PaypalBaguette::class
```
#### Add the service provider in config/app.php, in providers array

#### publish the configuration file
```sh
$ php artisan vendor:publish
```
#### Add both your paypal client id and secret id in config/paypalbaguette.php

### Usage:
This package sole purpose is to deliver a quick way to deal with paypal express checkouts, hence there is only one method:

#### pay($amount, $currency, $successUrl, $cancelUrl, $autoRedirect)
##### amount is the amount for the paypal transaction
##### currency is the currency code (USD,EUR,...)
##### successUrl is the link returned in case of a success
##### cancelUrl is the link returned in case of a cancel 
##### if autoRedirect is set to true, the method will automatically redirect the user to the paypal redirect link, if not, the method will just return the redirect url  