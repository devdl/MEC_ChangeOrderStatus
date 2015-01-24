<?php

class MEC_ChangeOrderStatus_Model_Observer {
    
    public function addMassAction(Varien_Event_Observer $observer) {

            $moduleEnabled = Mage::getStoreConfig('changeorderstatus/general/module_enabled');
            if ($moduleEnabled) {
                $block = $observer->getEvent()->getBlock();
                    if (get_class($block) == 'Mage_Adminhtml_Block_Widget_Grid_Massaction' && $block->getRequest()->getControllerName() == 'sales_order') {
                        $block->addItem('mec_changeorderstatus_changestatus', array(
                            'label' => Mage::helper('mec_changeorderstatus')->__('Change Order Status'),
                            'url' => Mage::app()->getStore()->getUrl('*/changeorderstatus/massupdatestatus'),
                            'additional' => array(
                                'mec_changeorderstatus_status' => array(
                                    'name' => 'mec_changeorderstatus_status',
                                    'type' => 'select',
                                    'class' => 'required-entry',
                                    'label' => Mage::helper('mec_changeorderstatus')->__('Status'),
                                    'values' => MEC_ChangeOrderStatus_Model_Orderstatusemail::getStatusesAndStates()
                    )
                )
            ));
            }        
        }                
        
    }
    
}
