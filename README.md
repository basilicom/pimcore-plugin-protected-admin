ProtectedAdmin Pimcore Plugin
================================================
    
Developer info: [Pimcore at basilicom](http://basilicom.de/en/pimcore)

## Synopsis

This Pimcore http://www.pimcore.org plugin requires http basic authentication prior
to granting admin access. Username and password can be set via Website Settings.

## Code Example / Method of Operation

After installing the plugin there are new website settings available (under Settings > Website):
**protectedAdminUser**, **protectedAdminPassword**. Set them accordingly.

## Motivation

Even though Pimcore comes with great security it still makes sense to prevent access to admin through the
main domain, especially to any bots, script kiddies, etc.

## Installation

Add "basilicom-pimcore/protected-admin" as a requirement to the composer.json in the toplevel directory of your Pimcore installation. Then enable and install the plugin in Pimcore Extension Manager (under Extras > Extensions)

Example:

    {
        "require": {
            "basilicom-pimcore-plugin/protected-admin": ">=1.0.0"
        }
    }

## Troubleshooting

In case you lose access to the admin area due to misconfiguration you have two options:
- disable plugin by editing /website/var/config/extensions.xml (change the value to 0 or delete the whole line)
- remove the Website Settings by deleting the corresponding keys (protectedAdmin*)

## Contributors

* Igor Benko igor.benko@basilicom.de
* Christoph Luehr christoph.luehr@basilicom.de


## License

* GNU General Public License version 3 (GPLv3)
