The module is **free** and **open source**.

### 1. Self-installation
```
bin/magento maintenance:enable

composer require magecrafts/customer-location:*

bin/magento module:enable Magecrafts_CustomerLocation
bin/magento setup:upgrade
bin/magento cache:enable

bin/magento setup:di:compile

bin/magento setup:static-content:deploy -f
bin/magento maintenance:disable
```
