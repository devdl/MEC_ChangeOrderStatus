<?php

class MEC_ChangeOrderStatus_Model_Orderstatusemail extends Mage_Core_Model_Abstract {
      
    public function _construct(){
        parent::_construct();
        $this->_init('mec_changeorderstatus/orderstatusemail');
    }
    
     public static function getStatusesAndStates() {
        
        $result = array();
        
        $excludeStates = array(Mage_Sales_Model_Order::STATE_CANCELED, Mage_Sales_Model_Order::STATE_HOLDED);        
        $protectOrderState = Mage::getStoreConfig('changeorderstatus/general/protect_order_state');
        if ($protectOrderState) {
            $excludeStates = array_merge($excludeStates, array(Mage_Sales_Model_Order::STATE_CLOSED, Mage_Sales_Model_Order::STATE_COMPLETE));
        }
        
        $statuses = Mage::getResourceModel('sales/order_status_collection')->joinStates();
        foreach ($statuses as $statusKey => $status) {
            
            $state = $status->getData('state');
                                     
            if ($state && !in_array($state, $excludeStates)) {
                                
                $key = $state . '-' . $status->getStatus();                                
                $value = $status->getLabel();
                $result[$key] = $value;
            }
            
        }        
        
        asort($result);
        return $result;
        
    }
    
}
