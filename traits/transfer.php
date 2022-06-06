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

trait Transfer
{
    /*
    Transfer a domain from a different registrar into your account.
    
    More info: https://www.namesilo.com/api-reference#domains/transfer-domain
    */

    public function transferDomain(string $domain, int $years = 1, int $contact_id = null, string $auth = null, string $portfolio = null, string $coupon = null, int $private = 1, int $auto_renew = 0, string $cp = null, string $fn = null, string $ln = null, string $ad = null, string $cy = null, string $st = null, int $zp = null, string $ct = null, string $em = null, int $ph = null)
    {
        if (!$contact_id) {
            $contact_id = $this->contactAdd($cp, $fn, $ln, $ad, $cy, $st, $zp, $ct, $em, $ph);
        }

        $result = $this->request('transferDomain', [
            ['domain', $domain],
            ['years', $years],
            ['payment_id', $this->payment_id],
            ['auth', $auth],
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
    Returns information concerning the domain transfer. Only the last status update will be displayed.

    Statuses: https://www.namesilo.com/popups/transfer_statuses.php
    */

    public function checkTransferStatus(string $domain)
    {
        $result = $this->request('checkTransferStatus', [['domain', $domain]]);
        if ($this->request_successp($result)) {
            return $result['reply']['status'];
        } else {
            return false;
        }
    }

    /*
    Adds/Changes the EPP code for a domain transfer.

    domain: The domain being transferred
    auth: The EPP code to use
    */

    public function transferUpdateChangeEPPCode(string $domain, string $auth)
    {
        $result = $this->request('transferUpdateChangeEPPCode', [
            ['domain', $domain],
            ['auth', $auth],
        ]);
        if ($this->request_successp($result)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    Re-sends the administrative contact email verification to continue a domain transfer.

    domain: The domain being transferred
    */

      public function transferUpdateResendAdminEmail(string $domain)
      {
          $result = $this->request('transferUpdateResendAdminEmail', [['domain', $domain]]);
          if ($this->request_successp($result)) {
              return true;
          } else {
              return false;
          }
      }

    /*
    Re-submits the domain to the registry continue a domain transfer.

    domain: The domain being transferred
    */

    public function transferUpdateResubmitToRegistry(string $domain)
    {
        $result = $this->request('transferUpdateResubmitToRegistry', [['domain', $domain]]);
        if ($this->request_successp($result)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    Have the EPP transfer code for the domain emailed to the administrative contact.

    domain: The domain to retrieve the authorization code
    */

    public function retrieveAuthCode(string $domain)
    {
        return $this->request_successp($this->request('retrieveAuthCode', [['domain', $domain]]));
    }
}
