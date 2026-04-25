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
    $kyc = new KYC;
    $json = $kyc->generateUrl(config('satusehatintegration.agen_name'), config('satusehatintegration.agen_nip'));
    $kyc_link = json_decode($json, true);
    return view('welcome', ['url' => $kyc_link['data']['url']]);
    // return redirect($kyc_link['data']['url']);
});
