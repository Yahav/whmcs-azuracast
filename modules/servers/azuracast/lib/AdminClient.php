<?php
declare(strict_types=1);

namespace WHMCS\Module\Server\AzuraCast;

use WHMCS\Module\Server\AzuraCast\Admin;

class AdminClient extends AbstractClient
{

    /**
     * @return Admin\UsersClient
     */
    public function users(): Admin\UsersClient
    {
        return new Admin\UsersClient($this->httpClient);
    }

    /**
     * @return Admin\StationsClient
     */
    public function stations(): Admin\StationsClient
    {
        return new Admin\StationsClient($this->httpClient);
    }

    /**
     * @return Dto\PermissionsDto
     *
     * @throws Exception\AccessDeniedException
     * @throws Exception\ClientRequestException
     */
    public function permissions(): Dto\PermissionsDto
    {
        $permissionsData = $this->request('GET', 'admin/permissions');
        return Dto\PermissionsDto::fromArray($permissionsData);
    }

    /**
     * @return Admin\RolesClient
     */
    public function roles(): Admin\RolesClient
    {
        return new Admin\RolesClient($this->httpClient);
    }

    /**
     * @return Admin\StorageClient
     */
    public function storage(): Admin\StorageClient
    {
        return new Admin\StorageClient($this->httpClient);
    }

    /**
     * @return Admin\ServerStatusClient
     */
    public function serverStats(): Admin\ServerStatusClient
    {
        return new Admin\ServerStatusClient($this->httpClient);
    }

}
