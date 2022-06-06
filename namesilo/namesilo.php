<?php

/*
 * Created on Fri Jun 03 2022
 *
 * Copyright (c) 2022 Roman L. https://github.com/ponasromas, https://romas.online
 *
 * License: The MIT License (MIT)
 * Copyright (c) 2022 Roman L.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


declare(strict_types=1);

namespace Namesilo;

spl_autoload_register(function ($class) {
    include 'traits/' . strtolower($class) . '.php';
});

class Namesilo
{
    public $api_url;
    public $api_key;
    public $version = 1;
    public $type = 'xml';
    public $debug = false;
    public $api_batch = false; // set to true in order to perform apibatch requests
    public $payment_id = ''; // set payment ID from namesilo account or sandbox. If not set, account funds will be used.

    public function __construct(string $api_key, bool $sandbox = false, bool $debug = false, bool $api_batch = false)
    {
        if ($sandbox == true) {
            switch ($api_batch) {
                case false:
                    $this->api_url = 'https://sandbox.namesilo.com/api/';
                    break;
                case true:
                    $this->api_url = 'https://sandbox.namesilo.com/apibatch/';
                    break;
                default:
                    $this->api_url = null;
            }
        } else {
            switch ($api_batch) {
                case false:
                    $this->api_url = 'https://www.namesilo.com/api/';
                    break;
                case true:
                    $this->api_url = 'https://www.namesilo.com/apibatch/';
                    break;
                default:
                    $this->api_url = null;
            }
        }

        $this->api_key = $api_key;
        $this->debug = $debug;
        $this->api_batch = $api_batch;
    }

    // Request, response methods
    protected function request($command, array $options = [])
    {
        $build_options = '';

        if (!empty($options)) {
            foreach ($options as $pair) {
                $build_options .= '&';
                $build_options .= $pair[0];
                $build_options .= '=';
                $build_options .= (is_string($pair[1]) ? urlencode($pair[1]) : $pair[1]);
            }
        }

        $url = $this->api_url . $command . '?version=' . $this->version . '&type=' . $this->type . '&key=' . $this->api_key . $build_options;

        if (($str = @file_get_contents($url)) === false) {
            $error = error_get_last();
            exit("HTTP request failed. Error was:" . $error['message']);
        }

        $result = $this->xml_to_arr($str);

        if ($this->debug) {
            echo '<pre>';
            print_r($result);
            echo '</pre>';
        }

        if (!$this->request_successp($result)) {
            throw new \Exception('Request: ' . $url . '<br><br> Result: ' . $result['reply']['code'] . ' ' . $result['reply']['detail']);
        }

        return $result;
    }

    protected function xml_to_arr($str)
    {
        return json_decode(json_encode(simplexml_load_string(trim($str))), TRUE);
    }

    protected function request_successp($arr)
    {
        if (
            $arr['reply']['code'] == 300 ||
            $arr['reply']['code'] == 301 ||
            $arr['reply']['code'] == 302
        ) {
            return true;
        } else {
            return false;
        }
    }

    // Traits
    use \Account,
        \Auctions,
        \Contact,
        \Dns,
        \Domains,
        \Email,
        \Forwarding,
        \Marketplace,
        \Nameserver,
        \Portfolio,
        \Privacy,
        \Transfer;
}
