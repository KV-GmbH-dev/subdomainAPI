<?php

namespace lib;

use ArrayAccess;
use Closure;


/**
 * Sample API Client.
 *
 * A simple API Client for communicating with an external API endpoint.
 */

class clientAPI
{
    const API_URL = 'https://subdomain.net/api/';

    protected $results = array();

    private $api_token;

    protected $default_curl_settings = [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 1,
        CURLOPT_TIMEOUT => 1000
    ];


    public function __construct(String $api_token)
    {

        $this->api_token = $api_token;

    }

    /**
     * Make external API call to API.
     *
     * @param string $endpoint
     * @param array $payload
     *
     * @return array
     *
     *@throws \Exception Bad API response
     *
     * @throws \Exception Connection error
     */
    public function call($method, $endpoint, $payload = null)
    {

        $json_payload = json_encode($payload);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->api_token,
            'Content-Type: application/json',
            'Accept: application/json'
            ));
        curl_setopt($ch,CURLINFO_HEADER_OUT, true);

        //DEFAULT
        curl_setopt_array($ch,$this->default_curl_settings);

        switch ($method) {
            case "GET":
                break;
            case "PATCH":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$json_payload);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$json_payload);
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$json_payload);
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:

        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('Connection Error: ' . curl_errno($ch) . ' - ' . curl_error($ch));
        }

        curl_close($ch);


        $this->results = $this->processResponse($response);

        /** Catch Laravel Sanctum Standard for unauthenticated */
        if ($this->getFromResponse("message")) {

            $error = $this->getFromResponse("message");

            throw new \Exception("API Error: " . $error);
        }

        /** Catch error messages */
        if ($this->getFromResponse("error")) {
            throw new \Exception('API Error:' . $this->getFromResponse("error"));

        }

        if (!$this->getFromResponse("success")) {

            $error = $this->getFromResponse("error") ? $this->getFromResponse("error") : "Unknown API Error. Please consult the module log.";

            throw new \Exception("API Error: " . $error);
        }


        if ($this->results === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Bad response received from API');
        }

        return $this->results;
    }

    /**
     * Make external GET API call to API.
     *
     * @param string $endpoint
     * @param array $payload
     *
     * @return array
     *@throws \Exception Bad API response
     *
     * @throws \Exception Connection error
     */
    public function get($endpoint, $payload = null)
    {
        return $this->call("GET",$endpoint,$payload);
    }

    /**
     * Make external PUT API call to API.
     *
     * @param string $endpoint
     * @param array $payload
     *
     * @return array
     *@throws \Exception Bad API response
     *
     * @throws \Exception Connection error
     */
    public function put($endpoint, $payload)
    {
        return $this->call("PUT",$endpoint,$payload);
    }

    /**
     * Make external POST API call to API.
     *
     * @param string $endpoint
     * @param array $payload
     *
     * @return array
     *@throws \Exception Bad API response
     *
     * @throws \Exception Connection error
     */
    public function post($endpoint, $payload)
    {
        return $this->call("POST",$endpoint,$payload);
    }
    /**
     * Make external PATCH API call to API.
     *
     * @param string $endpoint
     * @param array $payload
     *
     * @return array
     *@throws \Exception Bad API response
     *
     * @throws \Exception Connection error
     */
    public function patch($endpoint, $payload)
    {
        return $this->call("PATCH",$endpoint,$payload);
    }
    /**
     * Make external DELTE API call to API.
     *
     * @param string $endpoint
     * @param array $payload
     *
     * @return array
     *@throws \Exception Bad API response
     *
     * @throws \Exception Connection error
     */
    public function delete($endpoint, $payload)
    {
        return $this->call("DELETE",$endpoint,$payload);
    }

    /**
     * Process API response.
     *
     * @param string $response
     *
     * @return array
     */
    public function processResponse($response)
    {
        $response = json_decode($response, true);

        return $response;
    }

    /**
     * Get from response results.
     *
     * @param string $key
     *
     * @return string
     */
    public function getFromResponse($key)
    {
        return static::getDot($this->results, $key, "");
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function getDot($array, $key, $default = null)
    {
        if (! static::accessible($array)) {
            return static::value($default);
        }
        if (is_null($key)) {
            return $array;
        }
        if (static::exists($array, $key)) {
            return $array[$key];
        }
        if (strpos($key, '.') === false) {
            return $array[$key] ?? static::value($default);
        }
        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return static::value($default);
            }
        }
        return $array;
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed  $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int  $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }

    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }


}