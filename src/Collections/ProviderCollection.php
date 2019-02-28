<?php

namespace Maduser\Minimal\Framework\Collections;

use Maduser\Minimal\Collections\AbstractTypedCollection;
use Maduser\Minimal\Collections\Collection;
use Maduser\Minimal\Collections\Contracts\CollectionInterface;
use Maduser\Minimal\Collections\Contracts\TypedCollectionInterface;
use Maduser\Minimal\Collections\Exceptions\InvalidKeyException;
use Maduser\Minimal\Collections\Exceptions\KeyInUseException;
use Maduser\Minimal\Collections\Exceptions\UnacceptableTypeException;
use Maduser\Minimal\Provider\Contracts\AbstractProviderInterface;
use Maduser\Minimal\Provider\Contracts\ProviderInterface;

final class ProviderCollection extends AbstractTypedCollection
{
    protected $acceptedTypes = [AbstractProviderInterface::class];
}

