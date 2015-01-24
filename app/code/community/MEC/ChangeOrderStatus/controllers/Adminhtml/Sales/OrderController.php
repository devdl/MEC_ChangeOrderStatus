<?php

require_once(Mage::getModuleDir('controllers','Mage_Adminhtml').DS.'Sales/OrderController.php');
class MEC_ChangeOrderStatus_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
    public function addCommentAction() {
        
        $moduleEnabled = Mage::getStoreConfig('changeorderstatus/general/module_enabled');

        if (!$moduleEnabled) {
            
            parent::addCommentAction();
            
        } else {
            
            if ($order = $this->_initOrder()) {
            try {
                $response = false;
                $data = $this->getRequest()->getPost('history');
                $notify = isset($data['is_customer_notified']) ? $data['is_customer_notified'] : false;
                $visible = isset($data['is_visible_on_front']) ? $data['is_visible_on_front'] : false;

                $statusCodeArray = explode('-', $data['status']);
                $state = $statusCodeArray[0];
                $status = $statusCodeArray[1];
                
                $order->setData('state', $state);
                
                $order->addStatusHistoryComment($data['comment'], $status)
                    ->setIsVisibleOnFront($visible)
                    ->setIsCustomerNotified($notify);
                
                $comment = trim(strip_tags($data['comment']));

                $order->save();
                $order->sendOrderUpdateEmail($notify, $comment);

                $this->loadLayout('empty');
                $this->renderLayout();
            }
            catch (Mage_Core_Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $e->getMessage(),
                );
            }
            catch (Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $this->__('Cannot add order history.')
                );
            }

            if (is_array($response)) {
                $response = Mage::helper('core')->jsonEncode($response);
                $this->getResponse()->setBody($response);
            }
                        
        }
            
        }
        
    }
    
}
