<?php

declare(strict_types=1);

namespace Gadget\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class CacheItemPool implements CacheItemPoolInterface
{
    /**
     * @param CacheItemPoolInterface $cache
     * @param string[] $namespace
     * @param bool $manageKeys
     */
    final public function __construct(
        private CacheItemPoolInterface $cache,
        private array $namespace = [],
        private bool $manageKeys = true
    ) {
    }


    /**
     * @return CacheItemPoolInterface
     */
    protected function getCache(): CacheItemPoolInterface
    {
        return $this->cache;
    }


    /**
     * @return string[]
     */
    public function getNamespace(): array
    {
        return $this->namespace;
    }


    /**
     * @param string $namespace
     * @return self
     */
    public function withNamespace(string ...$namespace): self
    {
        return new static(
            $this->getCache(),
            [...$this->getNamespace(), ...$namespace],
            $this->shouldManageKeys()
        );
    }


    /**
     * @return bool
     */
    public function shouldManageKeys(): bool
    {
        return $this->manageKeys;
    }


    /**
     * @param bool $manageKeys
     * @return static
     */
    public function setManageKeys(bool $manageKeys): static
    {
        $this->manageKeys = $manageKeys;
        return $this;
    }


    /**
     * @param string $key
     * @return string
     */
    public function getKey(string $key): string
    {
        return $this->shouldManageKeys()
            ? hash('SHA256', implode('::', [...$this->getNamespace(), $key]))
            : $key;
    }


    /**
     * @param string|CacheItemInterface $keyOrItem
     * @return CacheItemInterface
     */
    protected function getCacheItem(string|CacheItemInterface $keyOrItem): CacheItemInterface
    {
        return $keyOrItem instanceof CacheItemInterface
            ? $keyOrItem
            : $this->getItem($keyOrItem);
    }


    /**
     * @param string|CacheItemInterface $keyOrItem
     * @return mixed
     */
    public function getValue(string|CacheItemInterface $keyOrItem): mixed
    {
        $cacheItem = $this->getCacheItem($keyOrItem);
        return $cacheItem->isHit() ? $cacheItem->get() : null;
    }


    /**
     * @template T
     * @param string|CacheItemInterface $keyOrItem
     * @param (callable(mixed $value):(T|null)) $toValue
     * @return T|null
     */
    public function getTypedValue(
        string|CacheItemInterface $keyOrItem,
        callable $toValue
    ): mixed {
        return $toValue($this->getValue($keyOrItem));
    }


    /**
     * @param string|CacheItemInterface $keyOrItem
     * @param mixed $value
     * @param int|\DateTimeInterface|null $expiresOn
     * @return bool
     */
    public function setValue(
        string|CacheItemInterface $keyOrItem,
        mixed $value,
        int|\DateTimeInterface|null $expiresOn = null
    ): bool {
        return $this->save(
            $this
                ->getCacheItem($keyOrItem)
                ->set($value)
                ->expiresAt(
                    is_int($expiresOn)
                        ? (new \DateTime())->setTimestamp($expiresOn)
                        : $expiresOn
                )
        );
    }


    /**
     * @param string $key
     * @throws InvalidArgumentException
     * @return CacheItemInterface
     */
    public function getItem(string $key): CacheItemInterface
    {
        $key = $this->getKey($key);
        return $this->getCache()->getItem($key);
    }


    /**
     * @param string[] $keys
     * @throws InvalidArgumentException
     * @return iterable<string,CacheItemInterface>
     */
    public function getItems(array $keys = []): iterable
    {
        foreach ($keys as $key) {
            yield $key => $this->getItem($key);
        }
    }


    /**
     * @param string $key
     * @throws InvalidArgumentException
     * @return bool
     */
    public function hasItem(string $key): bool
    {
        $key = $this->getKey($key);
        return $this->getCache()->hasItem($key);
    }


    /** @return bool */
    public function clear(): bool
    {
        return $this->getCache()->clear();
    }


    /**
     * @param string $key
     * @throws InvalidArgumentException
     * @return bool
     */
    public function deleteItem(string $key): bool
    {
        $key = $this->getKey($key);
        return $this->getCache()->deleteItem($key);
    }


    /**
     * @param string[] $keys
     * @throws InvalidArgumentException
     * @return bool
     */
    public function deleteItems(array $keys): bool
    {
        $keys = array_map($this->getKey(...), $keys);
        return $this->getCache()->deleteItems($keys);
    }


    /**
     * @param CacheItemInterface $item
     * @return bool
     */
    public function save(CacheItemInterface $item): bool
    {
        return $this->getCache()->save($item);
    }


    /**
     * @param CacheItemInterface $item
     * @return bool
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        return $this->getCache()->saveDeferred($item);
    }


    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->getCache()->commit();
    }
}
