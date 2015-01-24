<?php

class MEC_ChangeOrderStatus_Model_Resource_Orderstatusemail_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract {
   
     protected function _construct(){
        parent::_construct();
        $this->_init('mec_changeorderstatus/orderstatusemail');
                
    }
    
}
