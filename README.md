
# WHMCS AzuraCast Server Provisioning Module

The most complete/comprehensive AzuraCast provisioning module for WHMCS.
*Requested at https://features.azuracast.com/suggestions/65007/whmcs-module

![Module Settings Screenshot](https://files.catbox.moe/lzskpn.png)

## Features

- Has the following configurable options per product:
    - Server Type: Icecast, Shoutcast
    - Maximum Bitrate
    - Maximum Mount Points & HLS Streams
    - Maximum Listeners
    - Maximum Storage space (Media Storage, Podcasts Storage and Recordings storage)
- Station Creation, Suspension, Unsuspension and Termination
- Packages Upgrade/Downgrade
- Password Change
- Detection of multiple (same module, same server) products for the same client and provisioning within the same AzuraCast user.
- Client Sign On functionality (Not available until the functionality will be added to AzuraCast itself.)

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
*  Within the Lib folder there are some modified files from the [official AzuraCast PHP SDK](https://github.com/AzuraCast/php-api-client) (Apache-2.0 license)

