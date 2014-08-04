Upgrade from 0.x.x to 1.0.0
===========================

API calls
---------

All API calls signatures have changed. Old situation:

    <?php

    $service->getArtist(1);

New situation is always using arrays:

    <?php

    $service->getArtist([
        'id' => 1
    ]);

In the <code>resources/service.php</code> file you can find all the implemented calls and their signatures and responses.

Iterator removed
----------------

It's not yet possible to iterate over the responses. Perhaps this will be added in the near future. It should be
easy to implement an <code>Iterator</code> yourself. PR's are welcome :).

Caching removed
---------------

Caching in the library itself is removed. This can be achieved by creating or using a cache plugin. At the moment of
writing the plugin for Guzzle isn't refactored yet for version 4.0.

No more models
--------------

Models have been replaced with plain old arrays. Models were nice for typing but a hell to manage. All responses will
return an array. When you want to debug the output use <code>$response->toArray()</code>.

