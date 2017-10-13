<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if (fn_allowed_for('ULTIMATE')) {
    fn_register_hooks(
        'ult_check_store_permission'
    );
}
