<?php

namespace App\Http\Handlers;

use App\Services\EmailServiceAbstract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmailProviderHandler
{
    public function handle(array $serviceProvidersList): array
    {
        $servicesToBeIgnoredNextTime = $this->getServicesToBeIgnoredForChecking();

        /** @var EmailServiceAbstract $serviceProvider */
        foreach ($serviceProvidersList as $emailServiceProvider) {
            if (in_array($emailServiceProvider::class, $servicesToBeIgnoredNextTime)) {
                Log::info('Skip '.$emailServiceProvider::class.' for checking. ');

                continue;
            }

            try {
                $response = $emailServiceProvider
                    ->setRequestParameters(['name' => 'test', 'company' => 'test', ''])
                    ->doRequest();

                if ($this->isResponseValid($emailServiceProvider, $response)) {
                    $servicesToBeIgnoredNextTime[] = $emailServiceProvider::class;
                }

            } catch (\Exception $exception) {
                Log::error('Error on '.$emailServiceProvider::class.'. '.$exception->getMessage());
            }
        }

        $this->storeServicesToBeIgnoredForChecking($servicesToBeIgnoredNextTime);

        return $servicesToBeIgnoredNextTime;
    }

    protected function storeServicesToBeIgnoredForChecking($servicesToBeIgnoredNextTime): void
    {
        //TODO: store $servicesToBeIgnoredNextTime somewhere
    }

    protected function getServicesToBeIgnoredForChecking(): array
    {
        // TODO: return values from store

        // This return value is only for testing
        return ['App\Services\ThirdEmailService'];
    }

    /**
     * @throws ValidationException
     */
    protected function isResponseValid($emailServiceProvider, $response): bool
    {
        if (!empty($response['statusCode'])) {
            Log::info('Error on '.$emailServiceProvider::class.'. '.implode(',', $response));

            return false;
        }

        foreach ($emailServiceProvider->normalizeResponse($response) as $fieldValue) {

            $fieldsToBeCheckedInResponse = array_intersect(
                array_keys($fieldValue),
                $emailServiceProvider->getResponseValidationRules()
            );

            if (count($fieldsToBeCheckedInResponse) === 0) {
                continue;
            }

            // This will throw an error if not valid and will catch the error upper
            Validator::validate($fieldValue, $emailServiceProvider->getResponseValidationRules());
        }

        return true;
    }
}
