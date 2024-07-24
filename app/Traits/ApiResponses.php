<?php

namespace App\Traits;

trait ApiResponses
{
    /**
     * Recognized http status code.
     */
    protected array $validStatusCodes = [
        '200',
        '201',
        '400',
        '401',
        '403',
        '404',
        '406',
        '417',
        '422',
        '500',
        '501',
        '503',
    ];

    /**
     * Return a 'success' json message.
     *
     * @param  null|string|array  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function okayApiResponse($data = null, $message = 'Request is successful')
    {
        return $this->successApiResponse($message, $data, 200);
    }

    /**
     * Return a 'success-created' json message.
     *
     * @param  null|string|array  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function createdApiResponse($data = null, $message = 'Request is successful')
    {
        return $this->successApiResponse($message, $data, 201);
    }

    /**
     * Return a 'success-fetch' json message.
     *
     * @param  null|string|array  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function fetchApiResponse($data = null, $message = 'Request is successful')
    {
        return $this->successApiResponse($message, $data, 200);
    }

    /**
     * Return a paginated 'success' result.
     *
     * @param  array  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function paginatedApiResponse($data, $message = 'Request is successful')
    {
        return response()->json(array_merge([
            'status' => true,
            'message' => $message,
        ], (is_array($data) ? $data : $data->resource->toArray())), 200);
    }

    /**
     * @param  null|string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function forbiddenApiResponse($message = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message ?? 'No access granted',
        ], 403);
    }

    public function respond($data, string $status = 'success', array $headers = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
        ], 200, $headers);
    }

    /**
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function notFoundApiResponse($message = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message ?? 'Resource not found!',
        ], 404);
    }

    private function sendFailure($message = 'Something went wrong', $data = null, $statusCode = 400)
    {
        return $this->send($message, $data, $statusCode, false);
    }

    private function sendSuccess($message = 'Request is successful', $data = [], $statusCode = 200)
    {
        return $this->send($message, $data, $statusCode, true);
    }

    private function send($message = '', $data = null, $statusCode = 200, $status = false)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * @param  null|string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function unauthorizedApiResponse($message = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message ?? 'Invalid credentials',
        ], 401);
    }

    /**
     * Return a custom error json message.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function errorApiResponse(string $message, int $code)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $code);
    }

    /**
     * Return a custom success json message.
     *
     * @param  null|string  $message
     * @param  null|array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    private function successApiResponse(string $message, $data, int $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Return a custom success json message with no data.
     *
     * @param  string|null  $message
     * @param  array|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
    private function successNoDataApiResponse(string $message, ?int $code = null)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
        ], $code ?? 200);
    }

    private function validateStatusCode($code)
    {
        return in_array($code, $this->validStatusCodes) ? (int) $code : 500;
    }

    /**
     * Returns invalid-request json
     */
    public function invalidRequestFields(array $errors, $code = 422)
    {
        return response()->json([
            'status' => false,
            'message' => $errors,
            'data' => null,
        ], $code);
    }
}
