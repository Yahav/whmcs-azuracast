
# WHMCS AzuraCast Server Provisioning Module

This module enables you to provision AzuraCast instances from WHMCS.

*Requested at https://features.azuracast.com/suggestions/65007/whmcs-module

*This module requires the following PR to be merged:
https://github.com/AzuraCast/AzuraCast/pull/7388

## Features

- Has the following configurable options per product:
    - Server Type: Icecast, Shoutcast
    - Maximum Mount Points & HLS Streams
    - Maximum Listeners
    - Maximum Storage space (applies to: Media Storage, Podcasts Storage and Recordings storage)
- Station Creation, Suspension, Unsuspension and Termination
- Packages Upgrade/Downgrade
- Password Change
- Detection of multiple (same module, same server) products for the same client and provisioning within the same AzuraCast user.


## Installation

Copy the azuracast directory to your WHMCS installation dir under modules/servers/

When setting up a new AzuraCast product in WHMCS, you will need to set the following Custom Field:
 * Field Name: Station Name
 * Field Type: Text Box
 * Field Description: The Station Name - English Characters, Numbers and Spaces Only.
 * Validation: /^[A-Za-z0-9 ]+$/
 * Required Field, Show on Order Form
## License

"Do whatever you want license"
*  Within the Lib folder there are files from the [official AzuraCast PHP SDK](https://github.com/AzuraCast/php-api-client) (Apache-2.0 license)

