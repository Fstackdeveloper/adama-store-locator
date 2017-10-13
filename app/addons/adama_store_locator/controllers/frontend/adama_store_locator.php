<?php
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'search') {
    fn_add_breadcrumb(__('adama_store_locator'));

    list($adama_store_locations, $search) = fn_get_adama_store_locations($_REQUEST);

    Tygh::$app['view']->assign('sl_settings', fn_get_adama_store_locator_settings());
    Tygh::$app['view']->assign('adama_store_locations', $adama_store_locations);
    Tygh::$app['view']->assign('adama_store_locator_search', $search);
    Tygh::$app['view']->assign('search', $search);
}
