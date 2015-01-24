<?php

class MEC_ChangeOrderStatus_Block_Adminhtml_Sales_Order_View_History extends Mage_Adminhtml_Block_Sales_Order_View_History
{
   
    public function getStatuses()
    {
        $moduleEnabled = Mage::getStoreConfig('changeorderstatus/general/module_enabled');
        if (!$moduleEnabled) {
            return parent::getStatuses();
        }
        
        return MEC_ChangeOrderStatus_Model_Orderstatusemail::getStatusesAndStates();
    }

}
