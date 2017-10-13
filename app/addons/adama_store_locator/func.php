<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Settings;
use Tygh\Registry;
use Tygh\Themes\Themes;
use Tygh\Tools\SecurityHelper;

/**
 * Gets list of store locations
 *
 * @param array $params Request parameters
 * @param int $items_per_page Amount of items per page
 * @param string $lang_code Two-letter language code
 *
 * @return array List of store locations
 */
function fn_get_adama_store_locations($params, $items_per_page = 0, $lang_code = CART_LANGUAGE)
{
    $default_params = array (
        'page' => 1,
        'q' => '',
        'match' => 'any',
        'items_per_page' => $items_per_page
    );

    $params = array_merge($default_params, $params);

    $fields = array (
        '?:adama_store_locations.*',
        '?:adama_store_location_descriptions.*',
        '?:country_descriptions.country as country_title'
    );

    $join = db_quote(" LEFT JOIN ?:adama_store_location_descriptions ON ?:adama_store_locations.adama_store_location_id = ?:adama_store_location_descriptions.adama_store_location_id AND ?:adama_store_location_descriptions.lang_code = ?s", $lang_code);

    $join .= db_quote(" LEFT JOIN ?:country_descriptions ON ?:adama_store_locations.country = ?:country_descriptions.code AND ?:country_descriptions.lang_code = ?s", $lang_code);

    $condition = 1;

    if (AREA == 'C') {
        $condition .= " AND status = 'A'";
    }

    // Search string condition for SQL query
    if (!empty($params['q'])) {

        if ($params['match'] == 'any') {
            $pieces = explode(' ', $params['q']);
            $search_type = ' OR ';
        } elseif ($params['match'] == 'all') {
            $pieces = explode(' ', $params['q']);
            $search_type = ' AND ';
        } else {
            $pieces = array($params['q']);
            $search_type = '';
        }

        $_condition = array();
        foreach ($pieces as $piece) {
            $tmp = db_quote("?:adama_store_location_descriptions.name LIKE ?l", "%$piece%"); // check search words

            $tmp .= db_quote(" OR ?:adama_store_location_descriptions.description LIKE ?l", "%$piece%");

            $tmp .= db_quote(" OR ?:adama_store_location_descriptions.city LIKE ?l", "%$piece%");

            $tmp .= db_quote(" OR ?:country_descriptions.country LIKE ?l", "%$piece%");

            $_condition[] = '(' . $tmp . ')';
        }

        $_cond = implode($search_type, $_condition);

        if (!empty($_condition)) {
            $condition .= ' AND (' . $_cond . ') ';
        }

        unset($_condition);
    }

    $condition .= (AREA == 'C' && defined('CART_LOCALIZATION')) ? fn_get_localizations_condition('?:adama_store_locations.localization') : '';

    $sorting = "?:adama_store_locations.position, ?:adama_store_location_descriptions.name";

    $limit = '';
    if (!empty($params['items_per_page'])) {
        $params['total_items'] = db_get_field("SELECT COUNT(?:adama_store_locations.adama_store_location_id) FROM ?:adama_store_locations ?p WHERE ?p", $join, $condition);
        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }

    $data = db_get_hash_array('SELECT ?p FROM ?:adama_store_locations ?p WHERE ?p GROUP BY ?:adama_store_locations.adama_store_location_id ORDER BY ?p ?p', 'adama_store_location_id', implode(', ', $fields), $join, $condition, $sorting, $limit);
    

    foreach ($data AS $key=>$value)
    {
        $data[$key]['main_pair'] = fn_get_image_pairs($value['adama_store_location_id'], 'location', 'M', true, true, $lang_code);
    }

    return array($data, $params);

}

function fn_get_adama_store_location($adama_store_location_id, $lang_code = CART_LANGUAGE)
{
    $fields = array (
        '?:adama_store_locations.*',
        '?:adama_store_location_descriptions.*',
        '?:country_descriptions.country as country_title'
    );

    $join = db_quote(" LEFT JOIN ?:adama_store_location_descriptions ON ?:adama_store_locations.adama_store_location_id = ?:adama_store_location_descriptions.adama_store_location_id AND ?:adama_store_location_descriptions.lang_code = ?s", $lang_code);
    $join .= db_quote(" LEFT JOIN ?:country_descriptions ON ?:adama_store_locations.country = ?:country_descriptions.code AND ?:country_descriptions.lang_code = ?s", $lang_code);

    $condition = db_quote(" ?:adama_store_locations.adama_store_location_id = ?i ", $adama_store_location_id);
    $condition .= (AREA == 'C' && defined('CART_LOCALIZATION')) ? fn_get_localizations_condition('?:adama_store_locations.localization') : '';

    $adama_store_location = db_get_row('SELECT ?p FROM ?:adama_store_locations ?p WHERE ?p', implode(', ', $fields), $join, $condition);
    
    $adama_store_location['main_pair'] = fn_get_image_pairs($adama_store_location_id, 'location', 'M', true, true, $lang_code);

    return $adama_store_location;
}

