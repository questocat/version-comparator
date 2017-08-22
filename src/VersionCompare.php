<?php

namespace Emanci\VersionCompare;

/**
 * @link http://semver.org Semantic Versioning
 */
class VersionCompare
{
    /**
     * The major version number.
     *
     * @var int
     */
    protected $major = 0;

    /**
     * The minor version number.
     *
     * @var int
     */
    protected $minor = 0;

    /**
     * The patch number.
     *
     * @var int
     */
    protected $patch = 0;

    /**
     * The pre-release version number.
     *
     * @var mixed
     */
    protected $preRelease;

    /**
     * The build metadata information.
     *
     * @var mixed
     */
    protected $buildMetadata;

    protected $comps = [
        -1 => '<',
        0 => '==',
        1 => '>',
    ];

    /**
     * VersionCompare construct.
     *
     * @param string $version
     */
    public function __construct($version = null)
    {
        if ($version && is_string($version)) {
            $this->parseVersion($version);
        }
    }

    public function setMajor($major)
    {
        $this->major = $major;
    }

    public function setMinor($minor)
    {
        $this->minor = $minor;
    }

    public function setPatch($patch)
    {
        $this->patch = $patch;
    }

    public function setPreRelease($preRelease)
    {
        $this->preRelease = $preRelease;
    }

    public function setBuildMetadata($buildMetadata)
    {
        $this->buildMetadata = $buildMetadata;
    }

    public function getMajor()
    {
        return $this->major;
    }

    public function getMinor()
    {
        return $this->minor;
    }

    public function getPatch()
    {
        return $this->patch;
    }

    public function getPreRelease()
    {
        return $this->preRelease;
    }

    public function getBuildMetadata()
    {
        return $this->buildMetadata;
    }

    /**
     * Creates a new Version instance.
     *
     * @param string $version
     *
     * @return Version
     */
    public static function create($version)
    {
        return new static($version);
    }

    public function compare($version1, $operator, $version2)
    {
        $version1 = static::create($version1);
        $version2 = static::create($version2);

        $version1Str = strval($version1->getMajor().$version1->getMinor().$version1->getPatch());
        $version2Str = strval($version2->getMajor().$version2->getMinor().$version2->getPatch());

        if ($comp = version_compare($version1Str, $version2Str)) {
            return $this->comps[$comp] == $operator;
        }

        // continue
    }

    protected function parseVersion($version)
    {
        if (false !== strpos($version, '+')) {
            list($version, $buildMetadata) = explode('+', $version);
            $this->setBuildMetadata($buildMetadata);
        }

        if (false !== strpos($version, '-')) {
            list($version, $preRelease) = explode('-', $version);
            $this->setPreRelease($preRelease);
        }

        $versions = explode('.', $version);

        $this->setMajor($versions[0]);

        if (isset($versions[1])) {
            $this->setMinor($versions[1]);
        }

        if (isset($versions[2])) {
            $this->setPatch($versions[2]);
        }
    }
}
