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

trait Auctions
{
    /*
    Returns auctions list.

    Optional
        domainId: Limit results by domain ID
        domainName: Limit results by domain name
        typeId: Limit results by type ID:
            Customer auction type ID = 1
            Expired domain auction type ID = 3
        statusId: Limit results by status ID:
            Expired domain active status ID = 2
            Auction closed winner status ID = 3
            Customer sale active status ID = 9
            Sale waiting status ID = 11
            Auction closed active payment plan ID = 16
        buyNow: Buy now (set 1)
        page: Limit results by page number
        pageSize: Limit results by num items of page
    */

    public function listAuctions(int $domainId = null, string $domainName = null, int $typeId = 1, int $statusId = 2, int $buyNow = 0, int $page = 1, int $pageSize = 50)
    {
        $result = $this->request('listAuctions', [
            ['domainId', $domainId],
            ['domainName', $domainName],
            ['typeId', $typeId],
            ['statusId', $statusId],
            ['buyNow', $buyNow],
            ['page', $page],
            ['pageSize', $pageSize],
        ]);

        if ($this->request_successp($result)) {
            return $result['reply']['body'];
        } else {
            return false;
        }
    }

    /*
    View auction.

    auctionId: Auction ID
    */

    public function viewAuction(int $auctionId)
    {
        $result = $this->request('viewAuction', [['auctionId', $auctionId]]);

        if ($this->request_successp($result)) {
            return $result['reply']['body'];
        } else {
            return false;
        }
    }

    /*
    Auction bid.

    auctionId: Auction ID
    bid: Bid amount

    Optional
        proxyBid: Proxy bid amount
    */

    public function bidAuction(int $auctionId, int $bid, int $proxyBid = null)
    {
        $result = $this->request('bidAuction', [
            ['auctionId', $auctionId],
            ['bid', $bid],
            ['proxyBid', $proxyBid],
        ]);

        if ($this->request_successp($result)) {
            return $result['reply']['body'];
        } else {
            return false;
        }
    }

    /*
    Auction buy now.

    auctionId: Auction ID
    */

    public function buyNowAuction(int $auctionId)
    {
        $result = $this->request('buyNowAuction', [['auctionId', $auctionId]]);

        if ($this->request_successp($result)) {
            return $result['reply']['body'];
        } else {
            return false;
        }
    }

    /*
    View auction history.

    auctionId: Auction ID
    */

    public function viewAuctionHistory(int $auctionId)
    {
        $result = $this->request('viewAuctionHistory', [['auctionId', $auctionId]]);

        if ($this->request_successp($result)) {
            return $result['reply']['body'];
        } else {
            return false;
        }
    }
}