function fn_get_adama_store_location_name($adama_store_location_id, $lang_code = CART_LANGUAGE)
{
    if (!empty($adama_store_location_id)) {
        return db_get_field('SELECT `name` FROM ?:adama_store_location_descriptions WHERE adama_store_location_id = ?i AND lang_code = ?s', $adama_store_location_id, $lang_code);
    }

    return false;
}

function fn_update_adama_store_location($adama_store_location_data, $adama_store_location_id, $lang_code = DESCR_SL)
{
    SecurityHelper::sanitizeObjectData('adama_store_location', $adama_store_location_data);

    $adama_store_location_data['localization'] = empty($adama_store_location_data['localization']) ? '' : fn_implode_localizations($adama_store_location_data['localization']);

    if (empty($adama_store_location_id)) {
        if (empty($adama_store_location_data['position'])) {
            $adama_store_location_data['position'] = db_get_field('SELECT MAX(position) FROM ?:adama_store_locations');
            $adama_store_location_data['position'] += 10;
        }

        $adama_store_location_id = db_query('INSERT INTO ?:adama_store_locations ?e', $adama_store_location_data);

        $adama_store_location_data['adama_store_location_id'] = $adama_store_location_id;

        foreach (fn_get_translation_languages() as $adama_store_location_data['lang_code'] => $v) {
            db_query("INSERT INTO ?:adama_store_location_descriptions ?e", $adama_store_location_data);
        }
    } else {
        db_query('UPDATE ?:adama_store_locations SET ?u WHERE adama_store_location_id = ?i', $adama_store_location_data, $adama_store_location_id);
        db_query('UPDATE ?:adama_store_location_descriptions SET ?u WHERE adama_store_location_id = ?i AND lang_code = ?s', $adama_store_location_data, $adama_store_location_id, $lang_code);
    }

    return $adama_store_location_id;
}

function fn_delete_adama_store_location($adama_store_location_id)
{
    $deleted = true;

    $affected_rows = db_query('DELETE FROM ?:adama_store_locations WHERE adama_store_location_id = ?i', $adama_store_location_id);
    db_query('DELETE FROM ?:adama_store_location_descriptions WHERE adama_store_location_id = ?i', $adama_store_location_id);

    if (empty($affected_rows)) {
        $deleted = false;
    }

    return $deleted;
}

function fn_adama_store_locator_google_langs($lang_code)
{
    $supported_langs = array ('en', 'eu', 'ca', 'da', 'nl', 'fi', 'fr', 'gl', 'de', 'el', 'it', 'ja', 'no', 'nn', 'ru' , 'es', 'sv', 'th');

    if (in_array($lang_code, $supported_langs)) {
        return $lang_code;
    }

    return '';
}

function fn_adama_store_locator_yandex_langs($lang_code)
{
    $supported_langs = array ('en' => 'en-US', 'tr' => 'tr-TR', 'ru' => 'ru-RU');
    $default_lang_code = 'en';

    if (isset($supported_langs[$lang_code])) {
        return $supported_langs[$lang_code];
    }

    return $supported_langs[$default_lang_code];
}

function fn_adama_store_locator_get_info()
{
    $text = '<a href="http://code.google.com/apis/maps/signup.html">' . __('singup_google_url') . '</a>';

    return $text;
}

function fn_get_adama_store_locator_settings($company_id = null)
{
    static $cache;

    if (empty($cache['settings_' . $company_id])) {
        $settings = Settings::instance()->getValue('adama_store_locator_', '', $company_id);
        $settings = unserialize($settings);

        if (empty($settings)) {
            $settings = array();
        }

        $cache['settings_' . $company_id] = $settings;
    }

    return $cache['settings_' . $company_id];
}

function fn_get_adama_store_locator_map_templates($area)
{
    $templates = array();

    if (empty($area) || !in_array($area, array('A', 'C'))) {
        return $templates;
    }

    $theme = Themes::areaFactory($area);
    $search_path = 'addons/adama_store_locator/views/adama_store_locator/components/maps/';

    $_templates = $theme->getDirContents(array(
        'dir' => 'templates/' . $search_path,
        'get_dirs' => false,
        'get_files' => true,
        'extension' => array('.tpl')
    ), Themes::STR_MERGE, Themes::PATH_ABSOLUTE, Themes::USE_BASE);

    if (!empty($_templates)) {
        foreach ($_templates as $template => $file_info) {
            $template_provider = str_replace('.tpl', '', strtolower($template)); // Get provider name
            $templates[$template_provider] = $search_path . $template;
        }
    }

    return $templates;
}

if (fn_allowed_for('ULTIMATE')) {
    function fn_adama_store_locator_ult_check_store_permission($params, &$object_type, &$object_name, &$table, &$key, &$key_id)
    {
        if (Registry::get('runtime.controller') == 'adama_store_locator' && !empty($params['adama_store_location_id'])) {
            $key = 'adama_store_location_id';
            $key_id = $params[$key];
            $table = 'adama_store_locations';
            $object_name = fn_get_adama_store_location_name($key_id, DESCR_SL);
            $object_type = __('adama_store_locator');
        }
    }
}
