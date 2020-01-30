<?php
namespace Fishead\DeliveryAddress\Setup\Patch\Data;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Quote\Setup\QuoteSetup;
use Magento\Sales\Setup\SalesSetup;

class AddCustomerAddressAttribute implements DataPatchInterface, PatchRevertableInterface
{

    const QUOTE_ENTITY = 'quote_address';

    const ORDER_ENTITY = 'sales_order_address';

    const DELIVERY_ADDRESS_FIELD = 'delivery_address';
    /**
     * @var ModuleDataSetupInterface
     */
    private $_moduleDataSetup;
    /**
     * @var CustomerSetupFactory
     */
    private $_customerSetupFactory;

    /**
     * @var QuoteSetup
     */
    private $quoteSetup;

    /**
     * @var SalesSetup
     */
    private $salesSetup;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $_moduleDataSetup
     * @param CustomerSetupFactory     $_customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $_moduleDataSetup,
        CustomerSetupFactory $_customerSetupFactory,
        QuoteSetup $quoteSetup,
        SalesSetup $salesSetup
    ) {
        $this->_moduleDataSetup = $_moduleDataSetup;
        $this->_customerSetupFactory = $_customerSetupFactory;
        $this->quoteSetup  = $quoteSetup;
        $this->salesSetup  = $salesSetup;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function apply()
    {
        $this->_moduleDataSetup->getConnection()->startSetup();
        $customerSetup = $this->_customerSetupFactory->create(['setup' => $this->_moduleDataSetup]);

        $customerSetup->addAttribute('customer_address', 'delivery_address', [
            'label' => 'Delivery Address',
            'input' => 'text',
            'type' => 'varchar',
            'source' => '',
            'required' => false,
            'position' => 333,
            'visible' => true,
            'system' => false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false,
            'backend' => ''
        ]);


        $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'delivery_address')
            ->addData(['used_in_forms' => [
                'customer_address_edit',
                'customer_register_address'
            ]]);
        $attribute->save();

        $this->_moduleDataSetup->getConnection()->endSetup();
    }//end apply()

    /**
     * Remove attributes in case of uninstall module
     *
     * @return void
     */
    public function revert()
    {
        
    }// end revert()

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getAliases()
    {
        return [];
    }//end getAliases()

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getDependencies()
    {
        return [

        ];
    }//end getDependencies()
}
