<?php

namespace Emanci\VersionCompare;

/**
 * 按照"语义化版本控制规范"比较版本号.
 *
 * @link http://semver.org Semantic Versioning
 */
class VersionCompare
{
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
    public static function checkValid($string)
    {
        return true;
    }

    /**
     * Compares one version string to another.
     *
     * @param string $versionStr
     *
     * @return bool
     */
    public function compareTo($versionStr)
    {
    }

    /**
     * Compares two "PHP-standardized" version number strings.
     *
     * @param string $version1
     * @param string $operator
     * @param string $version2
     *
     * @return bool
     */
    public function compare($version1, $operator, $version2)
    {
        $version1 = static::create($version1)->getVersion();
        $version2 = static::create($version2)->getVersion();
        $comp = $this->execCompareTask($version1, $version2);

        return $this->resultBool($comp, $operator);
    }

    /**
     * Using a predefined task to compares version number strings.
     *
     * returns -1 if the first version is lower than the second, 0 if they are equal, and 1 if the second is lower
     *
     * @param string $version1
     * @param string $version2
     *
     * @return int
     */
    protected function execCompareTask($version1, $version2)
    {
        $comp = 0;
        $compareTasks = ['standardVersionTask', 'preReleaseVersionTask', 'buildMetadataVersionTask'];

        array_walk($compareTasks, function ($value) use (&$comp, $version1, $version2) {
            if (!$comp) {
                $comp = call_user_func_array([$this, $value], [$version1, $version2]);
            }
        });

        return $comp;
    }

    /**
     * Compares the standard version.
     *
     * 1.0.0 < 2.0.0 < 2.1.0 < 2.1.1
     *
     * @param string $version1
     * @param string $version2
     *
     * @return int
     */
    protected function standardVersionTask($version1, $version2)
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
     * @param string $version1
     * @param string $version2
     *
     * @return int
     */
    protected function preReleaseVersionTask($version1, $version2)
    {
        if ($preRelease1 = $version1->getPreRelease() ||
            $preRelease2 = $version2->getPreRelease()) {
            if ($preRelease1 && !$preRelease2) {
                return -1;
            }

            if (!$preRelease1 && $preRelease2) {
                return 1;
            }

            // 0
        }
    }

    /**
     * Compares the build metadata version.
     *
     * @param string $version1
     * @param string $version2
     *
     * @return int
     */
    protected function buildMetadataVersionTask($version1, $version2)
    {
        if ($buildMetadata1 = $version1->getBuildMetadata() ||
            $buildMetadata2 = $version2->getBuildMetadata()) {
            if ($buildMetadata1 && !$buildMetadata2) {
                return -1;
            }

            if (!$buildMetadata1 && $buildMetadata2) {
                return 1;
            }

            // 0
        }
    }

    /**
     * Parses the version number string.
     *
     * @param string $versionStr
     */
    protected function parseVersion($versionStr)
    {
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

        $versions = explode('.', $versionStr);

        $this->version->setMajor((int) $versions[0]);

        if (isset($versions[1])) {
            $this->version->setMinor((int) $versions[1]);
        }

        if (isset($versions[2])) {
            $this->version->setPatch((int) $versions[2]);
        }
    }

    /**
     * Returns a Boolean value.
     *
     * Can use the comparison operator
     * <、 lt、<=、 le、>、 gt、>=、 ge、==、 =、eq、 !=、<> and ne
     *
     * @param int    $comp
     * @param string $operator
     *
     * @return bool
     */
    protected function resultBool($comp, $operator)
    {
        if (!strncmp($operator, '<', 1) || !strncmp($operator, 'lt', 1)) {
            return $comp == -1;
        }

        if (!strncmp($operator, '<=', 2) || !strncmp($operator, 'le', 2)) {
            return $comp != 1;
        }

        if (!strncmp($operator, '>', 1) || !strncmp($operator, 'gt', 1)) {
            return $comp == 1;
        }

        if (!strncmp($operator, '>=', 2) || !strncmp($operator, 'ge', 2)) {
            return $comp != -1;
        }

        if (!strncmp($operator, '==', 2) || !strncmp($operator, '=', 2) || !strncmp($operator, 'eq', 2)) {
            return $comp == 0;
        }

        if (!strncmp($operator, '!=', 2) || !strncmp($operator, '<>', 2) || !strncmp($operator, 'ne', 2)) {
            return $comp != 0;
        }

        return null;
    }
}
