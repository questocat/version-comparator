<?php

/*
 * This file is part of version-compare package.
 *
 * (c) emanci <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Emanci\VersionComparator;

trait ValidatesSemVer
{
    /**
     * Checks if the string is a valid string representation of a version.
     *
     * @see http://semver.org  Semantic Versioning Specification (SemVer)
     *
     * @param string $versionStr
     *
     * @return bool
     */
    public static function validateVersion($versionStr)
    {
        return preg_match('/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-([0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*))?(?:\+([0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*))?$/', $versionStr);
    }
}
