<?php declare(strict_types=1);

namespace App\Providers\MovieApiProvider;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

class ApiResponseServiceProvider
{
    /**
     * @param $result
     * @param string $message
     *
     * @return JsonResponse
     */
    public function responseSuccess($result, string $message, int $code = 200): JsonResponse
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }


    /**
     * @param string|MessageBag $error
     * @param array|MessageBag $errorMessage
     * @param int $code
     *
     * @return JsonResponse
     */
    public function responseError(string|MessageBag $error, array|MessageBag $errorMessage = [], int $code = 404): JsonResponse
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessage)) {
            $response['data'] = $errorMessage;
        }

        return response()->json($response, $code);
    }
}