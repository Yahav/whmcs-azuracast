<?php
/**
 * WHMCS Azuracast Provisoioning Module
 *
 * This module allows you to provision AzuraCast instances from WHMCS
 *
 * When setting up a new AzuraCast product in WHMCS, you will need to set the following Custom Fiels:
 * Field Name: Station Name
 * Field Type: Text Box
 * Field Description: The Station Name - English Characters, Numbers and Spaces Only.
 * Validation: /^[A-Za-z0-9 ]+$/
 * Required Field, Show on Order Form
 *
 * @written_by Yahav [DOT] Shasha [AT] gmail [DOT] com
 * @license Within the Lib folder there are some modified files from the [official AzuraCast PHP SDK](https://github.com/AzuraCast/php-api-client) (Apache-2.0 license)
 * @license The rest is under "Do whatever you want" License
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Module\Server\AzuraCast\Client;
use WHMCS\Module\Server\AzuraCast\Dto\RoleDto;
use WHMCS\Module\Server\AzuraCast\Service;
use WHMCS\Database\Capsule;

const AZURACAST_UPDATE_USER_PASSWORD_ON_ANOTHER_STATION_CREATION = false;
/**
 * Define module related meta data.
 *
 * @see https://developers.whmcs.com/provisioning-modules/meta-data-params/
 *
 * @return array
 */
function azuracast_MetaData()
{
    return array(
        'DisplayName' => 'AzuraCast Module',
        'APIVersion' => '1.1',
        'RequiresServer' => true,
        'DefaultNonSSLPort' => '80',
        'DefaultSSLPort' => '443',
    );
}

/**
 * Define product configuration options.
 *
 * @see https://developers.whmcs.com/provisioning-modules/config-options/
 *
 * @return array
 */
function azuracast_ConfigOptions()
{
    return array(
        'Maximum Bitrate' => [
            'Type' => 'text',
            'Size' => '10',
            'Default' => '128',
            'Description' => 'Enter in Kbps',
        ],
        'Maximum Mounts' => [
            'Type' => 'text',
            'Size' => '10',
            'Default' => '2',
            'Description' => 'Maximum allowed Mount Points',
        ],
        'Maximum HLS Streams' => [
            'Type' => 'text',
            'Size' => '10',
            'Default' => '2',
            'Description' => 'Maximum allowed HLS Streams',
        ],
        'Media Storage Limit' => [
            'Type' => 'text',
            'Size' => '10',
            'Default' => '1000',
            'Description' => 'Enter in Mb',
        ],
        'Recordings Storage Limit' => [
            'Type' => 'text',
            'Size' => '10',
            'Default' => '1000',
            'Description' => 'Enter in Mb',
        ],
        'Podcasts Storage Limit' => [
            'Type' => 'text',
            'Size' => '10',
            'Default' => '1000',
            'Description' => 'Enter in Mb',
        ],
        'Maximum Listeners' => [
            'Type' => 'text',
            'Size' => '10',
            'Default' => '100',
            'Description' => 'Maximum Number of Listeners',
        ],
        'Server Type' => [
            "FriendlyName" => "Server Type",
            "Type" => "dropdown",
            "Options" => "icecast,shoutcast",
            "Description" => "The Frontend Type of the Station",
            "Default" => "icecast",
        ]
    );
}

