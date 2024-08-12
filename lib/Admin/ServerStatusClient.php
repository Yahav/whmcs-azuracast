<?php
declare(strict_types=1);

namespace WHMCS\Module\Server\AzuraCast\Admin;

use WHMCS\Module\Server\AzuraCast\AbstractClient;
use WHMCS\Module\Server\AzuraCast\Dto;
use WHMCS\Module\Server\AzuraCast\Exception;

class ServerStatusClient extends AbstractClient
{
    /**
     * @return array
     *
     * @throws Exception\AccessDeniedException
     * @throws Exception\ClientRequestException
     */
    public function get(): array
    {
        $serverStats = $this->request(
            'GET',
            'admin/server/stats'
        );


        return $serverStats;
    }
}
