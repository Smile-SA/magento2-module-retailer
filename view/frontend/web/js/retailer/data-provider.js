/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

define([
    'jquery'
], function ($) {
    'use strict';

    var options,
        storage,
        dataProvider;

    storage = $.initNamespaceStorage('mage-cache-storage').localStorage;

    dataProvider = {

        /**
         * @param retailerId The retailer Id
         *
         * @return {Promise}
         */
        getFromStorage: function (retailerId) {
            var retailer = storage.get("smile_retailer_" + retailerId);

            if (retailer) {
                var defer = $.Deferred();
                defer.resolve(retailer);

                return defer.promise();
            }

            return null;
        },

        /**
         * @param retailerId The retailer Id
         *
         * @return {Promise}
         */
        getFromServer: function (retailerId) {
            return $.getJSON(options.apiBaseUrl + "get/" + retailerId).fail(function (jqXHR) {
                throw new Error(jqXHR);
            });
        },

        /**
         * @param retailerId The retailer Id
         *
         * @return {Promise}
         */
        get: function (retailerId) {

            if (this.getFromStorage(retailerId) !== null) {
                return this.getFromStorage(retailerId);
            }

            return this.getFromServer(retailerId).done(function (retailer) {
                storage.set("smile_retailer_" + retailerId, retailer);
            });
        },

        /**
         * @param {Object} settings
         *
         * @constructor
         */
        'Smile_Retailer/js/retailer/data-provider': function (settings) {
            options = settings;
        }
    };

    return dataProvider;
});
