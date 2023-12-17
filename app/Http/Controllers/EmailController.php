<?php

namespace App\Http\Controllers;

use App\Providers\FirstEmailServiceProvider;
use App\Services\FirstEmailService;
use App\Services\SecondEmailService;
use App\Services\ThirdEmailService;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Handlers\EmailProviderHandler;

class EmailController extends BaseController
{
    public function processEmails(
        FirstEmailService $firstEmailService,
        SecondEmailService $secondEmailService,
        ThirdEmailService $thirdEmailService
    ): array {
        $serviceProvidersList = [
            $firstEmailService,
            $secondEmailService,
            $thirdEmailService,
        ];

        return response()->json(['services_passed' => (new EmailProviderHandler())->handle($serviceProvidersList)]);
    }
}
