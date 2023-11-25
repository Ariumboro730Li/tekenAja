<?php

namespace App\Services\Traits;

trait GetMessageTrait {

    /**
     * The default HTTP status code
     * for the response.
     * @var int $http_code
     */
    public $http_code = 200;

    /**
     * The message for the response.
     * change this value in your service
     * @var string|null $message
     */
    public $message = null;

    /**
     * set debug to false | use failedMessageNotdebug() will return original message.
     * if debug is true, message will be replaced with default message.
     * @var array|null $result
     */
    public $debug = false;

    /**
     * Default Messages for HTTP status codes.
     */
    public array $defaultMessages = [
        '200' => 'Success',
        '400' => 'Failed',
        '404' => 'Not Found'
        // Add more status codes and default messages as needed
    ];

    /**
     * Get the response message along with the HTTP status code and execution time.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessage()
    {
        return response()->json([
            'code' => $this->http_code,
            'exec_time' => $this->exec_time->getTime() ?? 0,
            'message' => $this->setMessage($this->http_code),
        ], $this->http_code);
    }

    /**
     * Set a failed message along with the HTTP status code.
     *
     * @param string $message The error message.
     * @param int $http_code The HTTP status code (default: 400 Bad Request).
     * @param bool $debug Whether to use debug response behavior (default: true).
     * @return void
     */
    public function setFailedMessage($message = 'Failed', $http_code = 400, $debug = true)
    {
        $this->debug = $debug;
        $this->http_code = $http_code;
        $this->message = $this->setMessage($this->http_code, $message);
    }

    /**
     * Set a failed message along with the HTTP status code and debug response behavior.
     *
     * @param string $message The error message.
     * @param int $http_code The HTTP status code (default: 400 Bad Request).
     * @return void
     */
    public function failedMessage($message = 'Failed', $http_code = 400) : void
    {
        $this->setFailedMessage($message, $http_code, true);
    }

    /**
     * Set a failed message along with the HTTP status code and non-debug response behavior.
     *
     * @param string $message The error message.
     * @param int $http_code The HTTP status code (default: 400 Bad Request).
     * @return void
     */
    public function failedMessageDebugFalse($message = 'Failed', $http_code = 400) : void
    {
        $this->setFailedMessage($message, $http_code, false);
    }

    /**
     * Get the result with message, request data, execution time, and HTTP status code.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResultWithMessage()
    {
        return response()->json(array_merge([
            'request' => $this->request->all() ?? [],
            'exec_time' => $this->exec_time->getTime() ?? 0,
            'message' => $this->setMessage($this->http_code),
            'http_code' => $this->http_code,
        ], $this->result ?? []), $this->http_code);
    }

    /**
     * Get the appropriate message based on the HTTP status code.
     *
     * @param int $http_code The HTTP status code.
     * @param string|null $message The custom message (optional).
     * @return string The appropriate message based on the provided HTTP status code.
     */
    public function setMessage($http_code, $message = null)
    {
        // If the message is not empty && if debug is false,
        // return the message else return the default message.
        if (!$this->debug && ($this->message || $message)) {
            return $this->message ?? $message;
        }

        return $this->defaultMessages[$http_code] ?? 'No Code Registered';
    }
}
