<?php
namespace Fishead\DeliveryAddress\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Quote\Setup\QuoteSetup;
use Magento\Sales\Setup\SalesSetup;
use Magento\Framework\DB\Ddl\Table;

class AddDeliveryAddress implements SchemaPatchInterface, PatchRevertableInterface
{
    const DELIVERY_ADDRESS_FIELD = 'delivery_address';
    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    /**
     * @var QuoteSetup
     */
    private $quoteSetup;

    /**
     * @var SalesSetup
     */
    private $salesSetup;


    /**
     * CreateBulkOrderAttribute constructor.
     * @param SchemaSetupInterface $schemaSetup SchemaSetupInterface
     * @param QuoteSetup           $quoteSetup  QuoteSetup
     * @param SalesSetup           $salesSetup  SalesSetup
     */
    public function __construct(
        SchemaSetupInterface $schemaSetup,
        QuoteSetup $quoteSetup,
        SalesSetup $salesSetup
    ) {
        $this->schemaSetup = $schemaSetup;
        $this->quoteSetup  = $quoteSetup;
        $this->salesSetup  = $salesSetup;
    }//end __construct()


    /**
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }//end getDependencies()


    /**
     * @return array
     */
    public function getAliases()
    {
        return [];
    }//end getAliases()


    /**
     * Apply patch if not applied
     */
    public function apply()
    {
        $this->schemaSetup->startSetup();

        $this->schemaSetup->getConnection()->addColumn(
            $this->schemaSetup->getTable('quote_address'),
            self::DELIVERY_ADDRESS_FIELD,
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => false,
                'comment' => 'Delivery Address'
            ]
        );

        $this->schemaSetup->getConnection()->addColumn(
            $this->schemaSetup->getTable('sales_order_address'),
            self::DELIVERY_ADDRESS_FIELD,
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => false,
                'comment' => 'Delivery Address'
            ]
        );
        
        $this->schemaSetup->endSetup();
    }//end apply()


    /**
     *
     */
    public function revert()
    {
        $this->schemaSetup->startSetup();
        $this->schemaSetup->getConnection()->dropColumn('quote_address',self::DELIVERY_ADDRESS_FIELD);
        $this->schemaSetup->getConnection()->dropColumn('sales_order_address',self::DELIVERY_ADDRESS_FIELD);
        $this->schemaSetup->endSetup();
    }//end revert()


}//end class
