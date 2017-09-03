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

/**
 * Compare the version number strings according to the "Semantic Versioning 2.0.0".
 *
 * @see http://semver.org Semantic Versioning
 */
class VersionComparator
{
    const COMPARE_LESS_THAN = -1;
    const COMPARE_EQUAL_TO = 0;
    const COMPARE_GREATER_THAN = 1;

    /**
     * Compares version number strings.
     *
     * returns -1 if the first version is lower than the second, 0 if they are equal, and 1 if the second is lower
     *
     * @param Version $version1
     * @param Version $version2
     *
     * @return int
     */
    public function versionCompare(Version $version1, Version $version2)
    {
        $compare = $this->compareStandardVersion($version1, $version2);

        if (0 == $compare) {
            $compare = $this->comparePreReleaseVersion($version1, $version2);
        }

        return $compare;
    }

    /**
     * Returns a Boolean value.
     *
     * Can use the comparison operator
     * <、 lt、<=、 le、>、 gt、>=、 ge、==、 =、eq、 !=、<> and ne
     *
     * @param int    $compare
     * @param string $operator
     *
     * @return bool
     */
    public function returnBool($compare, $operator)
    {
        $compareLen = strlen($operator);

        if (!strncmp($operator, '<', $compareLen) || !strncmp($operator, 'lt', $compareLen)) {
            return $compare == self::COMPARE_LESS_THAN;
        }

        if (!strncmp($operator, '<=', $compareLen) || !strncmp($operator, 'le', $compareLen)) {
            return $compare != self::COMPARE_GREATER_THAN;
        }

        if (!strncmp($operator, '>', $compareLen) || !strncmp($operator, 'gt', $compareLen)) {
            return $compare == self::COMPARE_GREATER_THAN;
        }

        if (!strncmp($operator, '>=', $compareLen) || !strncmp($operator, 'ge', $compareLen)) {
            return $compare != self::COMPARE_LESS_THAN;
        }

        if (!strncmp($operator, '==', $compareLen) || !strncmp($operator, '=', $compareLen) || !strncmp($operator, 'eq', $compareLen)) {
            return $compare == self::COMPARE_EQUAL_TO;
        }

        if (!strncmp($operator, '!=', $compareLen) || !strncmp($operator, '<>', $compareLen) || !strncmp($operator, 'ne', $compareLen)) {
            return $compare != self::COMPARE_EQUAL_TO;
        }

        return null;
    }

    /**
     * Compares the standard version.
     *
     * 1.0.0 < 2.0.0 < 2.1.0 < 2.1.1
     *
     * @param Version $version1
     * @param Version $version2
     *
     * @return int
     */
    protected function compareStandardVersion(Version $version1, Version $version2)
    {
        $version1Str = strval($version1->getMajor().$version1->getMinor().$version1->getPatch());
        $version2Str = strval($version2->getMajor().$version2->getMinor().$version2->getPatch());

        return version_compare($version1Str, $version2Str);
    }

    /**
     * Compares the pre-release version.
     *
     * 1.0.0-alpha < 1.0.0
     *
     * @param Version $version1
     * @param Version $version2
     *
     * @return int
     */
    protected function comparePreReleaseVersion(Version $version1, Version $version2)
    {
        $preRelease1 = $version1->getPreRelease();
        $preRelease2 = $version2->getPreRelease();

        if ($preRelease1 || $preRelease2) {
            if ($preRelease1 && empty($preRelease2)) {
                return self::COMPARE_LESS_THAN;
            }

            if (empty($preRelease1) && $preRelease2) {
                return self::COMPARE_GREATER_THAN;
            }

            $left = $preRelease1;
            $right = $preRelease2;
            $lt = self::COMPARE_LESS_THAN;
            $gt = self::COMPARE_GREATER_THAN;

            if (count($preRelease1) < count($preRelease2)) {
                $left = $preRelease2;
                $right = $preRelease1;
                $lt = self::COMPARE_GREATER_THAN;
                $gt = self::COMPARE_LESS_THAN;
            }

            foreach ($left as $index => $leftItem) {
                if (!isset($right[$index])) {
                    return $gt;
                }

                $rightItem = $right[$index];

                if ($leftItem == $rightItem) {
                    continue;
                }

                $leftIsNumeric = is_numeric($leftItem);
                $rightIsNumeric = is_numeric($rightItem);

                if ($leftIsNumeric && $rightIsNumeric) {
                    return $leftItem < $rightItem ? $lt : $gt;
                }

                if ($leftIsNumeric && !$rightIsNumeric) {
                    return $lt;
                }

                if (!$leftIsNumeric && $rightIsNumeric) {
                    return $gt;
                }

                $compare = strcmp($leftItem, $rightItem);

                if ($compare) {
                    return $compare > 0 ? $gt : $lt;
                }
            }
        }

        return self::COMPARE_EQUAL_TO;
    }
}
