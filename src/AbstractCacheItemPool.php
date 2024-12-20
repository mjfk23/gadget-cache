<?php

declare(strict_types=1);

namespace Gadget\Cache;

use Psr\Cache\CacheItemInterface as PsrCacheItemInterface;
use Psr\Cache\InvalidArgumentException as PsrInvalidArgumentException;

abstract class AbstractCacheItemPool implements CacheItemPoolInterface
{
    /**
     * @param string $key
     * @return PsrCacheItemInterface
     * @throws PsrInvalidArgumentException
     */
    public function getItem(string $key): PsrCacheItemInterface
    {
        $key = $this->getCacheKey($key);
        return $this->getCacheItemPool()->getItem($key);
    }


    /**
     * @param string[] $keys
     * @return iterable<string,PsrCacheItemInterface>
     * @throws PsrInvalidArgumentException
     */
    public function getItems(array $keys = []): iterable
    {
        foreach ($keys as $key) {
            yield $key => $this->getItem($key);
        }
    }


    /**
     * @param string $key
     * @return bool
     * @throws PsrInvalidArgumentException
     */
    public function hasItem(string $key): bool
    {
        $key = $this->getCacheKey($key);
        return $this->getCacheItemPool()->hasItem($key);
    }


    /**
     * @return bool
     */
    public function clear(): bool
    {
        return $this->getCacheItemPool()->clear();
    }


    /**
     * @param string $key
     * @return bool
     * @throws PsrInvalidArgumentException
     */
    public function deleteItem(string $key): bool
    {
        $key = $this->getCacheKey($key);
        return $this->getCacheItemPool()->deleteItem($key);
    }


    /**
     * @param string[] $keys
     * @return bool
     * @throws PsrInvalidArgumentException
     */
    public function deleteItems(array $keys): bool
    {
        $keys = array_map($this->getCacheKey(...), $keys);
        return $this->getCacheItemPool()->deleteItems($keys);
    }


    /**
     * @param PsrCacheItemInterface $item
     * @return bool
     */
    public function save(PsrCacheItemInterface $item): bool
    {
        return $this->getCacheItemPool()->save($item);
    }


    /**
     * @param PsrCacheItemInterface $item
     * @return bool
     */
    public function saveDeferred(PsrCacheItemInterface $item): bool
    {
        return $this->getCacheItemPool()->saveDeferred($item);
    }


    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->getCacheItemPool()->commit();
    }
}
