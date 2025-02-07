<?php

declare(strict_types=1);

namespace Pest\Arch\Objects;

use Error;
use PHPUnit\Architecture\Elements\ObjectDescription;

/**
 * @internal
 */
final class VendorObjectDescription extends ObjectDescription // @phpstan-ignore-line
{
    /**
     * {@inheritDoc}
     */
    public static function make(string $path): ?self // @phpstan-ignore-line
    {
        try {
            $vendorObject = ObjectDescriptionBase::make($path);
        } catch (Error) {
            return null;
        }

        if (! $vendorObject instanceof ObjectDescriptionBase) {
            return null;
        }

        return $vendorObject;
    }
}
