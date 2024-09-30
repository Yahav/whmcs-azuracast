<?php

namespace WHMCS\Module\Server\AzuraCast;

use Illuminate\Database\Eloquent\Model;

class Service
{
    /**
     * @var int Bitrate in Kbps
     */
    private int $maxBitrate;
    private int $maxMounts;
    private int $maxHlsStreams;
    /**
     * @var int Max Storage Space in MB
     */
    private int $mediaStorage, $recordingsStorage, $podcastsStorage;

    private int|string $maxListeners;
    private string $statioName;
    private string $userEmail;
    private string $userFullName;
    private string $password;
    private string $serverType;
    private Model $model;

    public function __construct(array $params)
    {
        $this->maxBitrate = $params['configoption1'] ?? 0;
        $this->maxMounts = $params['configoption2'] ?? 0;
        $this->maxHlsStreams = $params['configoption3'] ?? 0;
        $this->mediaStorage = $params['configoption4'];
        $this->recordingsStorage = $params['configoption5'];
        $this->podcastsStorage = $params['configoption6'];
        $this->maxListeners = $params['configoption7'];
        $this->serverType = $params['configoption8'];
        $this->password = $params['password'];
        $this->statioName = $params['customfields']['Station Name'];
        $this->userFullName = $params['clientsdetails']['fullname'];
        $this->userEmail = $params['clientsdetails']['email'];
        $this->model = $params['model'];
    }

    public function getMaxBitrate(): int
    {
        return $this->maxBitrate;
    }

    public function getMaxMounts(): int
    {
        return $this->maxMounts;
    }

    public function getMaxHlsStreams(): int
    {
        return $this->maxHlsStreams;
    }

    public function getMediaStorage(): string
    {
        return $this->mediaStorage;
    }

    public function getMediaStorageInBytes(): int
    {
        return $this->mediaStorage * 1000000;
    }

    public function getRecordingsStorage(): string
    {
        return $this->recordingsStorage;
    }

    public function getRecordingsStorageInBytes(): int
    {
        return $this->recordingsStorage * 1000000;
    }

    public function getPodcastsStorage(): string
    {
        return $this->podcastsStorage;
    }

    public function getPodcastsStorageInBytes(): int
    {
        return $this->podcastsStorage * 1000000;
    }

    public function getMaxListeners(): int|string
    {
        return $this->maxListeners;
    }

    public function getStationName(): string
    {
        return $this->statioName;
    }

    public function getStationShortName(): string
    {
        return strtolower(str_replace(' ', '_', $this->statioName));
    }

    public function getServerType(): string
    {
        return $this->serverType;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getUserFullName(): string
    {
        return $this->userFullName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function serviceProperties(): \WHMCS\Service\Properties
    {
        return $this->model->serviceProperties;
    }

    public function setStationId(int $stationId): void
    {
        $this->model->serviceProperties->save(['stationId' => $stationId]);
    }

    public function getStationId(): ?int
    {
        return $this->model->serviceProperties->get('stationId');
    }

    public function setUserId(int $userId): void
    {
        $this->model->serviceProperties->save(['userId' => $userId]);
    }

    public function getUserId(): ?int
    {
        return $this->model->serviceProperties->get('userId');
    }

    public function setRoleId(int $roleId): void
    {
        $this->model->serviceProperties->save(['roleId' => $roleId]);
    }

    public function getRoleId(): ?int
    {
        return $this->model->serviceProperties->get('roleId');
    }

    public function setMediaStorageId(int $mediaStorageId): void
    {
        $this->model->serviceProperties->save(['mediaStorageId' => $mediaStorageId]);
    }

    public function getMediaStorageId(): ?int
    {
        return $this->model->serviceProperties->get('mediaStorageId');
    }

    public function setRecordingsStorageId(int $recordingsStorageId): void
    {
        $this->model->serviceProperties->save(['recordingsStorageId' => $recordingsStorageId]);
    }

    public function getRecordingsStorageId(): ?int
    {
        return $this->model->serviceProperties->get('recordingsStorageId');
    }

    public function setPodcastsStorageId(int $podcastsStorageId): void
    {
        $this->model->serviceProperties->save(['podcastsStorageId' => $podcastsStorageId]);
    }

    public function getPodcastsStorageId(): ?int
    {
        return $this->model->serviceProperties->get('podcastsStorageId');
    }

    public function getModel(): Model
    {
        return $this->model;
    }

}