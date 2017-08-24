<?php

namespace Emanci\VersionCompare;

class Version
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
     * @var array
     */
    protected $preRelease;

    /**
     * The build metadata identifiers.
     *
     * @var array
     */
    protected $buildMetadata;

    /**
     * Set major.
     *
     * @param string $major
     *
     * @return Version
     */
    public function setMajor($major)
    {
        $this->major = $major;

        return $this;
    }

    /**
     * Get major.
     *
     * @return int
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set minor.
     *
     * @param string $minor
     *
     * @return Version
     */
    public function setMinor($minor)
    {
        $this->minor = $minor;

        return $this;
    }

    /**
     * Get minor.
     *
     * @return int
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * Set patch.
     *
     * @param string $patch
     *
     * @return Version
     */
    public function setPatch($patch)
    {
        $this->patch = $patch;

        return $this;
    }

    /**
     * Get patch.
     *
     * @return int
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * Set preRelease.
     *
     * @param array $preRelease
     *
     * @return Version
     */
    public function setPreRelease(array $preRelease)
    {
        $this->preRelease = $preRelease;

        return $this;
    }

    /**
     * Get preRelease.
     *
     * @return array
     */
    public function getPreRelease()
    {
        return $this->preRelease;
    }

    /**
     * Set buildMetadata.
     *
     * @param array $buildMetadata
     *
     * @return Version
     */
    public function setBuildMetadata(array $buildMetadata)
    {
        $this->buildMetadata = $buildMetadata;

        return $this;
    }

    /**
     * Get buildMetadata.
     *
     * @return array
     */
    public function getBuildMetadata()
    {
        return $this->buildMetadata;
    }

    /**
     * Checks if the version number is stable.
     *
     * @return bool TRUE if it is stable, FALSE if not
     */
    public function isStable()
    {
        return empty($this->preRelease) && $this->major !== 0;
    }
}
