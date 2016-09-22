/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */


/*jshint browser:true jquery:true*/
/*global alert*/

define(['uiComponent', 'jquery', 'mage/template', 'mage/calendar', 'mage/cookies'], function (Component, $) {

    "use strict";

    /**
     * Object containing session data : The chosen retailer and the pickup date, if any.
     *
     * @type {{getSelectedStoreId: RetailerSession.getSelectedStoreId, getPickupDate: RetailerSession.getPickupDate}}
     */
    var RetailerSession = {
        getSelectedStoreId : function() {
            return $.mage.cookies.get('smile_retailer_id');
        },

        getPickupDate : function() {
            var date = $.mage.cookies.get('smile_retailer_pickupdate');
            if (date) {
                date = $.datepicker.parseDate($.datepicker.ISO_8601, date);
            }
            return date;
        }
    };

    /**
     * Store Constructor
     *
     * @param storeId
     * @param storeData
     * @constructor
     */
    var Store = function(storeId, storeData) {
        this.id       = storeId;
        this.name     = storeData.name;
        this.calendar = storeData.calendar;
    }

    /**
     * Store Picker.
     * Also contains a Date Picker if enabled in configuration.
     */
    return Component.extend({
        defaults: {
            displayDateFormat  : 'dd/mm/yy',
            internalDateFormat : $.datepicker.ISO_8601,
            selectedStoreId    : null,
            pickupDate         : null
        },

        /**
         * Widget initializing
         */
        initialize: function() {
            this._super();
            this.observe(['selectedStoreId', 'pickupDate']);
            this.initStores();
            console.log(this.displayPickupDate);
            this.selectedStoreId(RetailerSession.getSelectedStoreId());
            if (this.isPickupDateDisplayed()) {
                this.pickupDate(RetailerSession.getPickupDate());
            }
        },

        /**
         * Init the Store collection items.
         */
        initStores: function() {
            var stores    = [];
            this.storeById = {};

            for (var key in this.stores) {
                if (this.stores.hasOwnProperty(key)) {
                    var currentStore = new Store(key, this.stores[key]);
                    stores.push(currentStore);
                    this.storeById[key] = currentStore;
                }
            }
            this.stores = stores;
        },

        /**
         * Retrieve the currently selected Store.
         *
         * @returns {*}
         */
        getCurrentStore: function() {
            return this.storeById[this.selectedStoreId()];
        },

        /**
         * Binding when user changes the current Store.
         */
        onStoreChange: function() {
            if (this.isPickupDateDisplayed()) {
                this.pickupDate(null);
                this.initDatePicker();
            }
        },

        /**
         * Init the Date Picker element.
         *
         * @param pickerNode
         */
        initDatePicker: function(pickerNode) {
            if (pickerNode) {
                this.pickerNode = pickerNode;
            }

            $(pickerNode).datepicker("destroy");
            $(pickerNode).datepicker({
                beforeShowDay : this.filterAvailableDates.bind(this),
                onSelect      : this.onDatePick.bind(this),
                dateFormat    : this.internalDateFormat
            });
        },

        /**
         * Show the Date Picker
         */
        showPicker: function () {
            $(this.pickerNode).datepicker("show");
        },

        /**
         * Binding when a date is picked up
         *
         * @param date
         * @param datePicker
         */
        onDatePick: function(date, datePicker) {
            this.pickupDate($.datepicker.parseDate(this.internalDateFormat, date));
        },

        /**
         * Retrieve The Date Picker Label.
         *
         * @returns {string}
         */
        getDatePickerLabel: function() {
            var currentDate = this.pickupDate();
            var pickerLabel = 'Choose your delivery date ...';
            if (currentDate) {
                pickerLabel = 'Delivery date : ' + $.datepicker.formatDate(this.displayDateFormat, currentDate);
            }

            return pickerLabel;
        },

        /**
         * Filter available dates when rendering in the Date Picker.
         * Available Dates for a Store are coming from widget configuration.
         *
         * @param date
         * @returns {string[]}
         */
        filterAvailableDates: function(date) {
            var calendar      = this.getCurrentStore() ? this.getCurrentStore().calendar : [];
            var formattedDate = $.datepicker.formatDate(this.internalDateFormat, date);
            var isValidDate   = $.inArray(formattedDate, calendar) != -1;
            return [isValidDate, "", ""];
        },

        /**
         * Checks if the store/date picker can be validated.
         *
         * @returns {boolean}
         */
        canValidate : function() {
            if (this.isPickupDateDisplayed()) {
                return this.pickupDate() !== null && this.selectedStoreId() !== null;
            }
            return this.selectedStoreId() !== null;
        },

        /**
         * Validate the form and submit the Data.
         */
        validate: function() {
            var params = {
                'pickup_date' : $.datepicker.formatDate(this.internalDateFormat, this.pickupDate()),
                'retailer_id' : this.selectedStoreId()
            };

            $.ajax({
                url: this.updateUrl,
                data: params,
                type: 'post',
                beforeSend: function () {
                    $('body').trigger('processStart');
                },
                success: function () {
                    location.reload();
                },
                error : function() {
                    $('body').trigger('processStop');
                }
            });
        },

        /**
         * Check if the date picker should be displayed or not.
         *
         * @returns {*}
         */
        isPickupDateDisplayed: function() {
            return this.displayPickupDate;
        }
    });
});
