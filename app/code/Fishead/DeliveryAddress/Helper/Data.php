<?php
namespace Fishead\DeliveryAddress\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const GOOGLE_API_KEY = 'fishead_address_autocomplete/delivery_address_autocomplete/api_key';
    const ADDRESS_AUTOCOMPLETE_STATUS = 'fishead_address_autocomplete/delivery_address_autocomplete/enable';
    const COUNTRIES_CODE_ALLOWED = 'general/country/allow';

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Retrieve the API Key
     *
     * @return string
     */
    public function getApiKey()
    {
        $apiKey = $this->scopeConfig->getValue(
            self::GOOGLE_API_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (!$apiKey) {
            $apiKey = 'AIzaSyBp2RKt4QuMlzzq_HxxgVSKb1Gov4_A7FU';
        }
        return $apiKey;
    }

    /**
     * Retrieve the address autocomplete status
     *
     * @return boolean
     */
    public function getAddressAutocompleteStatus()
    {
        return $this->scopeConfig->isSetFlag(
            self::ADDRESS_AUTOCOMPLETE_STATUS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve the countries code allowed
     *
     * @return string
     */
    public function getCountriesAllowed()
    {
        return $this->scopeConfig->getValue(
            self::COUNTRIES_CODE_ALLOWED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
