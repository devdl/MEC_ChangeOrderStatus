<?php
class MEC_ChangeOrderStatus_Adminhtml_ChangeorderstatusController extends Mage_Adminhtml_Controller_Action
{
    
    public function massupdatestatusAction()
    {
            
        $orderIds = $this->getRequest()->getParam('order_ids');
        $newOrderStatus = $this->getRequest()->getParam('mec_changeorderstatus_status');

        $newOrderStatus = explode('-', $newOrderStatus);
        $state = $newOrderStatus[0];
        $status = $newOrderStatus[1];
        
        $protectOrderState = Mage::getStoreConfig('changeorderstatus/general/protect_order_state');
        
        $count = 0;
        
        foreach ($orderIds as $orderId) {

            $order = Mage::getModel("sales/order")->load($orderId);

            $currStatus = $order->getStatus();
            $currState = $order->getData('state');
            
            if (!$order->isStateProtected($state)) {
            
                $order->setData('state', $state)
                    ->setStatus($status)
                    ->save();
                
                $order = Mage::getModel("sales/order")->load($orderId);                                
                if ($currStatus != $order->getStatus() ||
                        $currState != $order->getData('state')) {
                    $count++;    
                }                 
            }            
        }
                       
        $statusLabel = Mage::getSingleton('sales/order_config')->getStatusLabel($status);
        
        if ($count > 0) {
            $this->_getSession()->addSuccess(
                    $this->__("$count order(s) status has been changed to $statusLabel")
            );
        }
        
        $this->_redirect('/sales_order/');        
        
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/changeorderstatus');
    }
}
