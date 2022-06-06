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

trait Marketplace
{

    /*
    A list and associated details for all active Marketplace sales in your account.
    */

    public function marketplaceActiveSalesOverview(string $domain)
    {
        $result = $this->request('marketplaceActiveSalesOverview');

        if ($this->request_successp($result)) {

            if (!isset($result['reply']['sale_details'][0])) {
                $temp_arr = [];
                $temp_arr[0] = $result['reply']['sale_details'];
                return $temp_arr;
            } else {
                return $result['reply']['sale_details'];
            }
        } else {
            return false;
        }
    }
}
