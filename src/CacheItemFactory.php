<?php

declare(strict_types=1);

namespace Gadget\Cache;

use Psr\Cache\CacheItemInterface as PsrCacheItemInterface;

class CacheItemFactory implements CacheItemFactoryInterface
{
    public function create(
        CacheInterface $cache,
        string|PsrCacheItemInterface $keyOrItem
    ): CacheItemInterface {
        return new CacheItem(
            $cache,
            $keyOrItem instanceof PsrCacheItemInterface
                ? $keyOrItem
                : $cache->getItem($keyOrItem)
        );
    }
}
