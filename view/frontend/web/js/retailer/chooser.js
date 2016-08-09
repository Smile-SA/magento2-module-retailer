define(['uiComponent', 'jquery', 'mage/template', 'mage/calendar', 'mage/cookies'], function (Component, $) {

    "use strict";

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

    var Store = function(storeId, storeData) {
        this.id       = storeId;
        this.name     = storeData.name;
        this.calendar = storeData.calendar;
    }

    return Component.extend({
        defaults: {
            displayDateFormat  : 'dd/mm/yy',
            internalDateFormat : $.datepicker.ISO_8601,
            selectedStoreId    : null,
            pickupDate         : null
        },

        initialize: function() {
            this._super();
            this.observe(['selectedStoreId', 'pickupDate']);
            this.initStores();
            this.selectedStoreId(RetailerSession.getSelectedStoreId());
            this.pickupDate(RetailerSession.getPickupDate());
        },

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

        getCurrentStore: function() {
            return this.storeById[this.selectedStoreId()];
        },

        onStoreChange: function() {
            this.pickupDate(null);
            this.initDatePicker();
        },

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

        showPicker: function () {
            $(this.pickerNode).datepicker("show");
        },

        onDatePick: function(date, datePicker) {
            this.pickupDate($.datepicker.parseDate(this.internalDateFormat, date));
        },

        getDatePickerLabel: function() {
            var currentDate = this.pickupDate();
            var pickerLabel = 'Choose your delivery date ...';
            if (currentDate) {
                pickerLabel = 'Delivery date : ' + $.datepicker.formatDate(this.displayDateFormat, currentDate);
            }

            return pickerLabel;
        },

        filterAvailableDates: function(date) {
            var calendar      = this.getCurrentStore() ? this.getCurrentStore().calendar : [];
            var formattedDate = $.datepicker.formatDate(this.internalDateFormat, date);
            var isValidDate   = $.inArray(formattedDate, calendar) != -1;
            return [isValidDate, "", ""];
        },

        canValidate : function() {
            return this.pickupDate() !== null && this.selectedStoreId() !== null;
        },

        validate: function() {
            var params = {
                'pickup_date' : $.datepicker.formatDate(this.internalDateFormat, this.pickupDate()),
                'retailer_id' : this.selectedStoreId()
            }
            
            window.location = [this.updateUrl, $.param(params)].join('?');
        }
    });
});
