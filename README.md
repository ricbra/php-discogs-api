## Discogs Api

[![Build Status](https://secure.travis-ci.org/ricbra/php-discogs-api.png)](http://travis-ci.org/ricbra/php-discogs-api)

This library is an PHP 5.3 implementation of the [Discogs API v2.0.](http://www.discogs.com/developers/index.html)
The Discogs API is a REST-based interface. By using this library you don't have to worry about communicating with the
API: all the hard work has already be done.

## License
This library is released under the MIT license. See the complete license in the LICENSE file.

## Installation
Start by [installing composer](http://getcomposer.org/doc/01-basic-usage.md#installation) and finally
[install the dependencies](http://getcomposer.org/doc/01-basic-usage.md#installing-dependencies).

## Requirements
PHP >=5.3.0

## Usage
Creating a new instance is as simple as:

```php
<?php
$service = new \Discogs\Service();
```

### Perform a search:

```php
<?php

$resultset = $service->search(array(
    'q'     => 'Meagashira',
    'label' => 'Enzyme'
));

// Total results
echo count($resultset)."\n";
// Total pages
$pagination = $resultset->getPagination();
echo count($pagination)."\n";

// Fetch all results (use on your own risk, only one request per second allowed)
do {
    $pagination = $resultset->getPagination();
    echo $pagination->getPage().'<br />';
    foreach ($resultset as $result) {
        echo get_class($result).'<br />';
    }
} while($resultset = $service->next($resultset));
```

### Get information about a label:

```php
<?php

$label = $service->getLabel(1);

echo $label->getName()."\n";
```

### Get information about an artist:

```php
<?php

$artist = $service->getArtist(1);

echo $artist->getName()."\n";
```

### Get information about a release:

```php
<?php

$release = $service->getRelease(1);

echo $release->getTitle()."\n";
```

### Get information about a master release:

```php
<?php

$master  = $service->getMaster(1);

echo $master->getTitle()."\n";
```

### Response transformation

You have two options in which form to receive formatted response: as object using supplied models, or as plain array.
By default (if nothing has been set via setter) Model response transformer is chosen. You can manipulate it via
the `setResponseTransfomer` setter:

``` php
$discogs->setResponseTransformer(new \Discogs\ResponseTransformer\Model());
// or
$discogs->setResponseTransformer(new \Discogs\ResponseTransformer\Hash());
```

You can also set your own response transformer which need to implement the `ResponseTransformerInterface`

**NOTE** At this moment only the "Database" resource has been implemented. The "Marketplace" and "User" are missing.

## Documentation
Further documentation can be found at the [Discogs API v2.0 Documentation](http://www.discogs.com/developers/index.html).