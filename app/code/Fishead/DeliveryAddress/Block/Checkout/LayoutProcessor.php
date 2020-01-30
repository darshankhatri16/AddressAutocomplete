<?php

namespace Fishead\DeliveryAddress\Block\Checkout;

class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{

    public function process($jsLayout) {
        $customAttributeCode = 'delivery_address';
        
        //For Shipping Address
        if(isset($jsLayout['components']['checkout']['children']['steps']['children']
                ['shipping-step']['children']['shippingAddress']['children']
                ['shipping-address-fieldset'])
        ){
            $customField = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                // customScope is used to group elements within a single form (e.g. they can be validated separately)
                'customScope' => 'shippingAddress',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'tooltip' => [
                    'description' => 'Delivery Address. Autocomplete provided by Google.',
                ],
            ],
            'dataScope' => 'shippingAddress' . '.' . $customAttributeCode,
            'label' => 'Delivery Address',
            'provider' => 'checkoutProvider',
            'sortOrder' => 65,
            'validation' => [
            'required-entry' => true
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
            'value' => '' // value field is used to set a default value of the attribute
        ];

            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$customAttributeCode] = $customField;
        }

        //For Billing Address
        if(isset($jsLayout['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list'])) {
                $paymentForms = $jsLayout['components']['checkout']['children']['steps']['children']
                                ['billing-step']['children']['payment']['children']
                                ['payments-list']['children'];

                foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {
                    $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);
                    if (!isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])) {
                        continue;
                    }
                    $jsLayout['components']['checkout']['children']['steps']['children']
                    ['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'][$customAttributeCode] = $customField;
                }
            }
        return $jsLayout;
    }
    
}