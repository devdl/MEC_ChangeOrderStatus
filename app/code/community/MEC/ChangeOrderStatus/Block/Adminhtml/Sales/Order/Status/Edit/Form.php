<?php
class MEC_ChangeOrderStatus_Block_Adminhtml_Sales_Order_Status_Edit_Form extends MEC_ChangeOrderStatus_Block_Adminhtml_Sales_Order_Status_New_Form {
        
    public function __construct()
    {
        parent::__construct();
        $this->setId('new_order_status');
    }

    protected function _prepareForm()
    {
        parent::_prepareForm();
        $form = $this->getForm();
        $form->getElement('base_fieldset')->removeField('is_new');
        $form->getElement('base_fieldset')->removeField('status');
        $form->setAction(
            $this->getUrl('*/sales_order_status/save', array('status'=>$this->getRequest()->getParam('status')))
        );
        return $this;
    }
    
    
}
			