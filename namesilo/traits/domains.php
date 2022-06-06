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

trait Domains
{

    /*
    Register a new domain name for the specified number of years and with the specified attributes.

    More info: https://www.namesilo.com/api-reference#domains/register-domain
    */

    public function registerDomain(string $domain, int $years = 1, int $contact_id = null, string $portfolio = null, string $coupon = null, int $private = 1, int $auto_renew = 0, string $cp = null, string $fn = null, string $ln = null, string $ad = null, string $cy = null, string $st = null, int $zp = null, string $ct = null, string $em = null, int $ph = null)
    {
        if (!$contact_id) {
            $contact_id = $this->contactAdd($cp, $fn, $ln, $ad, $cy, $st, $zp, $ct, $em, $ph);
        }

        $result = $this->request('registerDomain', [
            ['domain', $domain],
            ['years', $years],
            ['payment_id', $this->payment_id],
            ['private', $private],
            ['auto_renew', $auto_renew],
            ['portfolio', $portfolio],
            ['coupon', $coupon],
            ['contact_id', $contact_id],
        ]);

        if (!$this->request_successp($result)) {
            $this->contactDelete($contact_id);
            return false;
        }
        return true;
    }

    /*
    This API function can only be used between 10:45am PT and 12:15pm PT. 
    If you use this function outside of this timeframe you will receive an error response.

    Required
        domain: The domain you want to register
        years: The number of years for which you would like to register the domain (must be a number between 1-10)
    
    Optional
        private: Whether or not you want the registration to utilize our free WHOIS privacy service. 
        Use "1" for private, and "0" for not private. If not supplied, the domain will be registered without privacy.
        auto_renew: Whether or not you want the domain to auto-renew upon its expiration. 
        Use "1" to auto-renew, and "0" not to auto-renew. If not supplied, the domain will be set to auto-renew.

    More info: https://www.namesilo.com/api-reference#domains/register-domain-drop
    */

    public function registerDomainDrop(string $domain, int $years = 1, int $private = 1, int $auto_renew = 0)
    {
        if (!$this->api_batch) {
            exit('Batch API call must be used. Instatiate class with required parameter!');
        }

        $result = $this->request('registerDomainDrop', [
            ['domain', $domain],
            ['years', $years],
            ['private', 1],
            ['auto_renew', 0],
        ]);

        if (!$this->request_successp($result)) {
            return false;
        }
        return true;
    }

    /*
    Renew a domain in your account for the specified number of years.
    
    Required:
        domain: The domain you want to renew
        years: The number of years to renew the domain (must be a number between 1-10)

    Optional:
        payment_id: The ID number for the verified credit card to use for the transaction. 
        If you do not specify a payment_id, we will attempt to process the transaction using your account funds.
        coupon: The coupon code to apply to this order
    */

    public function renewDomain(string $domain, int $years, string $coupon = null)
    {
        $result = $this->request('renewDomain', [
            ['domain', $domain],
            ['years', $years],
            ['payment_id', $this->payment_id],
            ['coupon', $coupon],
        ]);

        if ($this->request_successp($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /*
    Generate a list of all active domains within your account.

    Optional
        portfolio: Limit results by an encoded portfolio name
        pageSize: Display only one page for big lists
        page: Display page for big lists
        withBid: Display page for big lists
        skipExpired: Skip expired domains
    */

    public function listDomains(string $portfolio = null, int $pageSize = 99, int $page = 1, int $withBid = 1, int $skipExpired = 1)
    {
        $result = $this->request('listDomains', [
            ['portfolio', $portfolio],
            ['pageSize', $pageSize],
            ['page', $page],
            ['withBid', $withBid],
            ['skipExpired', $skipExpired],
        ]);

        if (!$this->request_successp($result)) {
            return false;
        }
        return $result['reply']['domains'];
    }

    /*
    Get essential information on a domain within your account including the 
    expiration date, creation date, status, locked status and name servers.

    Operation-sepcific response:
        traffic_type: Will reflect the same as the "DNS Setting" value via our web site. The possible responses are, 
        "Parked", "Forwarded" or "Custom DNS".

        forward_url: Will show the domain forwarding URL if any. "N/A" will be returned if domain forwarding is
        not being used.

        forward_type: Will show the type of domain forwarding if used. The possible response are" 
            "Permanent Forward (301)", 
            "Temporary Forward (302)",
            "Cloaked Forward". 
            "N/A" will be returned if domain forwarding is not being used.

        email_verification_required: Will show if the Registrant email address requires verification or not. 
        Please note that domains using WHOIS Privacy do not require Registrant email verification so domains 
        using privacy will always return as "No".

        contact_ids: These values represent the internal NameSilo ID for each contact associated with this domain. 
        You can use these IDs when performing registrations, transfers and contact profile updates.
    */

    public function getDomainInfo(string $domain)
    {
        $result = $this->request('getDomainInfo', [['domain', $domain]]);
        if ($this->request_successp($result)) {
            return $result['reply'];
        } else {
            return false;
        }
    }

    /*
    Set the specified domain to be auto-renewed.
    */

    public function addAutoRenewal(string $domain)
    {
        $result = $this->request('addAutoRenewal', [['domain', $domain]]);
        if ($this->request_successp($result)) {
            return $result['reply'];
        } else {
            return false;
        }
    }

    /*
    Set the specified domain to not be auto-renewed.
    */

    public function removeAutoRenewal(string $domain)
    {
        $result = $this->request('removeAutoRenewal', [['domain', $domain]]);
        if ($this->request_successp($result)) {
            return $result['reply'];
        } else {
            return false;
        }
    }

    /*
    Set the specified domain to be locked.

    domain: The domain to lock
    */

    public function domainLock(string $domain)
    {
        $result = $this->request('domainLock', [['domain', $domain]]);
        return $this->request_successp($result);
    }

    /*
    Set the specified domain to be un-locked.

    domain: The domain to un-lock
    */

    public function domainUnlock(string $domain)
    {
        $result = $this->request('domainUnlock', [['domain', $domain]]);
        return $this->request_successp($result);
    }

    /*
    Determine if you can register the specified domains.

    domains: A comma-delimited list of domains to check (up to 200 can be processed)
    */

    public function checkRegisterAvailability(string $domain)
    {
        $result = $this->request('checkRegisterAvailability', [['domains', $domain]]);

        if ($this->request_successp($result) && isset($result['reply']['available'])) {
            return 'available';
        }
        if ($this->request_successp($result) && isset($result['reply']['invalid'])) {
            return 'invalid';
        }
        if ($this->request_successp($result) && isset($result['reply']['unavailable'])) {
            return 'unavailable';
        }

        return false;
    }

    /*
    Check if you can transfer the specified domains to your NameSilo account.

    domains: A comma-delimited list of domains to check (up to 200 can be processed)
    */

    public function checkTransferAvailability(string $domain)
    {
        $result = $this->request('checkTransferAvailability', [['domains', $domain]]);

        if ($this->request_successp($result)) {
            return $result['reply'];
        }

        return false;
    }

    /*
    Get whois info for domain.    
    */

    public function whoisInfo(string $domain)
    {
        $result = $this->request('whoisInfo', [['domain', $domain]]);
        if ($this->request_successp($result)) {
            return $result['reply'];
        } else {
            return false;
        }
    }

    /*
    Custom: returns domain lock status (true/false).
    */

    public function checkDomainLockStatus(string $domain)
    {
        $domain_info = $this->getDomainInfo($domain);
        if (!$domain_info) {
            return false;
        }
        $private = strtolower($domain_info['locked']);
        if ($private == 'yes') {
            return true;
        } else {
            return false;
        }
    }
}
