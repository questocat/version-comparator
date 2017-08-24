<?php

namespace Emanci\VersionCompare;

/**
 * 按照"语义化版本控制规范"比较版本号.
 *
 * @link http://semver.org Semantic Versioning
 */
class VersionCompare
{
    const COMPARE_LESS_THAN = -1;
    const COMPARE_EQUAL_TO = 0;
    const COMPARE_GREATER_THAN = 1;

    /**
     * The Version instance.
     *
     * @var Version
     */
    protected $version;

    /**
     * VersionCompare construct.
     *
     * @param string $versionStr
     */
    public function __construct($versionStr = null)
    {
        $this->version = new Version();

        if ($versionStr && is_string($versionStr)) {
            $this->parseVersion($versionStr);
        }
    }

    /**
     * Set version.
     *
     * @param Version $version
     *
     * @return VersionCompare
     */
    public function setVersion(Version $version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version.
     *
     * @return Version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Creates a new Version instance.
     *
     * @param string $versionStr
     *
     * @return VersionCompare
     */
    protected static function create($versionStr)
    {
        return new static($versionStr);
    }

    /**
     * Checks if the string is a valid string representation of a version.
     *
     * @param string $string
     *
     * @return bool
     */
    public static function checkVersionValid($string)
    {
        return true;
    }

    /**
     * Compares one version number string to another.
     *
     * @param string      $versionStr
     * @param string|null $operator
     *
     * @return int|bool
     */
    public function compareTo($versionStr, $operator = null)
    {
        $version1 = $this->getVersion();
        $version2 = static::create($versionStr)->getVersion();
        $compare = $this->actionCompare($version1, $version2);

        return $operator ? $this->resultBool($compare, $operator) : $compare;
    }

    /**
     * Compares two "PHP-standardized" version number strings.
     *
     * @param string      $version1
     * @param string      $version2
     * @param string|null $operator
     *
     * @return int|bool
     */
    public function compare($version1, $version2, $operator = null)
    {
        $version1 = static::create($version1)->getVersion();
        $version2 = static::create($version2)->getVersion();
        $compare = $this->actionCompare($version1, $version2);

        return $operator ? $this->resultBool($compare, $operator) : $compare;
    }

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
    protected function actionCompare(Version $version1, Version $version2)
    {
        $compare = $this->compareStandardVersion($version1, $version2);

        if (0 == $compare) {
            $compare = $this->comparePreReleaseVersion($version1, $version2);
        }

        return $compare;
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
                    return $lt;
                }

                $rightItem = $right[$index];

                if ($leftItem == $rightItem) {
                    continue;
                }

                if ($leftIsNumeric = is_numeric($leftItem) && $rightIsNumeric = is_numeric($rightItem)) {
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

    /**
     * Parses the version number string.
     *
     * @param string $versionStr
     */
    protected function parseVersion($versionStr)
    {
        if (static::checkVersionValid($versionStr)) {
            // throw exception
        }

        if (false !== strpos($versionStr, '+')) {
            list($versionStr, $buildMetadata) = explode('+', $versionStr);
            $buildMetadata = explode('.', $buildMetadata);
            $this->version->setBuildMetadata($buildMetadata);
        }

        if (false !== ($pos = strpos($versionStr, '-'))) {
            $original = $versionStr;
            $versionStr = substr($versionStr, 0, $pos);
            $preRelease = explode('.', substr($original, $pos + 1));
            $this->version->setPreRelease($preRelease);
        }

        list($major, $minor, $patch) = array_map('intval', explode('.', $versionStr));

        $this->version->setMajor($major);
        $this->version->setMinor($minor);
        $this->version->setPatch($patch);
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
    protected function resultBool($compare, $operator)
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
}
