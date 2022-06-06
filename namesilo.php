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

namespace Ponasromas;

spl_autoload_register(function ($class) {
    include 'traits/' . strtolower($class) . '.php';
});

class Namesilo
{
    public $config;
    public $batch;

    public function __construct(bool $batch = false)
    {
        $this->config = parse_ini_file(__DIR__ . '/config.ini', true, INI_SCANNER_TYPED);

        if (!$this->config) {
            exit('config.ini file not found!');
        }

        if (!isset($this->config['api_key'])) {
            exit('API key not set.');
        }

        if ($this->config['sandbox'] == true) {
            $this->api_url = ($this->batch ? $this->config['sandbox_api'] . 'apibatch/' : $this->config['sandbox_api'] . 'api/');
        } else {
            $this->api_url = ($this->batch ? $this->config['production_api'] . 'apibatch/' : $this->config['production_api'] . 'api/');
        }

        $this->version = $this->config['version'];
        $this->type = $this->config['type'];
        $this->payment_id = $this->config['payment_id'];
        $this->api_key = $this->config['api_key'];
        $this->sandbox = $this->config['sandbox'];
        $this->debug = $this->config['debug'];
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

        if (($string = @file_get_contents($url)) === false) {
            $error = error_get_last();
            exit("HTTP request failed. Error was:" . $error['message']);
        }

        $result = $this->xml_to_arr($string);

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

    // Inject Traits
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
