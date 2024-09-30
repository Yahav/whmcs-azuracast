<?php
declare(strict_types=1);

namespace WHMCS\Module\Server\AzuraCast\Admin;

use WHMCS\Module\Server\AzuraCast\AbstractClient;
use WHMCS\Module\Server\AzuraCast\Dto;
use WHMCS\Module\Server\AzuraCast\Dto\ApiKeyDto;
use WHMCS\Module\Server\AzuraCast\Dto\RoleDto;
use WHMCS\Module\Server\AzuraCast\Exception;
use WHMCS\Module\Server\AzuraCast\Service;

class StorageClient extends AbstractClient
{
    /**
     * @param Service $serviceDetails
     *
     * @return void
     *
     * @throws Exception\AccessDeniedException
     * @throws Exception\ClientRequestException
     */
    public function update(Service $serviceDetails): void
    {
        $storageTypes = ['Media', 'Podcasts', 'Recordings'];

        foreach ($storageTypes as $storageType) {
            $idGetterMethod = 'get' . $storageType . 'StorageId';
            $storageData = [
                'id' => $serviceDetails->$idGetterMethod(),
                'storageQuota' => $serviceDetails->{'get' . $storageType . 'Storage'}() . ' MB',
                'storageQuotaBytes' => $serviceDetails->{'get' . $storageType . 'StorageInBytes'}(),
            ];

            $this->request(
                'PUT',
                sprintf('admin/storage_location/%s', $serviceDetails->$idGetterMethod()),
                ['json' => $storageData]
            );
        }
    }
}
