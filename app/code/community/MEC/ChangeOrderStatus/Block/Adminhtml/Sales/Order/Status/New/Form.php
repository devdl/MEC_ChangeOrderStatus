<?php
class MEC_ChangeOrderStatus_Block_Adminhtml_Sales_Order_Status_New_Form extends Mage_Adminhtml_Block_Sales_Order_Status_New_Form {
    
    
    protected function _prepareForm()
    {
        
        $moduleEnabled = Mage::getStoreConfig('changeorderstatus/general/module_enabled');
        if (!$moduleEnabled) {
            return parent::_prepareForm();
        }
        
        parent::_prepareForm();
        
        $model  = Mage::registry('current_status');
        $emails = $model ? $model->getStoreEmails() : array();
        
        $form = $this->getForm();
        
        $fieldset   = $form->addFieldset('store_emails_fieldset', array(
            'legend'    => Mage::helper('mec_changeorderstatus')->__('Order Status Notifications'),
            'table_class'  => 'form-list stores-tree',
        ));        
        
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset');
        $fieldset->setRenderer($renderer);
        
        $collection = Mage::getResourceModel('core/email_template_collection')->load();
        $options = $collection->toOptionArray();
        $options = array_merge(Mage_Core_Model_Email_Template::getDefaultTemplatesAsOptionsArray(), $options);
                
        foreach (Mage::app()->getWebsites() as $website) {
            $fieldset->addField("w_{$website->getId()}_label_email", 'note', array(
                'label'    => $website->getName(),
                'fieldset_html_class' => 'website',
            ));
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                if (count($stores) == 0) {
                    continue;
                }
                $fieldset->addField("sg_{$group->getId()}_label_email", 'note', array(
                    'label'    => $group->getName(),
                    'fieldset_html_class' => 'store-group',
                ));
                foreach ($stores as $store) {
                    $fieldset->addField("store_email_{$store->getId()}", 'select', array(
                        'name'      => 'store_emails['.$store->getId().']',
                        'required'  => false,
                        'label'     => $store->getName(),
                        'values'     => $options,
                        'value'     => isset($emails[$store->getId()]) ? $emails[$store->getId()] : '',
                        'fieldset_html_class' => 'store',
                    ));
                }
                  
            }
        }
                 
        return $this;
    }
}
			