## Smile Retailer

It allows to add retailers info.

### Compatibility

| Module version | Magento | Mage-OS | PHP |
| --- | --- | --- | --- |
| 2.1.x | 2.4.6 and later (tested up to 2.4.9) | 1.0.0 and later (tested up to 4.0) | 8.1 to 8.5, as supported by the platform |
| 2.0.x, from 2.0.5 | 2.4.4 and 2.4.5 | not available | 8.1 |

Releases up to 2.0.4 are not covered by this table: they declared `php ^8.1` and `magento/framework >=103.0.4`, and their method signatures use native `mixed` or union types, which the code generator of Magento 2.4.4 and 2.4.5 rejects (`setup:di:compile` fails).

### Requirements

The module requires:

- [Seller](https://github.com/Smile-SA/magento2-module-seller) >= 2.0.*

### How to use

1. Install the module via Composer:

``` composer require smile/module-retailer ```

2. Enable it:

``` bin/magento module:enable Smile_Retailer ```

3. Install the module and rebuild the DI cache:

``` bin/magento setup:upgrade ```

### How to configure retailers

Go to magento backoffice

Menu : Sellers > Retailers

### Web API

`GET /V1/retailer/get/:retailerId` is open to anonymous callers.

For a guest or a customer, the retailer is loaded in the current store view, whatever store id is requested,
and the API answers "no such entity" when the seller is not a retailer, when the retailer is inactive in that
store view, or when no active store view can be resolved.
Admin users and integrations are not restricted.

This applies to every call to `RetailerRepositoryInterface::get()` made while serving a REST or SOAP request
or a storefront page, including calls made by other modules: a guest checkout request that refers to an inactive
retailer fails with the same error, and storefront code must expect a `NoSuchEntityException` for a retailer that
is inactive in the current store view.
