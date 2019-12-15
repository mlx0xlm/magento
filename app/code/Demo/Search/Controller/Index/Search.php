<?php
namespace Demo\Search\Controller\Index;
use Magento\Framework\App\Action\Context;
class Search extends \Magento\Framework\App\Action\Action
{
    protected $collection;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collection,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->collection = $collection;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $param = $this->getRequest()->getPostValue()['data'];
        $collection = $this->collection->create();
        $collection->addFieldToSelect('*');
        if ($param) {
            $collection->addAttributeToFilter('status', array('eq' => \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED))
                ->addAttributeToFilter(
                    [
                        ['attribute' => 'sku', 'like' => '%' . $param . '%'],
                        ['attribute' => 'sku', 'like' => $param . '%'],
                        ['attribute' => 'sku', 'like' => '%' . $param],
                        ['attribute' => 'name', 'like' => '%' . $param . '%'],
                        ['attribute' => 'name', 'like' => $param . '%'],
                        ['attribute' => 'name', 'like' => '%' . $param]
                    ])
                ->setPageSize(10);
        }
        $res = [];
        foreach ($collection as $item) {
            $res[] =[
                'productUrl' => $item->getProductUrl(),
                'name'=>$item->getName(),
                'image' => $item->getImage(),
                'sku' => $item->getSku(),
                'is_salable'=> $item->getIsSalable(),
                'price'=> $item->getPrice()
            ];
        }
        $result = $this->resultJsonFactory->create();
        $result->setData($res);
        return $result;
    }
}