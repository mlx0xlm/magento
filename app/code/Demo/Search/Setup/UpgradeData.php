<?php
namespace Demo\Search\Setup;
use Magento\Customer\Model\ResourceModel\Attribute;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
class UpgradeData implements UpgradeDataInterface
{
    protected $EavSetup,$customerSetupFactory;
    protected $config;
    protected $storeManager;
    protected $attributeResource,$AttributeSetFactory;
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        StoreManagerInterface $storeManager,
        Config $eavConfig,
        Attribute $attributeResource,
        CustomerSetupFactory $CustomerSetupFactory,
        AttributeSetFactory $AttributeSetFactory
    )
    {
        $this->customerSetupFactory = $CustomerSetupFactory;
        $this->attributeResource = $attributeResource;
        $this->config = $eavConfig;
        $this->EavSetup = $eavSetupFactory;
        $this->storeManager = $storeManager;
        $this->AttributeSetFactory=$AttributeSetFactory;
    }
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        if (version_compare($context->getVersion(), '1.0.8') < 0) {
            $setup->startSetup();
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();
            $attributeSet = $this->AttributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'twilio_image', [
                'type' => 'text',
                'label' => 'Image',
                'input' => 'file',
                "source" => '',
                'required' => false,
                'default' => '0',
                'visible' => true,
                'user_defined' => true,
                'sort_order' => 210,
                'position' => 210,
                'system' => false,
            ]);
            $image = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'twilio_image')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit'],
                ]);
            $image->save();
            $setup->endSetup();
        }
        if ( version_compare($context->getVersion(), '1.0.4', '<' ))
        {
            $eavSetup = $this->EavSetup->create(['setup' => $setup]);
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, "type_product,");
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'type_product',
                [
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Booking Type',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '0',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply'=>'',
                ]
            );
        }
        if ( version_compare($context->getVersion(), '1.0.5', '<' ))
        {
            $eavSetup = $this->EavSetup->create(['setup' => $setup]);
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, "start_time,");
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'start_time',
                [
                    'group' => 'General',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Start Date',
                    'input' => 'date',
                    'class' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '0',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply'=>'',
                ]
            );
        }
    }
}