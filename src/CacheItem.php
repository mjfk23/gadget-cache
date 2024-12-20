<?php

declare(strict_types=1);

namespace Gadget\Cache;

use Psr\Cache\CacheItemInterface as PsrCacheItemInterface;

class CacheItem extends AbstractCacheItem implements CacheItemInterface
{
    /**
     * @param PsrCacheItemInterface $cacheItem
     */
    public function __construct(private PsrCacheItemInterface $cacheItem)
    {
    }


    /**
     * @return PsrCacheItemInterface
     */
    public function getCacheItem(): PsrCacheItemInterface
    {
        return $this->cacheItem;
    }


    /**
     * @param PsrCacheItemInterface $cacheItem
     * @return static
     */
    public function setCacheItem(PsrCacheItemInterface $cacheItem): static
    {
        $this->cacheItem = $cacheItem;
        return $this;
    }


    /**
     * @template T
     * @param (callable(mixed $value):(T|null)) $toValue
     * @return T|null
     */
    public function getCacheValue(callable $toValue): mixed
    {
        return $toValue($this->get());
    }


    /**
     * @param mixed $value
     * @param int|\DateTimeInterface|null $expiresOn
     * @return static
     */
    public function setCacheValue(
        mixed $value,
        int|\DateTimeInterface|null $expiresOn = null
    ): static {
        return $this
            ->set($value)
            ->expiresAt(
                is_int($expiresOn)
                    ? (new \DateTime())->setTimestamp($expiresOn)
                    : $expiresOn
            );
    }
}
