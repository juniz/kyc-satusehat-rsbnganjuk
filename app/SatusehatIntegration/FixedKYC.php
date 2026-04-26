<?php

namespace App\SatusehatIntegration;

use Satusehat\Integration\KYC;
use Satusehat\Integration\Models\SatusehatToken;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;

class FixedKYC extends KYC
{
    /**
     * Override constructor to avoid using putenv() and manual .env loading
     * which fails on production servers with restricted permissions.
     */
    public function __construct()
    {
        $env = env('SATUSEHAT_ENV');

        if ($env == 'PROD') {
            $this->auth_url = env('SATUSEHAT_AUTH_PROD', 'https://api-satusehat.kemkes.go.id/oauth2/v1');
            $this->base_url = env('SATUSEHAT_FHIR_PROD', 'https://api-satusehat.kemkes.go.id/fhir-r4/v1');
            $this->client_id = env('CLIENTID_PROD');
            $this->client_secret = env('CLIENTSECRET_PROD');
            $this->organization_id = env('ORGID_PROD');
        } elseif ($env == 'STG') {
            $this->auth_url = env('SATUSEHAT_AUTH_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1');
            $this->base_url = env('SATUSEHAT_FHIR_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1');
            $this->client_id = env('CLIENTID_STG');
            $this->client_secret = env('CLIENTSECRET_STG');
            $this->organization_id = env('ORGID_STG');
        } else {
            $this->auth_url = env('SATUSEHAT_AUTH_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1');
            $this->base_url = env('SATUSEHAT_FHIR_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1');
            $this->client_id = env('CLIENTID_DEV');
            $this->client_secret = env('CLIENTSECRET_DEV');
            $this->organization_id = env('ORGID_DEV');
        }
    }

    /**
     * Override token() to use Laravel's env() helper instead of getenv()
     */
    public function token()
    {
        $environment = env('SATUSEHAT_ENV');
        
        $token = SatusehatToken::where('environment', $environment)
            ->orderBy('created_at', 'desc')
            ->where('created_at', '>', now()->subMinutes(50))
            ->first();

        if ($token) {
            return $token->token;
        }

        $client = new Client();

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $options = [
            'form_params' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
            ],
        ];

        // Create session
        $url = $this->auth_url.'/accesstoken?grant_type=client_credentials';
        $request = new Request('POST', $url, $headers);

        try {
            $res = $client->sendAsync($request, $options)->wait();
            $contents = json_decode($res->getBody()->getContents());

            if (isset($contents->access_token)) {
                SatusehatToken::create([
                    'environment' => $environment,
                    'token' => $contents->access_token,
                ]);

                return $contents->access_token;
            } else {
                return null;
            }
        } catch (ClientException $e) {
            $res = json_decode($e->getResponse()->getBody()->getContents());
            $issue_information = isset($res->issue[0]->details->text) ? $res->issue[0]->details->text : $e->getMessage();

            return $issue_information;
        }
    }
}
