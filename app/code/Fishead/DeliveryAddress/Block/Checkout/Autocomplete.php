<?php
namespace Fishead\DeliveryAddress\Block\Checkout;

use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\View\Element\Template\Context;
use Fishead\DeliveryAddress\Helper\Data;

class Autocomplete extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Data
     */
    protected $addressAutocompleteHelperData;

    /**
     * @var ResolverInterface
     */
    protected $_localeResolver;

    /**
     * @param Context $context
     * @param Data $addressAutocompleteHelperData
     * @param ResolverInterface $localeResolver
     */
    public function __construct(
        Context $context,
        Data $addressAutocompleteHelperData,
        ResolverInterface $localeResolver
    ) {
        $this->addressAutocompleteHelperData = $addressAutocompleteHelperData;
        $this->_localeResolver = $localeResolver;
        parent::__construct($context);
    }

    /**
     * Retrieve the address autocomplete status
     *
     * @return boolean
     */
    public function showAddressAutocomplete()
    {
        if ($this->addressAutocompleteHelperData->getAddressAutocompleteStatus()) {
            return true;
        }
        return false;
    }

    /**
     * Retrieve the API Key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->addressAutocompleteHelperData->getApiKey();
    }

    /**
     * Retrieve the locate
     *
     * @return string
     */
    public function getLocate()
    {
        return $this->_localeResolver->getLocale();
    }

    /**
     * Retrieve the countries code allowed
     *
     * @return string
     */
    public function getCountriesAllowed()
    {
        return $this->addressAutocompleteHelperData->getCountriesAllowed();
    }
}
