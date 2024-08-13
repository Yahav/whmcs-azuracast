<?php
declare(strict_types=1);

namespace WHMCS\Module\Server\AzuraCast\Dto;

use JsonSerializable;

class StationDto implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var bool
     */
    protected $isEnabled;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $shortName;

    /**
     * @var string
     */
    protected $radioBaseDir;

    /**
     * @var int
     */
    protected $maxBitrate;

    /**
     * @var int
     */
    protected $maxMounts;

    /**
     * @var int
     */
    protected $maxHlsStreams;

    /**
     * @var ?int
     */
    protected $maxListeners;

    /**
     * @var int
     */
    protected $mediaStorageId;

    /**
     * @var int
     */
    protected $recordingsStorageId;

    /**
     * @var int
     */
    protected $podcastsStorageId;


    /**
     * @param int $id
     * @param bool $isEnabled
     * @param string $name
     * @param string $shortName
     * @param string $radioBaseDir
     * @param int $maxBitrate
     * @param int $maxMounts
     * @param int $maxHlsStreams
     * @param ?int $maxListeners
     * @param int $mediaStorageId
     * @param int $recordingsStorageId
     * @param int $podcastsStorageId
     */
    public function __construct(
        int $id,
        bool $isEnabled,
        string $name,
        string $shortName,
        string $radioBaseDir,
        int $maxBitrate,
        int $maxMounts,
        int $maxHlsStreams,
        ?int $maxListeners,
        int $mediaStorageId,
        int $recordingsStorageId,
        int $podcastsStorageId
    ) {
        $this->id = $id;
        $this->isEnabled = $isEnabled;
        $this->name = $name;
        $this->shortName = $shortName;
        $this->radioBaseDir = $radioBaseDir;
        $this->maxBitrate = $maxBitrate;
        $this->maxMounts = $maxMounts;
        $this->maxHlsStreams = $maxHlsStreams;
        $this->maxListeners = $maxListeners;
        $this->mediaStorageId = $mediaStorageId;
        $this->recordingsStorageId = $recordingsStorageId;
        $this->podcastsStorageId = $podcastsStorageId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return StationDto
     */
    public function setIsEnabled(bool $isEnabled): StationDto
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $shortName
     * @return StationDto
     */
    public function setShortName(string $shortName): StationDto
    {
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * @return string
     */
    public function getRadioBaseDir(): string
    {
        return $this->radioBaseDir;
    }

    /**
     * @param string $radioBaseDir
     * @return StationDto
     */
    public function setRadioBaseDir(string $radioBaseDir): StationDto
    {
        $this->radioBaseDir = $radioBaseDir;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxBitrate(): int
    {
        return $this->maxBitrate;
    }

    /**
     * @param int $maxBitrate
     * @return StationDto
     */
    public function setMaxBitrate(int $maxBitrate): StationDto
    {
        $this->maxBitrate = $maxBitrate;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxMounts(): int
    {
        return $this->maxMounts;
    }

    /**
     * @param int $maxMounts
     * @return StationDto
     */
    public function setMaxMounts(int $maxMounts): StationDto
    {
        $this->maxMounts = $maxMounts;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxHlsStreams(): int
    {
        return $this->maxHlsStreams;
    }

    /**
     * @param int $maxHlsStreams
     * @return StationDto
     */
    public function setMaxHlsStreams(int $maxHlsStreams): StationDto
    {
        $this->maxHlsStreams = $maxHlsStreams;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxListeners(): int
    {
        return $this->maxListeners;
    }

    /**
     * @param int $maxListeners
     * @return StationDto
     */
    public function setMaxListeners(int $maxListeners): StationDto
    {
        $this->maxListeners = $maxListeners;
        return $this;
    }

    /**
     * @return int
     */
    public function getMediaStorageId(): int
    {
        return $this->mediaStorageId;
    }

    /**
     * @param int $mediaStorageId
     * @return StationDto
     */
    public function setMediaStorageId(int $mediaStorageId): StationDto
    {
        $this->mediaStorageId = $mediaStorageId;
        return $this;
    }

    /**
     * @return int
     */
    public function getRecordingsStorageId(): int
    {
        return $this->recordingsStorageId;
    }

    /**
     * @param int $recordingsStorageId
     * @return StationDto
     */
    public function setRecordingsStorageId(int $recordingsStorageId): StationDto
    {
        $this->recordingsStorageId = $recordingsStorageId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPodcastsStorageId(): int
    {
        return $this->podcastsStorageId;
    }

    /**
     * @param int $podcastsStorageId
     * @return StationDto
     */
    public function setPodcastsStorageId(int $podcastsStorageId): StationDto
    {
        $this->podcastsStorageId = $podcastsStorageId;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'new_password' => $this->getNewPassword(),
            'name' => $this->getName(),
            'locale' => $this->getLocale(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'roles' => $this->getRoles(),
        ];
    }

    /**
     * @param mixed[] $stationData
     *
     * @return StationDto
     */
    public static function fromArray(array $stationData): self
    {
        return new self(
            $stationData['id'],
            $stationData['is_enabled'],
            $stationData['name'],
            $stationData['short_name'],
            $stationData['radio_base_dir'],
            $stationData['max_bitrate'],
            $stationData['max_mounts'],
            $stationData['max_hls_streams'],
            $stationData['frontend_config']['max_listeners'],
            $stationData['media_storage_location'],
            $stationData['recordings_storage_location'],
            $stationData['podcasts_storage_location']
        );
    }
}
