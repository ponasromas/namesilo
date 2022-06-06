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

trait Forwarding
{

    /*
    Configure your domain to forward using any one of Namesilo forwarding options.

    Required:
        domain: The domain you want to forward
        protocol: The URL protocol to use (options are "http" or "https")
        address: The web site address to forward to
        method: The forwarding method to utilize (options are "301", "302" or "cloaked"). 
        More on methods: https://www.namesilo.com/support/v2/articles/domain-manager/domain-forwarding

    Optional (only used when selecting "cloaked" as the method and all values must be encoded):
        meta_title: The META title for your cloaked forward
        meta_description: The META description for your cloaked forward
        meta_keywords: The META keywords for your cloaked forward
    */

    public function domainForward(string $domain, string $address, string $protocol = 'https', string $method = '302', string $meta_title = null, string $meta_description = null, string $meta_keywords = null)
    {
        $result = $this->request('domainForward', [
            ['domain', $domain],
            ['protocol', $protocol],
            ['address', $address],
            ['method', $method],
            ['meta_title', $meta_title],
            ['meta_description', $meta_description],
            ['meta_keywords', $meta_keywords],
        ]);

        if ($this->request_successp($result)) {
            return true;
        }

        return false;
    }

    /*
    Configure a sub-domain to forward using any one of our forwarding options.

    Required:
        domain: The domain you want to forward
        sub_domain: The sub-domain you want to forward
        protocol: The URL protocol to use (options are "http" or "https")
        address: The web site address to forward to
        method: The forwarding method to utilize (options are "301", "302" or "cloaked"). 
        More on methods: https://www.namesilo.com/support/v2/articles/domain-manager/domain-forwarding

    Optional (only used when selecting "cloaked" as the method and all values must be encoded):
        meta_title: The META title for your cloaked forward
        meta_description: The META description for your cloaked forward
        meta_keywords: The META keywords for your cloaked forward
    */

    public function domainForwardSubDomain(string $domain, string $sub_domain,  string $protocol = 'https', string $address, string $method = '302', string $meta_title = null, string $meta_description = null, string $meta_keywords = null)
    {
        $result = $this->request('domainForwardSubDomain', [
            ['domain', $domain],
            ['sub_domain', $sub_domain],
            ['protocol', $protocol],
            ['address', $address],
            ['method', $method],
            ['meta_title', $meta_title],
            ['meta_description', $meta_description],
            ['meta_keywords', $meta_keywords],
        ]);

        if ($this->request_successp($result)) {
            return true;
        }

        return false;
    }

    /*
    Delete a Sub-Domain Forward.
    */

    public function domainForwardSubDomainDelete(string $domain, string $sub_domain)
    {
        $result = $this->request('domainForwardSubDomainDelete', [
            ['domain', $domain],
            ['sub_domain', $sub_domain],
        ]);

        if ($this->request_successp($result)) {
            return true;
        }

        return false;
    }

    /*
    List of all email forwards for your domain.

    domain: The domain to view
    */

    public function listEmailForwards(string $domain)
    {
        $result = $this->request('listEmailForwards', [['domain', $domain]]);
        if ($this->request_successp($result)) {

            if (!isset($result['reply']['addresses'][0])) {
                $temp_arr = [];
                $temp_arr[0] = $result['reply']['addresses'];
                return $temp_arr;
            } else {
                return $result['reply']['addresses'];
            }
        } else {
            return false;
        }
    }

    /*
    Either create a new email forward or modify an existing email forward.

    Required
        domain: The domain related to the email address addition or modification
        email: The email forward to create/modify. For example, if you wanted to create/modify test@namesilo.com, 
        you would use "test". You can use "*" too create/modify a catch-all address.
        forward1: The first email address to forward email. For example, in the sample request above, test@namesilo.com would
        be set to forward to test@test.com.

    Optional
        forward2-5: Up to 4 additional addresses to forward email. For example, in the sample request above, in 
        addition to forwarding test@namesilo.com to the value for "forward1", email would also forward to 
        test2@test.com.
    */

    public function configureEmailForward(string $domain, string $email = '*', string $forward1, string $forward2 = null, string $forward3 = null, string $forward4 = null, string $forward5 = null)
    {
        $result = $this->request('configureEmailForward', [
            ['domain', $domain],
            ['email', $email],
            ['forward1', $forward1],
            ['forward2', $forward2],
            ['forward3', $forward3],
            ['forward4', $forward4],
            ['forward5', $forward5],
        ]);

        if ($this->request_successp($result)) {
            return true;
        }

        return false;
    }

    /*
    Delete an email forward.

    domain: The domain related to the email address to delete
    email: The forward to delete. For example, to delete test@namesilo.com, you would use "test"
    */

    public function deleteEmailForward(string $domain, string $email)
    {
        $result = $this->request('deleteEmailForward', [
            ['domain', $domain],
            ['email', $email],
        ]);

        if ($this->request_successp($result)) {
            return true;
        }

        return false;
    }
}
