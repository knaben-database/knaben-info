<?php

declare(strict_types=1);

require_once($_SERVER["DOCUMENT_ROOT"].'/php/classes/func/statusCodes.php');

class Api
{
    private static $instance;
    public static function init()
    {
        // Check is $_instance has been set
        if(!isset(self::$instance))
        {
            // Creates sets object to instance
            self::$instance = new Api();
        }

        // Returns the instance
        return self::$instance;
    }

    /**
     * @var array If some outputs are set with die=false, this is where all the messages gets stored.
     */
    private array $responses;

    /** Function to error out API
     * @param string|null $message The message, or the title of the error. If no message is provided, the API will use the default text from the status code
     * @param int $statusCode
     * @param string|null $description A deeper description of the problem can be added
     * @param bool $die if set to false, it will append the errors, and display them in the destructor
     * @param array $meta If wanted, possibility for changing the meta info
     * @return void
     */
    public function error(string $message = null, int $statusCode = 501, string $description = null, bool $die = true, array $meta = []){
        if ($message == null && isset($this->statusCodes::http_status_codes[$statusCode]))
            $message = StatusCodes::http_status_codes[$statusCode];
        $meta = array_merge($meta, ["error" => true, "error_msg" => ["title" => $message, "desc" => $description], "status_code" => $statusCode, "type" => "single", "server_time" => time()]);
        $result = [
            "meta" => $meta
        ];
        if ($die){
            http_response_code($statusCode);
            $this->setHeaders();
            echo json_encode($result);
            die;
        }else{
            unset($result["meta"]["type"], $result["meta"]["server_time"]);
            $this->responses[] = $result;
        }
    }

    /**
     * @param mixed $content The content to display, this would normally be an array
     * @param int $statusCode
     * @param bool $die if set to false, it will append the errors, and display them in the destructor
     * @param array $meta If wanted, possibility for changing the meta info
     * @return void
     */
    public function print($content = null, int $statusCode = 200, bool $die = true, array $meta = []){
        $meta = array_merge($meta, ["error" => false, "error_msg" => ["title" => null, "desc" => null], "status_code" => $statusCode, "type" => "single", "server_time" => time()]);
        $result = [
            "meta" => $meta,
            "source" => $content
        ];
        if ($die){
            http_response_code($statusCode);
            $this->setHeaders();
            if ($statusCode != 204 || $content != null)
                echo json_encode($result);
            die;
        }else{
            unset($result["meta"]["type"], $result["meta"]["server_time"]);
            $this->responses[] = $result;
        }
    }

    /**
     * Check if key exists in JSON response
     * @param array|null $rawJSON array This is the raw json decoded input from the client
     * @param array|null $keys array this is used to bind the $rawJSON with the keys. Provide the [$key => "int"|"string"|"bool"|"string[]"|"..."] here
     * @return array{ 0: bool, 1: array } bool == true means the checking was okです, if false refer to the errors at [1]
     */
    public function keys_exist(array $rawJSON = null, array $keys = null): array {
        if (!isset($rawJSON)) $rawJSON = [];
        if (!isset($keys)) $keys = [];
        $errors = [];
        foreach ($keys as $key => $type){
            // Check right away if the key doesn't exist
            if (!isset($rawJSON[$key])){
                $errors[$key] = "The key is not set";
                continue;
            }
            $value = $rawJSON[$key];

            // Check if array
            if (substr($type, -2) === "[]" && is_array($value)) {
                $type = substr($type, 0, -2);
                foreach ($value as $item){
                    if (($error = $this->checkType($item, $type)) !== true)
                        $errors[$key] = $error;
                }
            }else{
                if (($error = $this->checkType($value, $type)) !== true)
                    $errors[$key] = $error;
            }

        }

        if (empty($errors)) return [true, null];
        else return [false, $errors];
    }

    /**
     * Here we do the actual checking of the value being the correct type
     * @param mixed $value Can literally be anything
     * @param string $type "int"|"string"|"bool"|"string[]"|"etc"
     * @return string|true returns a string on failure, returns true when it was validated successfully
     */
    private function checkType($value, string $type){
        if ($type === "int" || $type === "number"){ // Handle if it is a number
            if (!is_numeric($value)) return "The value is not numeric";
            // Check if value is a float
            if (($value*10) % 10 != 0) return "The value is not an int";
        }else if ($type === "float") { // Handle if it is a number
            if (!is_numeric($value)) return "The value is not numeric";
        }else if ($type === "string"){ // Handle if it is a string
            // It can pretty much just not be an object
            if (is_array($value)) return "The value is not a string";
        }else if ($type === "bool" || $type === "boolean"){ // Handle if it is a bool
            if (is_numeric($value)) {
                if ((int)$value != 1 && 0 != (int)$value) {
                    return "The value is not a bool";
                }
                $value = ((int)$value == 1);
            }
            if (is_string($value)) {
                if (trim($value) != "true" && trim($value) != "false") {
                    return "The value is not a bool";
                } else {
                    $value = ($value == "true");
                }
            }
            if (!is_bool($value)){
                return "The value is not a bool";
            }
        }else{
            return "No data type provided?";
        }
        return true;
    }

    public function __destruct()
    {
        $highestError = 0;
        $errorCount = 0;
        $containsErrors = false;
        if (!empty($this->responses)){
            foreach ($this->responses as $response){
                if (isset($response["meta"]["status_code"]) && $response["meta"]["status_code"] > $highestError)
                    $highestError = $response["meta"]["status_code"];
                if (isset($response["meta"]["error"]) && $response["meta"]["error"] == true) {
                    $containsErrors = true;
                    $errorCount++;
                }
            }
            $result = [
                "meta" => [
                    "error" => $containsErrors,
                    "error_msg" => [
                        "title" => $containsErrors ? "one or more documents contains errors" : false,
                        "desc" => $errorCount . '/' . count($this->responses) . ' errors where found in multi API request'
                    ],
                    "status_code" => $highestError,
                    "type" => "multi",
                    "server_time" => time()
                ],
                "results" => $this->responses
            ];
            http_response_code($highestError);
            $this->setHeaders();
            if ($highestError != 204)
                echo json_encode($result);
        }
    }

    /**
     * Function to set headers more headers
     * @param array $extraHeaders not sure what this is, must have been drunk when I made this
     * @return void
     */
    private function setHeaders($extraHeaders = null){
        $headers = [
            [
                "match" => [
                    "content-type",
                    "application/json"
                ],
                "set" => "Content-Type: application/json; charset=utf-8",
                "found" => false
            ]
        ];

        if (!is_null($extraHeaders) && !empty($extraHeaders)){
            $headers = array_merge($headers, $extraHeaders);
        }

        foreach (headers_list() as $header){
            $header = strtolower($header);
            foreach ($headers as &$wantedHeaders){
                $found = true;
                foreach ($wantedHeaders["match"] as $match){
                    if (strpos($header, $match) === FALSE){
                        $found = false;
                    }
                }
                if ($found){
                    $wantedHeaders["found"] = true;
                    break;
                }
            }
        }

        foreach ($headers as $header)
            if (!$header["found"])
                header($header["set"]);
    }
}