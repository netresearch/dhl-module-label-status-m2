# DHL Label Status extension

This extension adds status icons for orders created with the DHL Shipping
extension to the Magento® 2 orders grid.

## Description

This extension adds icons for DHL shipment status to the Magento® 2
orders grid.

This functionality was previously available as part of DHL Shipping.
Since DHL Shipping version 0.10.0 it is available as a separate add-on.

It is not designed to function without the DHL Shipping extension.

## Requirements

* `dhl/module-shipping-m2` >= 0.10.0
* Magento 2.2.4 to 2.3
* PHP 7.0.2 to 7.3

## Installation Instructions

The DHL Label Status extension can be installed via Composer:

    composer require dhl/module-label-status:*

### Enable Module

Once the source files are available, make them known to the application:

    ./bin/magento module:enable Dhl_LabelStatus
    ./bin/magento setup:upgrade

Last but not least, flush cache and compile.

    ./bin/magento cache:flush
    ./bin/magento setup:di:compile

## Uninstallation

The following sections describe how to uninstall the module from your Magento® 2 instance. 

To unregister the shipping module from the application, run the following command:

    ./bin/magento module:uninstall --remove-data Dhl_LabelStatus
    composer update
    
This will automatically remove source files, clean up the database, update package dependencies.

*Please note that automatic uninstallation is only available on Magento version 2.2 or newer.
On Magento 2.1 and below, please use the following manual uninstallation method.*

### Manual Steps

To uninstall the module manually, run the following commands in your project
root directory:

    ./bin/magento module:disable Dhl_LabelStatus
    composer remove dhl/module-label-status

To clean up the database, run the following commands:

    ALTER TABLE `sales_order_grid` DROP COLUMN `dhlshipping_label_status`;
    DROP TABLE `dhlshipping_label_status`;
    DELETE FROM `setup_module` WHERE `module` = 'Dhl_LabelStatus';

## Support

In case of questions or problems, please have a look at the
[Support Portal (FAQ)](http://dhl.support.netresearch.de/) first.

If the issue cannot be resolved, you can contact the support team via the
[Support Portal](http://dhl.support.netresearch.de/) or by sending an email
to <dhl.support@netresearch.de>.

## Developers

* Sebastian Ertner | [Netresearch DTT GmbH](http://www.netresearch.de/)
* Andreas Müller | [Netresearch DTT GmbH](http://www.netresearch.de/)
* Max Melzer | [Netresearch DTT GmbH](http://www.netresearch.de/)

## License

[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

## Copyright

(c) 2019 DHL Paket GmbH
