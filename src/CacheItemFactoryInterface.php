<?php

declare(strict_types=1);

namespace Gadget\Cache;

use Psr\Cache\CacheItemInterface as PsrCacheItemInterface;

interface CacheItemFactoryInterface
{
    public function create(
        CacheItemPoolInterface $cache,
        string|PsrCacheItemInterface $keyOrItem
    ): CacheItemInterface;
}
