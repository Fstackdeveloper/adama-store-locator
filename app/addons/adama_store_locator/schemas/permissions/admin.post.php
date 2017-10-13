<?php
$schema['adama_store_locator'] = array (
    'permissions' => array('GET' => 'view_adama_store_locator', 'POST' => 'manage_adama_store_locator'),
    'modes' => array(
        'delete' => array(
            'permissions' => 'manage_adama_store_locator'
        )
    ),
);
$schema['tools']['modes']['update_status']['param_permissions']['table']['adama_store_locations'] = 'manage_adama_store_locator';

return $schema;
