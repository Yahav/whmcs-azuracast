<?php
declare(strict_types=1);

namespace WHMCS\Module\Server\AzuraCast\Admin;

use WHMCS\Module\Server\AzuraCast\AbstractClient;
use WHMCS\Module\Server\AzuraCast\Dto;
use WHMCS\Module\Server\AzuraCast\Dto\ApiKeyDto;
use WHMCS\Module\Server\AzuraCast\Dto\RoleDto;
use WHMCS\Module\Server\AzuraCast\Dto\StationDto;
use WHMCS\Module\Server\AzuraCast\Exception;
use WHMCS\Module\Server\AzuraCast\Exception\AccessDeniedException;
use WHMCS\Module\Server\AzuraCast\Exception\ClientRequestException;
use WHMCS\Module\Server\AzuraCast\Service;

class StationsClient extends AbstractClient
{
    /**
     * @return Dto\StationDto[]
     *
     * @throws Exception\AccessDeniedException
     * @throws Exception\ClientRequestException
     */
    public function list(): array
    {
        $stationsData = $this->request('GET', 'admin/stations');

        $stations = [];
        foreach ($stationsData as $stationData) {
            $stations[] = Dto\StationDto::fromArray($stationData);
        }
        return $stations;
    }

    /**
     * @param int $stationId
     *
     * @return Dto\StationDto
     *
     * @throws Exception\AccessDeniedException
     * @throws Exception\ClientRequestException
     */
    public function get(int $stationId): Dto\StationDto
    {
        $stationData = $this->request(
            'GET',
            sprintf('admin/station/%s', $stationId)
        );

        return Dto\StationDto::fromArray($stationData);
    }

    /**
     * @param Service $serviceDetails
     *
     * @return Dto\StationDto
     *
     * @throws Exception\AccessDeniedException
     * @throws Exception\ClientRequestException
     */
    public function create(Service $serviceDetails): Dto\StationDto
    {
        $stationData = [
            'name' => $serviceDetails->getStationName(),
            'short_name' => $serviceDetails->getStationShortName(),
            'is_enabled' => true,
            'frontend_type' => $serviceDetails->getServerType(),
            'frontend_config' => ['max_listeners' => $serviceDetails->getMaxListeners()],
            'backend_type' => 'liquidsoap',
            'backend_config' => [
                'record_streams_bitrate' => $serviceDetails->getMaxBitrate()
            ],
            'enable_hls' => true,
            'api_history_items' => 5,
            'timezone' => 'UTC',
            'max_bitrate' => $serviceDetails->getMaxBitrate(),
            'max_mounts' => $serviceDetails->getMaxMounts(),
            'max_hls_streams' => $serviceDetails->getMaxMounts()
        ];

        $newStationData =  $this->request(
            'POST',
            'admin/stations',
            ['json' => $stationData]
        );

        return Dto\StationDto::fromArray($newStationData);
    }

    /**
     * @param Service $serviceDetails
     * @param bool $isEnabled
     * @return void
     *
     * @throws AccessDeniedException
     * @throws ClientRequestException
     */
    public function update(Service $serviceDetails, bool $isEnabled = true): void
    {
        $stationData = [
            'id' => $serviceDetails->getStationId(),
            'is_enabled' => $isEnabled,
            'frontend_type' => $serviceDetails->getServerType(),
            'frontend_config' => ['max_listeners' => $serviceDetails->getMaxListeners()],
            'backend_config' => [
                'record_streams_bitrate' => $serviceDetails->getMaxBitrate()
            ],
            'max_bitrate' => $serviceDetails->getMaxBitrate(),
            'max_mounts' => $serviceDetails->getMaxMounts(),
            'max_hls_streams' => $serviceDetails->getMaxMounts()
        ];

        $newStationData =  $this->request(
            'PUT',
            sprintf('admin/station/%s', $serviceDetails->getStationId()),
            ['json' => $stationData]
        );
    }

    /**
     * @param int $stationId
     *
     * @return void
     *
     * @throws Exception\AccessDeniedException
     * @throws Exception\ClientRequestException
     */
    public function delete(int $stationId): void
    {
        $this->request(
            'DELETE',
            sprintf('admin/station/%s', $stationId)
        );
    }
}
