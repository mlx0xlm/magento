<?php

namespace Demo\Search\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
class UpgradeSchema implements  UpgradeSchemaInterface
{
    protected $installer;
    /**
     * {@inheritdoc}
     */
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.9', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('catalog_product_option_type_value'),
                'image',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 10,
                    'nullable' => true,
                    'comment' => 'image option',
                    'input' => 'file',
                ]
            );
        }
        $installer->endSetup();
    }
}