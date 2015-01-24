<?php

class MEC_ChangeOrderStatus_Model_Sales_Order_Status extends Mage_Sales_Model_Order_Status
{

    public function getStoreEmails()
    {
        if ($this->hasData('store_emails')) {
            return $this->_getData('store_emails');
        }
        $labels = $this->_getResource()->getStoreEmails($this);
        $this->setData('store_emails', $labels);
        return $labels;
    }

    public function getStoreEmail($store=null)
    {
        $store = Mage::app()->getStore($store);
        $email = false;
        if (!$store->isAdmin()) {
            $emails = $this->getStoreEmails();
            if (isset($emails[$store->getId()])) {
                return $emails[$store->getId()];
            }
        }
        return Mage::helper('sales')->__($this->getEmail());
    }

}
