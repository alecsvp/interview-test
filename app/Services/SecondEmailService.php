<?php

namespace App\Services;

use \Illuminate\Support\Facades\Http;

class SecondEmailService extends EmailServiceAbstract
{
    public function doRequest(): array
    {
        return Http::withHeaders(['Authorization' => 'Bearer '.env('EMAIL_SERVICE_TOKEN')])
            ->withoutVerifying()
            ->get('http://interview-api.stage1.beecoded.ro/mock/provider2/email', ['linkedInProfileUrl' => 'test'])
            ->json();
    }
}
