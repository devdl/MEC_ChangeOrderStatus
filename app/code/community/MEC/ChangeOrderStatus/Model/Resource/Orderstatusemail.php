<?php

class MEC_ChangeOrderStatus_Model_Resource_Orderstatusemail
    extends Mage_Core_Model_Resource_Db_Abstract {
   
   public function _construct(){
        $this->_init('mec_changeorderstatus/orderstatusemail', 'entity_id');
   }
    
}
