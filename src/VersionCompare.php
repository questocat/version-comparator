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
     * @var array
     */
    protected $comps = [
        -1 => '<',
        0 => '==',
        1 => '>',
    ];

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

        $version1Str = strval($version1->getMajor().$version1->getMinor().$version1->getPatch());
        $version2Str = strval($version2->getMajor().$version2->getMinor().$version2->getPatch());

        if ($comp = version_compare($version1Str, $version2Str)) {
            return $this->comps[$comp] == $operator;
        }

        // continue
    }

    /**
     * Parses the version string.
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
}