/**
 * Provision a new instance of a product/service.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function azuracast_CreateAccount(array $params)
{
    $service = new Service($params);
    $azuracast = azuracast_ApiClient($params);

    try {
        // Create a new Station
        /** @var \WHMCS\Module\Server\AzuraCast\Dto\StationDto $station */
        $station = $azuracast->admin()->stations()->create($service);
        $service->setStationId($station->getId());
        $service->setMediaStorageId($station->getMediaStorageId());
        $service->setRecordingsStorageId($station->getRecordingsStorageId());
        $service->setPodcastsStorageId($station->getPodcastsStorageId());

        // Modify Station's Storage Quota for each type
        $storage = $azuracast->admin()->storage()->update($service);

        // Create a role for this station
        $role = $azuracast->admin()->roles()->create("Station {$station->getId()} Role", [], [$station->getId() => ["manage station automation", "nanage station profile", "manage station broadcasting", "manage station media", "manage station mounts", "manage station podcasts", "manage station remotes", "manage station streamers", "manage station web hooks", "view station management", "view station reports"]]);
        $service->setRoleId($role->getId());

        // Look for other provisioned services at the same server
        // (Which means there's already an AzuraCast user associated with the client)
        $user = null;
        $otherServices = azuracast_GetOtherActiveServicesAtSameServerForServiceModel($service->getModel());
        if ($otherServices->isNotEmpty())
        {
            $azuracastUserId = $otherServices->first()->serviceProperties->get('userId');
            $user = $azuracast->admin()->users()->get($azuracastUserId);
        }

