<?php
use Tygh\Tools\SecurityHelper;

$schema['adama_store_location'] = array(
    SecurityHelper::SCHEMA_SECTION_FIELD_RULES => array(
        'name' => SecurityHelper::ACTION_REMOVE_HTML,
        'description' => SecurityHelper::ACTION_SANITIZE_HTML,
        'city' => SecurityHelper::ACTION_REMOVE_HTML,
    )
);

return $schema;
