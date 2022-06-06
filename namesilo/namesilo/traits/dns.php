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

trait DNS
{
    /*
    View all of the current DNS records associated with the domain.
    You will need the "record_id" value to perform the dnsUpdateRecord and dnsDeleteRecord functions.

    domain: The domain being requested
    */

    public function dnsListRecords(string $domain)
    {
        $result = $this->request('dnsListRecords', [['domain', $domain]]);

        if ($this->request_successp($result)) {

            if (!isset($result['reply']['resource_record'][0])) {
                $temp_arr = [];
                $temp_arr[0] = $result['reply']['resource_record'];
                return $temp_arr;
            } else {
                return $result['reply']['resource_record'];
            }
            
        } else {
            return false;
        }
    }

    /*
    Adds a new DNS resource record to the selected domain.

    domain: The domain being updated
    rrtype: The type of resources record to add. Possible values are "A", "AAAA", "CNAME", "MX" and "TXT"
    rrhost: The hostname for the new record (there is no need to include the ".DOMAIN")
    rrvalue: The value for the resource record
        A - The IPV4 Address
        AAAA - The IPV6 Address
        CNAME - The Target Hostname
        MX - The Target Hostname
        TXT - The Text
    rrdistance: Only used for MX (default is 10 if not provided)
    rrttl: The TTL for the new record (default is 7207 if not provided)
    */

    public function dnsAddRecord(string $domain, string $rrtype, string $rrhost, string $rrvalue, int $rrdistance = 10, int $rrttl = 3600)
    {
        $result = $this->request('dnsAddRecord', [
            ['domain', $domain],
            ['rrtype', $rrtype],
            ['rrhost', $rrhost],
            ['rrvalue', $rrvalue],
            ['rrdistance', $rrdistance],
            ['rrttl', $rrttl],
        ]);
        if ($this->request_successp($result)) {
            return['reply']['record_id'];
        } else {
            return false;
        }
    }

    /*
    Update an existing DNS resource record.

    domain: The domain associated with the DNS resource record to modify
    rrid: The unique ID of the resource record. You can get this value using dnsListRecords.
    rrhost: The hostname to use (there is no need to include the ".DOMAIN")
    rrvalue: The value for the resource record
        A - The IPV4 Address
        AAAA - The IPV6 Address
        CNAME - The Target Hostname
        MX - The Target Hostname
        TXT - The Text
    rrdistance: Only used for MX (default is 10 if not provided)
    rrttl: The TTL for this record (default is 7207 if not provided)
    */

    public function dnsUpdateRecord(string $domain, string $rrid, string $rrhost, string $rrvalue, int $rrdistance = 10, int $rrttl = 3600)
    {
        $result = $this->request('dnsUpdateRecord', [
            ['domain', $domain],
            ['rrid', $rrid],
            ['rrhost', $rrhost],
            ['rrvalue', $rrvalue],
            ['rrdistance', $rrdistance],
            ['rrttl', $rrttl],
        ]);
        if ($this->request_successp($result)) {
            return['reply']['record_id'];
        } else {
            return false;
        }
    }

    /*
    Delete an existing DNS resource record.

    domain: The domain associated with the DNS resource record to delete
    rrid: The unique ID of the resource record. You can get this value using dnsListRecords.

    Note: If you delete any resource records associated with sub-domain forwarding 
    then the forwarding will be automatically removed and no longer work.
    */

    public function dnsDeleteRecord(string $domain, int $rrid)
    {
        $result = $this->request('dnsDeleteRecord', [
            ['domain', $domain],
            ['rrid', $rrid],
        ]);
        if ($this->request_successp($result)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    View all of the current DS (DNSSEC) records associated with the domain.

    domain:The domain being requested
    */

    public function dnsSecListRecords(string $domain)
    {
        $result = $this->request('dnsSecListRecords', [['domain', $domain]]);

        if ($this->request_successp($result)) {
            if (!isset($result['reply']['ds_record'][0])) {
                $temp_arr = [];
                $temp_arr[0] = $result['reply']['ds_record'];
                return $temp_arr;
            } else {
                return $result['reply']['ds_record'];
            }
        } else {
            return false;
        }
    }

    /*
    Add a DS record (DNSSEC) to your domain.

    domain: The domain to add the DS record to
    digest: The digest
    keyTag: The key tag
    digestType: The digest type (https://www.namesilo.com/popups/dnssec_records.php)
    alg: The algorithm (https://www.namesilo.com/popups/dnssec_records.php)
    */

    public function dnsSecAddRecord(string $domain, int $digest, int $keyTag, int $digestType, int $alg)
    {
        $result = $this->request('dnsSecAddRecord', [
            ['domain', $domain],
            ['digest', $digest],
            ['keyTag', $keyTag],
            ['digestType', $digestType],
            ['alg', $alg],
        ]);
        if ($this->request_successp($result)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    Delete a DS record (DNSSEC) from your domain.

    domain: The domain to add the DS record to
    digest: The digest
    keyTag: The key tag
    digestType: The digest type (https://www.namesilo.com/popups/dnssec_records.php)
    alg: The algorithm (https://www.namesilo.com/popups/dnssec_records.php)
    */

    public function dnsSecDeleteRecord(string $domain, int $digest, int $keyTag, int $digestType, int $alg)
    {
        $result = $this->request('dnsSecDeleteRecord', [
            ['domain', $domain],
            ['digest', $digest],
            ['keyTag', $keyTag],
            ['digestType', $digestType],
            ['alg', $alg],
        ]);
        if ($this->request_successp($result)) {
            return true;
        } else {
            return false;
        }
    }

}
