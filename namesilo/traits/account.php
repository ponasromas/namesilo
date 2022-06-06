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

trait Account
{
    /*
    View your current account funds balance.
    */

    public function getAccountBalance()
    {
        $result = $this->request('getAccountBalance');
        if ($this->request_successp($result)) {
            return $result['reply']['balance'];
        } else {
            return false;
        }
    }

    /*
    Increase your NameSilo account funds balance.
    
    amount: The amount in US Dollars to add to your NameSilo account funds
    */

    public function addAccountFunds(float $amount)
    {
        $result = $this->request('addAccountFunds', [
            ['amount', $amount],
            ['payment_id', $this->payment_id],
        ]);

        if ($this->request_successp($result)) {
            return $result['reply']['new_balance'];
        } else {
            return false;
        }
    }

    /*
    Returns NameSilo price list customized optionally based upon your account's specific pricing.

    Optional:
        retail_prices: If passed, the prices returned will be our retail prices and will not 
        include Discount Program discounts applied to your account if you are enrolled in the Discount Program;
        registration_domains: The price based upon this number of domains being registered

    Custom: added $tld in order to extract single tld price.
    */

    public function getPrices(string $tld = null, float $retail_prices = null, int $registration_domains = null)
    {
        $result = $this->request('getPrices', [
            ['retail_prices', $retail_prices],
            ['registration_domains', $registration_domains]
        ]);

        if ($this->request_successp($result)) {
            if ($tld) {
                return $result['reply'][$tld];
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    /*
    Returns complete account order history.
    */

    public function listOrders()
    {
        $result = $this->request('listOrders');

        if ($this->request_successp($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /*
    View details for provided order number.

    order_number: The order number to view
    */

    public function orderDetails(int $order_number)
    {
        $result = $this->request('orderDetails', [['order_number', $order_number]]);

        if ($this->request_successp($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /*
    Returns expiring domains list.
       
    daysCount: Days count
   
    Optional:
        page: Limit results by page number
        pageSize: Limit results by num items of page
    */

    public function listExpiringDomains(int $daysCount = 90, int $page = 1, int $pageSize = 50)
    {
        $result = $this->request('listExpiringDomains', [
            ['daysCount', $daysCount],
            ['page', $page],
            ['pageSize', $pageSize]
        ]);

        if ($this->request_successp($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /*
    Returns expiring domains count.

    daysCount: Days count
    */

    public function countExpiringDomains(int $daysCount = 90)
    {
        $result = $this->request('countExpiringDomains', [['daysCount', $daysCount]]);

        if ($this->request_successp($result)) {
            return $result['reply']['body'];
        } else {
            return false;
        }
    }
}
