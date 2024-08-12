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
            $getterMethod = 'get' . $storageType . 'StorageId';
            $storageData = [
                'id' => $serviceDetails->$getterMethod(),
                'storageQuota' => $serviceDetails->getStorage() . ' MB',
                'storageQuotaBytes' => $serviceDetails->getStorageInBytes(),
            ];

            $this->request(
                'PUT',
                sprintf('admin/storage_location/%s', $serviceDetails->$getterMethod()),
                ['json' => $storageData]
            );
        }
    }
}
