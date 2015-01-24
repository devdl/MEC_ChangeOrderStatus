<?php

require_once(Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'Sales/Order/StatusController.php');

class MEC_ChangeOrderStatus_Adminhtml_Sales_Order_StatusController extends Mage_Adminhtml_Sales_Order_StatusController {

    public function saveAction() {

        $moduleEnabled = Mage::getStoreConfig('changeorderstatus/general/module_enabled');
        
        if (!$moduleEnabled) {        
            
            parent::saveAction();            
            
        } else {

            $data = $this->getRequest()->getPost();
            $isNew = $this->getRequest()->getParam('is_new');
            if ($data) {

                $statusCode = $this->getRequest()->getParam('status');

                $helper = Mage::helper('adminhtml');
                if ($isNew) {
                    $statusCode = $data['status'] = $helper->stripTags($data['status']);
                }
                $data['label'] = $helper->stripTags($data['label']);
                foreach ($data['store_labels'] as &$label) {
                    $label = $helper->stripTags($label);
                }
                foreach ($data['store_emails'] as &$email) {
                    $email = $helper->stripTags($email);
                }

                $status = Mage::getModel('sales/order_status')
                        ->load($statusCode);

                if ($isNew && $status->getStatus()) {
                    $this->_getSession()->addError(
                            Mage::helper('sales')->__('Order status with the same status code already exist.')
                    );
                    $this->_getSession()->setFormData($data);
                    $this->_redirect('*/*/new');
                    return;
                }

                $status->setData($data)
                        ->setStatus($statusCode);
                try {
                    $status->save();
                    $this->_getSession()->addSuccess(Mage::helper('sales')->__('The order status has been saved.'));
                    $this->_redirect('*/*/');
                    return;
                } catch (Mage_Core_Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                } catch (Exception $e) {
                    $this->_getSession()->addException(
                            $e, Mage::helper('sales')->__('An error occurred while saving order status. The status has not been added.')
                    );
                }
                $this->_getSession()->setFormData($data);
                if ($isNew) {
                    $this->_redirect('*/*/new');
                } else {
                    $this->_redirect('*/*/edit', array('status' => $this->getRequest()->getParam('status')));
                }
                return;
            }
            $this->_redirect('*/*/');
        }
    }

}
