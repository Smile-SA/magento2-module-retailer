# Changelog

All notable changes to this project will be documented in this file.

## [2.1.0] - Unreleased
[2.1.0]: https://github.com/Smile-SA/magento2-module-retailer/compare/2.0.4...2.1.0

- Supported platforms: Magento 2.4.6 and later, Mage-OS 1.0.0 and later (tested up to Magento 2.4.9 and Mage-OS 4.0), PHP 8.1 to 8.5
- `composer.json` now states it: `php ^8.1` and `magento/framework >=103.0.6` (previously `php ^8.1` and `magento/framework >=103.0.4`)
- Magento 2.4.4 and 2.4.5 are supported by the 2.0.x line, from 2.0.5
- CI: static analysis and `setup:di:compile` run on Mage-OS, without Magento credentials

## [2.0.4] - 2026-11-24
[2.0.4]: https://github.com/Smile-SA/magento2-module-retailer/compare/2.0.3...2.0.4

- Security: the retailer web API no longer returns inactive retailers or sellers that are not retailers to guests and customers
- Security: guests and customers read retailers through the web API in the current store view only
- Security: the same restrictions apply to `RetailerRepositoryInterface::get()` on the storefront
- Add unit tests for the retailer web API plugin

## [2.0.3] - 2026-04-14
[2.0.3]: https://github.com/Smile-SA/magento2-module-retailer/compare/2.0.2...2.0.3

- Add implementation for retailer search results

## [2.0.2] - 2024-05-03
[2.0.2]: https://github.com/Smile-SA/magento2-module-retailer/compare/2.0.1...2.0.2

- API model docblocks

## [2.0.1] - 2024-05-03
[2.0.1]: https://github.com/Smile-SA/magento2-module-retailer/compare/2.0.0...2.0.1

- Retailer : Fix PSR-0

## [2.0.0] - 2023-09-20
[2.0.0]: https://github.com/Smile-SA/magento2-module-offer/compare/1.2.9.1...2.0.0

Dataset compatibility ES 2.11.x and PHP 8.2

- Fix Dynamic type declaration
- Fix Type hinting
- Replace `Zend_Date` by `DateTime`
- Remove `MutationObserver` support
- Fix UI Component Retailer Offer editing
- Replace `Zend_Validate` by `Laminas\Validator`
- Fix Retailer Grid Column Action
- Fix Retailer Grid Mass Actions
- Fix some translations
- Remove Temando/Shipping Plugin