//        if ($user === null) {
//            // Look for existing user with the same email address
//            $user = $azuracast->admin()->users()->searchByEmail($service->getUserEmail());
//        }

        // If user doesn't exists, create it
        if ($user === null) {
            $user = $azuracast->admin()->users()->create(
                $service->getUserEmail(),
                $service->getPassword(),
                $service->getUserFullName(),
                'en_US',
                [['id' => $role->getId()]]
            );
        }
        else {
            // Update user's role
            $newRoles = azuracast_GetCurrentUserRolesArray($user->getRoles());
            $newRoles[] = ['id' => $role->getId()];
            $user = $azuracast->admin()->users()->update(
                $user->getId(),
                $service->getUserEmail(),
                AZURACAST_UPDATE_USER_PASSWORD_ON_ANOTHER_STATION_CREATION ? $service->getPassword() : '',
                $service->getUserFullName(),
                'en_US',
                $newRoles,
                $user->getCreatedAt(),
            );

            if (AZURACAST_UPDATE_USER_PASSWORD_ON_ANOTHER_STATION_CREATION)
            {
                // Update the new password for all other related services
                // This means the existing AzuraCast user's password will be changed
                // This is inconvinient, but we need to do it IF we want to keep the password in WHMCS in sync with AzuraCast
                $otherServices->each(function (WHMCS\Service\Service $otherService) use ($service) {
                    /** @var \Illuminate\Database\Eloquent\Model $otherService */
                    $otherService->serviceProperties->save(['Password' => $service->getPassword()]);
                });
            }

        }
        $service->setUserId($user->getId());


    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'azuracast',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Suspend an instance of a product/service.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function azuracast_SuspendAccount(array $params)
{
    try {
        $service = new Service($params);
        $azuracast = azuracast_ApiClient($params);

        // Update the station
        $azuracast->admin()->stations()->update($service, false);

    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'azuracast',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Un-suspend instance of a product/service.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function azuracast_UnsuspendAccount(array $params)
{
    try {
        $service = new Service($params);
        $azuracast = azuracast_ApiClient($params);

        // Update the station
        $azuracast->admin()->stations()->update($service, true);

    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'azuracast',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Terminate instance of a product/service.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function azuracast_TerminateAccount(array $params)
{
    try {

        $service = new Service($params);
        $azuracast = azuracast_ApiClient($params);

        // Remove User Role
        $azuracast->admin()->roles()->delete($service->getRoleId());

        // Remove Station
        $azuracast->admin()->stations()->delete($service->getStationId());

        // Check if WHMCS client has another service
        // If he doesn't, remove the user
        $otherServices = azuracast_GetOtherActiveServicesAtSameServerForServiceModel($service->getModel());
        if ($otherServices->isEmpty())
        {
            $azuracast->admin()->users()->delete($service->getUserId());
        }

    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'azuracast',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Change the password for an instance of a product/service.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function azuracast_ChangePassword(array $params)
{
    try {
        $service = new Service($params);
        $azuracast = azuracast_ApiClient($params);

        $currentUser = $azuracast->admin()->users()->get($service->getUserId());

        // Update the user's password
        $user = $azuracast->admin()->users()->update(
            $currentUser->getId(),
            $currentUser->getEmail(),
            $service->getPassword(),
            $currentUser->getName(),
            $currentUser->getLocale(),
            azuracast_GetCurrentUserRolesArray($currentUser->getRoles()),
            $currentUser->getCreatedAt(),
        );

        // Update the new password for all other related services
        $newPassword = $service->getPassword();
        $otherServices = azuracast_GetOtherActiveServicesAtSameServerForServiceModel($service->getModel());
        if ($otherServices->isNotEmpty())
        {
            $otherServices->each(function (WHMCS\Service\Service $otherService) use ($newPassword) {
                /** @var \Illuminate\Database\Eloquent\Model $otherService */
                $otherService->serviceProperties->save(['Password' => $newPassword]);
            });
        }

    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'azuracast',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Upgrade or downgrade an instance of a product/service.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function azuracast_ChangePackage(array $params)
{
    try {
        $service = new Service($params);
        $azuracast = azuracast_ApiClient($params);

        // Update the station with the new service
        $azuracast->admin()->stations()->update($service, true);

        // Modify Station's Storage Quota for each type
        $storage = $azuracast->admin()->storage()->update($service);

    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'azuracast',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Test connection with the given server parameters.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return array
 */
function azuracast_TestConnection(array $params)
{
    try {
        $azuracast = azuracast_ApiClient($params);
        $azuracast->admin()->serverStats()->get();

        $success = true;
        $errorMsg = '';
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'azuracast',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        $success = false;
        $errorMsg = $e->getMessage();
    }

    return array(
        'success' => $success,
        'error' => $errorMsg,
    );
}

/**
 * ----------------------------------------------------------------------------------
 * THIS DOESN'T WORK YET AS AZURACAST DOESN'T HAVE AN API ENDPOINT FOR USER LOGIN
 * ----------------------------------------------------------------------------------
 *
 * Perform single sign-on for a given instance of a product/service.
 *
 * Called when single sign-on is requested for an instance of a product/service.
 *
 * When successful, returns an URL to which the user should be redirected.
 *
 * @param array $params common module parameters
 *
 * @return array
 *@see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 */
/**
function azuracast_ServiceSingleSignOn(array $params)
{
    $return = array(
        'success' => false,
    );
    try {

        $service = new Service($params);
        $azuracast = azuracast_ApiClient($params);
        $loginUrl = $azuracast->admin()->stations()->login($service->getStationId());

        $return = array(
            'success' => true,
            'redirectTo' => $loginUrl,
        );

    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'azuracast',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        $return['errorMsg'] = $e->getMessage();
        $response = $e->getMessage();
        $formattedResponse = $e->getTraceAsString();
    }

    return $return;
}
*/

function azuracast_ApiClient($params) : Client
{
    $host = 'https://' . $params['serverhostname'];
    $apiKey = $params['serveraccesshash'];
    return Client::create($host, $apiKey);
}

/**
 * @param RoleDto[] $existingUserRoles
 * @return array
 */
function azuracast_GetCurrentUserRolesArray(array $existingUserRoles)
{
    $roles = [];
    foreach ($existingUserRoles as $existingUserRole) {
        $roles[] = ['id' => $existingUserRole->getId()];
    }

    return $roles;
}

function azuracast_GetOtherActiveServicesAtSameServerForServiceModel(WHMCS\Service\Service $serviceModel): \Illuminate\Database\Eloquent\Collection
{
    $currentServerId = $serviceModel->server;
    $currentServiceId = $serviceModel->id;

    return $serviceModel->client->services()->whereIn('domainStatus', ['Active', 'Suspended'])->where('server', $currentServerId)->where('id', '!=', $currentServiceId)->get();
}