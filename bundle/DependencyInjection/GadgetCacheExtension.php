<?php

declare(strict_types=1);

namespace Gadget\Cache\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\MicroBundleExtensionTrait;

final class GadgetCacheExtension extends Extension
{
    use MicroBundleExtensionTrait;
}
