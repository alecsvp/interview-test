<?php

namespace App\Services;

use \Illuminate\Support\Facades\Http;

class ThirdEmailService extends EmailServiceAbstract
{
    protected array $requestParamsValidationRules = [
        'linkedInProfileUrl' => 'required',
        'company' => 'required',
    ];

    public function doRequest(): array
    {
        return Http::withHeaders(['Authorization' => 'Bearer '.env('EMAIL_SERVICE_TOKEN')])
            ->withoutVerifying()
            ->get(
                'http://interview-api.stage1.beecoded.ro/mock/provider3/email',
                ['linkedInProfileUrl' => 'https://www.linkedin.com/feed/', 'company' => 'test']
            )
            ->json();
    }
}
