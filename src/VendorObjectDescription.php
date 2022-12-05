<?php

declare(strict_types=1);

namespace Pest\Arch;

use Error;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Elements\ObjectDescriptionBase;

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
        $object = new self();

        try {
            $vendorObject = ObjectDescriptionBase::make($path);
        } catch (Error $e) {
            return null;
        }

        if ($vendorObject === null) {
            return null;
        }

        $object->name = $vendorObject->name;

        return $object;
    }
}
