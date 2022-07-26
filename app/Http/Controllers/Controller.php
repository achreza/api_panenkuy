<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successResponse($result, $message) {
		$response = [
			'success' => true,
			'message' => $message,
			'data' => $result
		];

		return response()->json($response, 200);
	}

	public function errorResponse($error, $errorMessages = [], $code = 404 ) {
		$response = [
			'success' => false,
			'message' => $error,
		];

		if(!empty($errorMessages)) {
			$response['data'] = $errorMessages;
		}

		return response()->json($response, $code);
	}
}
