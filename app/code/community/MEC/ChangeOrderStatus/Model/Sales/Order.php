<?php

class MEC_ChangeOrderStatus_Model_Sales_Order extends Mage_Sales_Model_Order {
    
    public function save() {
        
        $oldOrder  = Mage::getModel('sales/order')->load($this->getId());
        $oldStatus = $oldOrder->getData('status');
        $oldState  = $oldOrder->getData('state');
        
        parent::save();
        
        $newStatus = $this->getData('status');
        $newState = $this->getData('state');
        
        if ($newStatus != $oldStatus || $newState != $oldState) {
            $this->sendStatusNotifications();
        }
        
    }
    
    public function isStateProtected($state) {   
        
        $protectOrderState = Mage::getStoreConfig('changeorderstatus/general/protect_order_state');
        if ($protectOrderState) {
            return parent::isStateProtected($state);
        }
        
        return false;
        
    }
    
    public function sendStatusNotifications() {
        
        $sendMailEnabled = Mage::getStoreConfig('changeorderstatus/general/send_mail');
        if (!$sendMailEnabled) {
            return false;
        }
        
         try {

            $orderStatusEmails = Mage::getModel('mec_changeorderstatus/orderstatusemail')->getCollection();

            $storeId = $this->getStoreId();
            $status = $this->getStatus();
            
            $templateId = null;

            foreach ($orderStatusEmails as $orderStatusEmail) {

                if ($orderStatusEmail->getData('store_id') == $storeId &&
                        $orderStatusEmail->getData('status') == $status) {
                    $templateId = $orderStatusEmail->getData('email');
                    break;
                }
            }
            
            if ($templateId) {
                
                $senderName = Mage::getStoreConfig('trans_email/ident_sales/name');
                $senderEmail = Mage::getStoreConfig('trans_email/ident_sales/email');		
	
                $recepientEmail = $this->getCustomerEmail();
                $recepientName = $this->getCustomerName();

                $vars = array('customerName' => $recepientEmail, 
						'customerEmail' => $recepientName,
						'order' => $this );

				$mailer = Mage::getModel('core/email_template_mailer');
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($recepientEmail, $recepientName);
                $mailer->addEmailInfo($emailInfo);
                $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId));
                $mailer->setStoreId($storeId);
                $mailer->setTemplateId($templateId);
                $mailer->setTemplateParams($vars);

                $emailQueue = Mage::getModel('core/email_queue');
                $emailQueue->setEntityId( uniqid() )
                        ->setEntityType(self::ENTITY)
                        ->setEventType(self::EMAIL_EVENT_NAME_NEW_ORDER);

                $mailer->setQueue($emailQueue)->send();
            }

        } catch (Exception $e) {
            Mage::logException($e);
        }
        
    }
   
}
