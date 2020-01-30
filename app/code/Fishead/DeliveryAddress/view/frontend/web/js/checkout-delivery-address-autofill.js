define([
    'jquery',
    'googleMapPlaceLibrary'
  ], function ($) {
    "use strict";
    $.widget('fishead.checkoutDeliveryAddressAutofill', {
      options: {
        loopShipping: 0,
        loopBilling: 0,
        componentForm: {
          street_number: 'street_1',
          route: 'route',
          locality: 'city',
          administrative_area_level_2: 'city',
          administrative_area_level_1: 'region',
          country: 'country',
          postal_code: 'zip'
        },
        shippingAutocomplete: null,
        billingAutocomplete: null,
        billingStreetFound: false,
        billingFunction: null
      },
  
      /**
       *
       * @private
       */
      _create: function () {
        var self = this;
  
        // Fill in shipping address
        this.options.shippingFunctions = setInterval(function() {
          var street = $('#shipping-new-address-form').find('input[name="delivery_address"]')[0];
          if (street) {
            self.options.shippingAutocomplete = new google.maps.places.Autocomplete(
              street,
              {types: ['geocode']}
            );
            self.options.shippingAutocomplete.inputId = street.id;
            self.options.shippingAutocomplete.setComponentRestrictions({'country': self._getCountriesCodeArray()});
            google.maps.event.addListener(self.options.shippingAutocomplete, 'place_changed', function () {
              // Get the place details from the autocomplete object.
              var place = self.options.shippingAutocomplete.getPlace();
              // Get each component of the address from the place details
              // and fill the corresponding field on the form.
              for (var i = place.address_components.length-1; i >= 0; i--) {
                var addressType = place.address_components[i].types[0];
                var long_name = place.address_components[i].long_name;
                var short_name = place.address_components[i].short_name;
                if (self.options.componentForm[addressType] && long_name) {
                  if (self.options.componentForm[addressType] == 'country') {
                    $('#shipping-new-address-form select[name="country_id"]').val(short_name).trigger('change');
                  } else if (self.options.componentForm[addressType] == 'region') {
                    $('#shipping-new-address-form input[name="region"]').val(long_name).trigger('keyup');
                    if ($('#shipping-new-address-form select[name="region_id"] option:contains('+long_name+')')) {
                      $('#shipping-new-address-form select[name="region_id"] option:contains('+long_name+')').prop('selected', true).trigger('change');
                    }
                  } else if (self.options.componentForm[addressType] == 'route') {
                    $('#shipping-new-address-form input[name="street[0]"]').val(long_name).trigger('keyup').trigger('keyup');
                  } else if (self.options.componentForm[addressType] == 'street_1') {
                    $('#shipping-new-address-form input[name="street[0]"]').val(long_name + ' '+ $('#shipping-new-address-form input[name="street[0]"]').val()).trigger('keyup');
                  } else if (self.options.componentForm[addressType] == 'zip') {
                    $('#shipping-new-address-form input[name="postcode"]').val(long_name).trigger('keyup');
                  } else {
                    $('#shipping-new-address-form input[name="'+self.options.componentForm[addressType]+'"]').val(long_name).trigger('keyup');
                  }
                }
              }
            });
            clearInterval(self.options.shippingFunctions);
          }
          self.options.loopShipping = self.options.loopShipping + 1;
          if (self.options.loopShipping >= 11) {
            clearInterval(self.options.shippingFunctions);
          }
        }, 2000);
  
        $(document).on('change', 'select[name="billing_address_id"]', function() {
          if ($(this).find('option:last').prop('selected')) {
            self._fillInBillingAddress();
          }
        });
  
        $(document).on('click', 'input[name="billing-address-same-as-shipping"], .action-edit-address', function() {
          if ($(this).find('option:last').prop('selected')) {
            self._fillInBillingAddress();
          }
        });
      },
  
      /**
       * Fill in billing address
       *
       * @private
       */
      _fillInBillingAddress() {
        var self = this;
        if (!this.options.billingStreetFound) {
          this.options.billingFunctions = setInterval(function() {
            var street = $('#co-payment-form').find('input[name="delivery_address"]')[0];
            if (street) {
              self.options.billingAutocomplete = new google.maps.places.Autocomplete(
                street,
                {types: ['geocode']}
              );
              self.options.billingAutocomplete.inputId = street.id;
              self.options.billingAutocomplete.setComponentRestrictions({'country': self._getCountriesCodeArray()});
              google.maps.event.addListener(self.options.billingAutocomplete, 'place_changed', function () {
                // Get the place details from the autocomplete object.
                var place = self.options.billingAutocomplete.getPlace();
                // Get each component of the address from the place details
                // and fill the corresponding field on the form.
                for (var i = place.address_components.length-1; i >= 0; i--) {
                  var addressType = place.address_components[i].types[0];
                  var long_name = place.address_components[i].long_name;
                  var short_name = place.address_components[i].short_name;
                  if (self.options.componentForm[addressType] && long_name) {
                    if (self.options.componentForm[addressType] == 'country') {
                      $('#billing-new-address-form select[name="country_id"]').val(short_name).trigger('change');
                    } else if (self.options.componentForm[addressType] == 'region') {
                      $('#billing-new-address-form input[name="region"]').val(long_name).trigger('keyup');
                      if ($('#billing-new-address-form select[name="region_id"] option:contains('+long_name+')')) {
                        $('#billing-new-address-form select[name="region_id"] option:contains('+long_name+')').prop('selected', true).trigger('change');
                      }
                    } else if (self.options.componentForm[addressType] == 'route') {
                      $('#billing-new-address-form input[name="street[0]"]').val(long_name).trigger('keyup').trigger('keyup');
                    } else if (self.options.componentForm[addressType] == 'street_1') {
                      $('#billing-new-address-form input[name="street[0]"]').val(long_name + ' '+ $('#billing-new-address-form input[name="street[0]"]').val()).trigger('keyup');
                    } else if (self.options.componentForm[addressType] == 'zip') {
                      $('#billing-new-address-form input[name="postcode"]').val(long_name).trigger('keyup');
                    } else {
                      $('#billing-new-address-form input[name="'+self.options.componentForm[addressType]+'"]').val(long_name).trigger('keyup');
                    }
                  }
                }
              });
              clearInterval(self.options.billingFunctions);
              self.options.billingStreetFound = true;
            }
            self.options.loopBilling = self.options.loopBilling + 1;
            if (self.options.loopBilling >= 11) {
              clearInterval(self.options.billingFunctions);
            }
          }, 2000);
        }
      },
  
      /**
       * Convert countries code to array
       *
       * @private
       */
      _getCountriesCodeArray() {
        var countries = this.options.countries;
        return countries.split(',');
      }
    });
    return $.fishead.checkoutDeliveryAddressAutofill;
  });
  