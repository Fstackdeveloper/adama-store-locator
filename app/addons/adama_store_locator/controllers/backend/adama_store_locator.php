<?php
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suffix = '';
    fn_trusted_vars('adama_store_locations', 'adama_store_location_data');

    if ($mode == 'update') {

        $adama_store_location_id = fn_update_adama_store_location($_REQUEST['adama_store_location_data'], $_REQUEST['adama_store_location_id'], DESCR_SL);

        if (empty($adama_store_location_id)) {
            $suffix = ".manage";
        } else {
            fn_attach_image_pairs('location_main', 'location', $adama_store_location_id, DESCR_SL);
            $suffix = ".update?adama_store_location_id=$adama_store_location_id";
        }
    }

    if ($mode == 'delete') {
        if (!empty($_REQUEST['adama_store_location_id'])) {
            fn_delete_adama_store_location($_REQUEST['adama_store_location_id']);
        }
        $suffix = '.manage';
    }

    return array (CONTROLLER_STATUS_OK, 'adama_store_locator' . $suffix);
}

if ($mode == 'manage') {

    list($adama_store_locations, $search) = fn_get_adama_store_locations($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'), DESCR_SL);

    Tygh::$app['view']->assign('sl_settings', fn_get_adama_store_locator_settings());
    Tygh::$app['view']->assign('adama_store_locations', $adama_store_locations);
    Tygh::$app['view']->assign('search', $search);

} elseif ($mode == 'add') {

    // [Page sections]
    Registry::set('navigation.tabs', array (
        'detailed' => array (
            'title' => __('general'),
            'js' => true
        ),
        'addons' => array (
            'title' => __('addons'),
            'js' => true
        )
    ));
    // [/Page sections]

    Tygh::$app['view']->assign('sl_settings', fn_get_adama_store_locator_settings());

} elseif ($mode == 'update') {

    $adama_store_location = fn_get_adama_store_location($_REQUEST['adama_store_location_id'], DESCR_SL);

    if (empty($adama_store_location)) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    Tygh::$app['view']->assign('adama_store_location', $adama_store_location);
    Tygh::$app['view']->assign('sl_settings', fn_get_adama_store_locator_settings());

    // [Page sections]
    $tabs = array (
        'detailed' => array (
            'title' => __('general'),
            'js' => true
        ),
        'addons' => array (
            'title' => __('addons'),
            'js' => true
        )
    );

    Registry::set('navigation.tabs', $tabs);
    // [/Page sections]

}
