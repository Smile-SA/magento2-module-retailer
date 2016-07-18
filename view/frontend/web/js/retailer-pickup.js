/*jshint browser:true jquery:true*/
define([
    'ko',
    'jquery',
    'underscore',
    'Smile_Retailer/js/retailer/data-provider',
    'Magento_Customer/js/customer-data',
    'mage/calendar'
], function (ko, $, _, retailerDataProvider, customerData) {
    'use strict';

    $.widget('smileRetailer.retailerPickup', {
        options: {
            datePickerConfig : {
                minDate : +2,
                maxDate : "+2M",
                internalDateFormat : $.datepicker.ISO_8601
            }
        },

        /**
         * Constructor. Will map elements to the picker.
         *
         * @private
         */
        _create: function () {
            this.datePicker = null;
            this.retailerPicker = null;
            this.dataProvider = retailerDataProvider;
            this.retailerData = null;

            if (this.options.datePicker) {
                this.initDatePicker();
            }

            if (this.options.retailerPicker) {
                this.initRetailerPicker();
            }

            if (this.options.placeHolder) {
                this.initPlaceHolder();
            }
        },

        /**
         * Init the placeholder for retailer picker.
         */
        initPlaceHolder : function()
        {
            this.placeHolder = $(this.options.placeHolder);
            if (this.getCustomerData("retailer_id") && (this.getCustomerData("pickup_date"))) {
                this.placeHolder.hide();
                this.element.show();
            } else {
                this.placeHolder.show();
                this.element.hide();
            }
            this.placeHolder.find("a").on("click", function() {this.element.show(); this.placeHolder.hide();}.bind(this));
        },

        /**
         * Init the retailer picker component.
         */
        initRetailerPicker : function()
        {
            this.retailerPicker = $(this.options.retailerPicker);
            if (this.getCustomerData("retailer_id")) {
                var retailerId = this.getCustomerData("retailer_id");
                this.retailerPicker.val(retailerId);
                this.updateRetailerData(parseInt(retailerId, 10));
            }
            this.retailerPicker.on('change', $.proxy(function (event) {this.setRetailer($(event.target).val());}, this));
        },

        /**
         * Init the date picker component.
         */
        initDatePicker : function()
        {
            this.datePicker = $(this.options.datePicker);
            if (this.getCustomerData("pickup_date")) {
                this.datePicker.val(this.getCustomerData("pickup_date"));
            }
            this.datePicker.on('change', $.proxy(function (event) {this.setPickupDate($(event.target).val());}, this));
        },

        /**
         * Set current retailer to this component.
         * Also set it to current customer data.
         *
         * @param retailerId
         */
        setRetailer : function(retailerId)
        {
            this.currentRetailer = retailerId;
            $.post(this.options.sessionSetUrl, { retailer_id: retailerId });
            this.resetPickupDate();
            this.updateRetailerData(retailerId);
        },

        /**
         * Set current pickup date to this component.
         * Also set it to current customer data.
         *
         * @param pickupDate
         */
        setPickupDate : function(pickupDate)
        {
            this.currentPickuPdate = pickupDate;
            $.post( this.options.sessionSetUrl, { retailer_id: this.currentRetailer.retailer_id, pickup_date: pickupDate } );
        },

        /**
         * Update current retailer data for a given Id
         * Retailer is a promise coming from the data provider
         *
         * @param retailerId
         */
        updateRetailerData : function(retailerId)
        {
            this.dataProvider.get(retailerId).done(function (data) {
                this.retailerData = data;
                this.updateDatePicker();
            }.bind(this));
        },

        /**
         * Update the date picker element according to current retailer data
         */
        updateDatePicker : function()
        {
            if (this.datePicker !== null && this.retailerData !== null) {
                this.datePicker.datepicker("destroy");
                // Set Proper callback to the date picker to show only available dates.
                this.datePicker.datepicker({
                    beforeShowDay: this.getRetailerBeforeShowDays(this.retailerData),
                    minDate: this.options.datePickerConfig.minDate,
                    maxDate: this.options.datePickerConfig.maxDate
                });

                this.datePicker.datepicker("refresh");
            }
        },

        /**
         * Retrieve date callback for the datepicker, to prevent display unavailable dates.
         *
         * @param retailerData A given retailer data
         *
         * @returns {Function}
         */
        getRetailerBeforeShowDays : function (retailerData)
        {
            // Parse standard closed days
            var standardCloseDays = this.getRetailerClosedDayWeek(retailerData);

            var specialDates = this.getRetailerSpecialDates(retailerData);
            // Parse special opening days
            var specialOpeningDays = specialDates.specialOpenings; //this.getRetailerSpecialOpeningDates(retailerData);

            // Parse special closing days
            var specialClosingDays = specialDates.specialClosings;//this.getRetailerSpecialClosingDates(retailerData);

            var internalDateFormat = this.options.datePickerConfig.internalDateFormat;

            return function(date) {

                var day = date.getDay();
                var string = $.datepicker.formatDate(internalDateFormat, date);
                var result = true;

                // Given weekday is closed
                if (standardCloseDays.indexOf(day) !== -1) {
                    result = false;

                    // Given precise date is a special opening date
                    if (specialOpeningDays.indexOf(string) !== -1) {
                        result = true;
                    }
                }

                // Given weekday is not closed, and precise date is not a special closing date
                if ((standardCloseDays.indexOf(day) === -1) && (specialClosingDays.indexOf(string) !== -1) ) {
                    result = false;
                }

                // jQuery datePicker is waiting for an array
                return [result];
            };
        },

        /**
         * Get retailer closed days (the number of the weekday)
         *
         * @param retailerData The retailer data
         *
         * @returns {Array}
         */
        getRetailerClosedDayWeek : function (retailerData)
        {
            var closedDays = [];
            if (retailerData.opening_hours
                && retailerData.opening_hours.time_ranges
                && retailerData.opening_hours.time_ranges.length > 0
            ) {
                retailerData.opening_hours.time_ranges.forEach( function (dateElement) {
                    if (dateElement.date !== undefined && dateElement.time_ranges === undefined) {
                        closedDays.push(parseInt(dateElement.date, 10));
                    }
                });
            }

            return closedDays;
        },

        /**
         * Get retailer special opening/closing dates (exact date)
         *
         * @param retailerData The retailer data
         *
         * @returns {Object}
         */
        getRetailerSpecialDates : function (retailerData)
        {
            var dates = {
                specialOpenings : [],
                specialClosings : []
            };

            if (retailerData.special_opening_hours
                && retailerData.special_opening_hours.time_ranges
                && retailerData.special_opening_hours.time_ranges.length > 0
            ) {
                retailerData.special_opening_hours.time_ranges.forEach( function (dateElement) {
                    if (dateElement.date !== undefined
                        && (dateElement.time_ranges !== undefined && dateElement.time_ranges.length > 0 && dateElement.time_ranges[0].length > 0)) {
                        dates.specialOpenings.push(dateElement.date);
                    } else {
                        dates.specialClosings.push(dateElement.date);
                    }
                });
            }

            return dates;
        },

        /**
         * Reset the pickup date to null. Can be used when switching to a new retailer.
         */
        resetPickupDate : function ()
        {
            if (this.datePicker !== null) {
                this.datePicker.val('');
            }
        },

        /**
         * Wrapper for data retrieval in customerData
         *
         * @param key
         *
         * @returns {*}
         */
        getCustomerData : function(key)
        {
            var retailerData = customerData.get("smile-retailer-data")();
            if (retailerData.hasOwnProperty(key)) {
                return retailerData[key];
            }

            return null;
        }
    });

    return $.smileRetailer.retailerPickup;
});
