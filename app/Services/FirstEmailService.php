<?php
namespace App\Services;

use \Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class FirstEmailService extends EmailServiceAbstract
{
    protected array $requestParamsValidationRules = [
        'name' => 'required|max:255|min:3',
        'company' => 'required|max:255|min:3',
    ];

    /**
     * @throws ValidationException
     */
    public function doRequest():array
    {
        $this->validateRequestParameters();

        return Http::withHeaders(['Authorization' => 'Bearer '.env('EMAIL_SERVICE_TOKEN')])
            ->withoutVerifying()
            ->get(
                'http://interview-api.stage1.beecoded.ro/mock/provider1/email',
                ['name' => 'test', 'company' => 'test']
            )
            ->json();
    }
}
