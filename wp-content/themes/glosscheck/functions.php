<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
});

add_filter('show_admin_bar', function ($show) {
    if (is_admin()) {
        return $show;
    }

    return current_user_can('manage_options');
});
