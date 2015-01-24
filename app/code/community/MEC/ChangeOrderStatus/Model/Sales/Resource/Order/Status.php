<?php

class MEC_ChangeOrderStatus_Model_Sales_Resource_Order_Status extends Mage_Sales_Model_Resource_Order_Status
{
   
    protected $_emailsTable;
    
    protected function _construct()
    {
        parent::_construct();
        $this->_emailsTable  = $this->getTable('mec_changeorderstatus/orderstatusemail');        

    }
        
    public function getStoreEmails(Mage_Core_Model_Abstract $status)
    {        
        $select = $this->_getWriteAdapter()->select()
            ->from($this->_emailsTable, array('store_id', 'email'))
            ->where('status = ?', $status->getStatus());
        return $this->_getReadAdapter()->fetchPairs($select);
    }
    
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->hasStoreEmails()) {
            $emails = $object->getStoreEmails();
            $this->_getWriteAdapter()->delete(
                $this->_emailsTable,
                array('status = ?' => $object->getStatus())
            );
            $data = array();
            foreach ($emails as $storeId => $email) {
                if (empty($email)) {
                    continue;
                }
                $data[] = array(
                    'status'    => $object->getStatus(),
                    'store_id'  => $storeId,
                    'email'     => $email
                );
            }
            if (!empty($data)) {
                $this->_getWriteAdapter()->insertMultiple($this->_emailsTable, $data);
            }
        }
        return parent::_afterSave($object);
    }
    
}
