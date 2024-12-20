<?php

declare(strict_types=1);

namespace Gadget\Cache;

use Psr\Cache\CacheItemInterface as PsrCacheItemInterface;

interface CacheItemFactoryInterface
{
    public function create(
        CacheInterface $cache,
        string|PsrCacheItemInterface $keyOrItem
    ): CacheItemInterface;
}
