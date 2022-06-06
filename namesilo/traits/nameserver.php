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

trait Nameserver
{
    /*
    Change the name servers associated with the provided domain name. You must provide between 2 and 13 name servers 
    in order for this operation to be successful.

    Required:
        domain: A comma-delimited list of up to 200 domains
        ns1: NameServer 1
        ns2: NameServer 2

    Optional (usually 4 nameservers are more than enough):
        ns3: NameServer 3
        ...
        ns13: NameServer 13
    */

    public function changeNameServers(string $domain, string $ns1, string $ns2, string $ns3 = null, string $ns4 = null)
    {
        $result = $this->request('changeNameServers', [
            ['domain', $domain],
            ['ns1', $ns1],
            ['ns2', $ns2],
            ['ns3', $ns3],
            ['ns4', $ns4],
        ]);
        if ($this->request_successp($result)) {
            return true;
        }
        return false;
    }

    /*
    List the Registered NameServers associated with a domain.
    */

    public function listRegisteredNameServers(string $domain)
    {
        $result = $this->request('listRegisteredNameServers', [['domain', $domain]]);
        if ($this->request_successp($result)) {
            if (!isset($result['reply']['hosts'][0])) {
                $temp_arr = [];
                $temp_arr[0] = $result['reply']['hosts'];
                return $temp_arr;
            } else {
                return $result['reply']['hosts'];
            }
        }
        return false;
    }

    /*
    Add a Registered NameServer for your domain.

    Required:
        new_host: The host name for the new Registered NameServer (do not include the domain name) (required)
        ip1: The IP Address for the new Registered NameServer (required)

    Optional:
        ip2-ip13: Any additional IPs to associate with the new Registered NameServer
    */

    public function addRegisteredNameServer(string $new_host, string $ip1, string $ip2 = null, string $ip3 = null, string $ip4 = null)
    {
        $result = $this->request('addRegisteredNameServer', [
            ['new_host', $new_host],
            ['ip1', $ip1],
            ['ip2', $ip2],
            ['ip3', $ip3],
            ['ip4', $ip4],
        ]);
        if ($this->request_successp($result)) {
            return true;
        }
        return false;
    }

    /*
    Modify a Registered NameServer for your domain.

    Required:
        current_host: The current host name for the Registered NameServer to modify (do not include the domain name)
        new_host: The new host name for this Registered NameServer (do not include the domain name)
        ip1: The IP Address for this Registered NameServer

    Optional:
        ip2-ip13: Any additional IPs to associate with the new Registered NameServer
    */

    public function modifyRegisteredNameServer(string $current_host, string $new_host, string $ip1, string $ip2 = null, string $ip3 = null, string $ip4 = null)
    {
        $result = $this->request('modifyRegisteredNameServer', [
            ['current_host', $current_host],
            ['new_host', $new_host],
            ['ip1', $ip1],
            ['ip2', $ip2],
            ['ip3', $ip3],
            ['ip4', $ip4],
        ]);
        if ($this->request_successp($result)) {
            return true;
        }
        return false;
    }

    /*
    Delete a Registered NameServer for your domain.

    Required:
        domain: domain name
        current_host: nameserver (ns1, ns2 etc.)
    */

    public function deleteRegisteredNameServer(string $domain, string $current_host)
    {
        $result = $this->request('deleteRegisteredNameServer', [
            ['domain', $domain],
            ['current_host', $current_host],
        ]);
        if ($this->request_successp($result)) {
            return true;
        }
        return false;
    }
}
