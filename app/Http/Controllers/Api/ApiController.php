<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    protected function respond($data, $statusCode = 200, $headers = [])
    {
        return response()->json($data, $statusCode, $headers);
    }

    protected function respondSuccess($data, $statusCode = 200, $headers = [])
    {
        return $this->respond([
            'success' => true,
            'data' => $data
        ], $statusCode, $headers);
    }

    protected function respondSuccessWithPagination($data, $total, $statusCode = 200, $headers = [])
    {
        return $this->respond([
            'success' => true,
            'data' => $data,
            'meta' => [
                'total' => $total
            ]
        ], $statusCode, $headers);
    }

    protected function respondSuccessWithPaginationNested($data, $total,$totalParent, $statusCode = 200, $headers = [])
    {
        return $this->respond([
            'success' => true,
            'data' => $data,
            'meta' => [
                'total' => $total,
                'total_parent' => $totalParent
            ]
        ], $statusCode, $headers);
    }

    protected function respondError($message, $status, $code = '')
    {
        return $this->respond([
            'success' => false,
            'error' => [
                'message' => $message,
                'status' => $status,
                'code' => $code
            ]
        ], $status);
    }

    protected function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->respondError($message, 401);
    }

    protected function respondForbidden($message = 'Forbidden')
    {
        return $this->respondError($message, 403);
    }

    protected function respondNotFound($message = 'Not Found')
    {
        return $this->respondError($message, 404);
    }

    protected function respondUnprocessableEntity($message = 'Unprocessable Entity')
    {
        return $this->respondError($message, 422);
    }

    protected function respondInternalError($message = 'Internal Error')
    {
        return $this->respondError($message, 500);
    }
}
