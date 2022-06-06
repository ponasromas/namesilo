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

trait Contact
{

    /*
    Add a contact profile to your account.

    More info: https://www.namesilo.com/api-reference#contact/contact-add
    */
    public function contactAdd(string $cp = null, string $fn, string $ln, string $ad, string $cy, string $st, int $zp, string $ct, string $em, int $ph)
    {
        $result = $this->request('contactAdd', [
            ['cp', $cp], // company (optional, string 64)
            ['fn', $fn], // first name (string 32)
            ['ln', $ln], // last name (string 32)
            ['ad', $ad], // address (string 128)
            ['cy', $cy], // city (string 64)
            ['st', $st], // state (string 64)
            ['zp', $zp], // zip (int 16)
            ['ct', $ct], // country (ISO code, string 4)
            ['em', $em], // email (string 128)
            ['ph', $ph], // phone number (int 32, no prefix)
        ]);
        if ($this->request_successp($result)) {
            return $result['reply']['contact_id'];
        }
        return false;
    }

    /*
    Update a contact profile in your account. Any changes you make to a contact profile 
    will update the registry records for any domains associated with that contact profile.

    More info: https://www.namesilo.com/api-reference#contact/contact-update
    */

    public function contactUpdate(string $domain, string $cp = null, string $fn, string $ln, string $ad, string $cy, string $st, int $zp, string $ct, string $em, int $ph)
    {
        $contact_id = $this->getContactIdByDomain($domain);
        $result = $this->request('contactUpdate', [
            ['contact_id', $contact_id],
            ['cp', $cp], // company (optional, string 64)
            ['fn', $fn], // first name (string 32)
            ['ln', $ln], // last name (string 32)
            ['ad', $ad], // address (string 128)
            ['cy', $cy], // city (string 64)
            ['st', $st], // state (string 64)
            ['zp', $zp], // zip (int 16)
            ['ct', $ct], // country (ISO code, string 4)
            ['em', $em], // email (string 128)
            ['ph', $ph], // phone number (int 32, no prefix)
        ]);
        if ($this->request_successp($result)) {
            return true;
        }
        return false;
    }

    /*
    Delete a contact profile in your account. Please remember that the only contact profiles that can be deleted 
    are those that are not the account default and are not associated with any active domains or order profiles.

    contact_id: The internal NameSilo ID for the contact profile record to delete.
    */

    public function contactDelete(int $contact_id)
    {
        $result = $this->request('contactDelete', [
            ['contact_id', $contact_id]
        ]);
        if ($this->request_successp($result)) {
            return true;
        }
        return false;
    }

    /*
    Get a list of all contact profiles within your account.

    Note: the maximum number of contacts that can be returned per request is limited to 1000, 
    please use offset parameter to navigate through your contacts dataset.
    
    Optional:
        contact_id: You can optionally pass the ID number for a specific contact record to look up. 
        All contacts associated with your account will be returned if you do not provide this parameter.
        offset: You can optionally pass the offset to navigate through your contacts dataset.
    */

    public function contactList(int $contact_id = null, int $offset = null)
    {
        $result = $this->request('contactList', [
            ['contact_id', $contact_id],
            ['offset', $offset],
        ]);

        if (!$this->request_successp($result)) {
            return false;
        }
        return $result['reply']['contact'];
    }

    /*
    Assign the data from your contact profiles to roles for a domain.

    Required Fields
        domain: The domain being updated

    Optional Fields (at least one is required)
        registrant: The contact id to use for the domain's Registrant role
        administrative: The contact id to use for the domain's Administrative Contact role
        billing: The contact id to use for the domain's Billing Contact role
        technical: The contact id to use for the domain's Technical Contact role
        
    Passing Contact ID: You must pass the internal NameSilo contact profile ID. 
    You can get this value by running a contactList command.
    */

    public function contactDomainAssociate(string $domain, int $registrant, int $administrative = null, int $billing = null, int $technical = null)
    {
        $result = $this->request('contactList', [
            ['domain', $domain],
            ['registrant', $registrant],
            ['administrative', $administrative],
            ['billing', $billing],
            ['technical', $technical],
        ]);

        if ($this->request_successp($result)) {
            return true;
        }
        return false;
    }

    /*
    Custom: get contact by domain name.
    */

    public function getContactByDomain(string $domain)
    {
        $contact_id = $this->getContactIdByDomain($domain);
        if (!$contact_id) {
            return false;
        }
        return $this->contactList($contact_id);
    }

    /*
    Custom: get contact ID by domain name.
    */

    public function getContactIdByDomain(string $domain)
    {
        $domain_info = $this->getDomainInfo($domain);
        if (!$domain_info) {
            return false;
        }
        $contact_id = $domain_info['contact_ids']['registrant'];
        return $contact_id;
    }
}
