<?php
$schema['adama_store_locator'] = array(
    
        'content' => array (
        'items' => array (
            'remove_indent' => true,
            'hide_label' => true,
            'type' => 'enum',
            'object' => 'locations',
            'items_function' => 'fn_get_adama_store_locations',
            'fillings' => array (
                'all' => array (
                    'params' => array (
                        'page' => '1',
                        'q' => '',
                        'match' => 'any',
                        'items_per_page' => '0',
                    )
                ),
            ),
        ),
    ),
    
    'templates' => array(
        'addons/adama_store_locator/blocks/adama_store_locator.tpl' => array(),
        'addons/adama_store_locator/blocks/adama_store_locator_scroller.tpl' => array(),
    ),
    'wrappers' => 'blocks/wrappers',
    'cache' => array(
        'disable_cache_when' => array(
            'request_handlers' => array('q')
        )
    )
);

return $schema;
