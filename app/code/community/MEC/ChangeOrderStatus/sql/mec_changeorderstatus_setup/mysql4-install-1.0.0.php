<?php

$installer = $this;

$installer->startSetup();

$statusTable      = 'sales_order_status';
$statusEmailTable = 'sales_order_status_email';
$coreStoreTable   = 'core_store';

$table = $installer->getConnection()
    ->newTable($installer->getTable('mec_changeorderstatus/orderstatusemail'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Entity ID')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Status')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store Id')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
        'nullable'  => false,
        ), 'Email')
    ->addIndex($installer->getIdxName('mec_changeorderstatus/orderstatusemail', array('store_id')),
        array('store_id'))
    ->addForeignKey($installer->getFkName('mec_changeorderstatus/orderstatusemail', 'status', 'sales/order_status', 'status'),
        'status', $installer->getTable('sales/order_status'), 'status',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('mec_changeorderstatus/orderstatusemail', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
     ->addForeignKey($installer->getFkName('mec_changeorderstatus/orderstatusemail', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Sales Order Status Emails Table');
$installer->getConnection()->createTable($table);
    
$installer->endSetup();