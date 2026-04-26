<?php

use Illuminate\Support\Facades\Route;
use Satusehat\Integration\KYC;
use Satusehat\Integration\OAuth2Client;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // Inject environment variables for the Satusehat library
    $variables = [
        'SATUSEHAT_ENV',
        'ORGID_PROD', 'CLIENTID_PROD', 'CLIENTSECRET_PROD', 'SATUSEHAT_AUTH_PROD', 'SATUSEHAT_FHIR_PROD',
        'ORGID_STG', 'CLIENTID_STG', 'CLIENTSECRET_STG', 'SATUSEHAT_AUTH_STG', 'SATUSEHAT_FHIR_STG',
        'ORGID_DEV', 'CLIENTID_DEV', 'CLIENTSECRET_DEV', 'SATUSEHAT_AUTH_DEV', 'SATUSEHAT_FHIR_DEV',
    ];

    foreach ($variables as $var) {
        if ($val = env($var)) {
            putenv("$var=$val");
        }
    }

    $kyc = new KYC;
    $json = $kyc->generateUrl(config('satusehatintegration.agen_name'), config('satusehatintegration.agen_nip'));
    $kyc_link = json_decode($json, true);
    return view('welcome', ['url' => $kyc_link['data']['url']]);
    // return redirect($kyc_link['data']['url']);
});
