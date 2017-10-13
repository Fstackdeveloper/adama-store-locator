<?php
$schema['adama_store_locations'] = array(
    'controller' => 'adama_store_locator',
    'mode' => 'update',
    'type' => 'tpl_tabs',
    'params' => array(
        'object_id' => '@adama_store_location_id',
        'object' => 'adama_store_locations'
    ),
    'table' => array(
        'name' => 'adama_store_locations',
        'key_field' => 'adama_store_location_id',
    ),
    'request_object' => 'adama_store_location_data',
    'have_owner' => true,
);

return $schema;
