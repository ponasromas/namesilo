# Namesilo API PHP wrapper
Easy and simple to understand API wrapper around Namesilo API.
API documentation: https://www.namesilo.com/api-reference#domains/register-domain

## Installation
```php
<?php

require_once('namesilo.php');

use Ponasromas\Namesilo;

$ns = new Namesilo();
```

## Usage
Class has traits according to Namesilo API operations:

- \Account
- \Auctions
- \Contact
- \Dns
- \Domains
- \Email
- \Forwarding
- \Marketplace
- \Nameserver
- \Portfolio
- \Privacy
- \Transfer

Please read documentation on each operation you will perform. Each trait contains short comments about parameters.

### Examples

First update config.ini with required values.

```ini
sandbox=true
debug=true
sandbox_api="https://sandbox.namesilo.com/"
production_api="https://www.namesilo.com/"
api_key=""
payment_id=false
version="1"
type="xml"
```

Run class:
```php
<?php

require_once('namesilo/namesilo.php');

use Ponasromas\Namesilo;

$ns = new Namesilo();
```

Creating new contact:
```php
try {
    $ns->contactAdd(
        null,
        'John',
        'Doe',
        'New York st. 0 - 1',
        'New York',
        'NY',
        01110,
        'USA',
        'john.doe@domain.tld',
        '01010101010'
    );
} catch (Exception $e) {
    echo $e->getMessage();
}
```

List all contacts:
```php
try {
    $ns->contactList();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

List single contact:
```php
try {
    $ns->contactList(01010);
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Delete contact:
```php
try {
    $ns->contactDelete(01010);
} catch (Exception $e) {
    echo $e->getMessage();
}
```

All commands work in the same manner.

## Notes
Take a note about Namesilo batch API. If you need batch API, just pass true when instatiating class. Just like this:

```php
<?php

require_once('namesilo/namesilo.php');

use Namesilo\Namesilo;

$ns = new Namesilo(true);
```

Trait *\Marketplace* does not cover '*marketplaceAddOrModifySale*' and '*marketplaceLandingPageUpdate*' operations. These should be issued from Namesilo panel. Don't play with them from API ;) .
