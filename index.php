<?php
/**
 * Plugin Name: Quản lý PC
 * Plugin URI:  https://github.com/vdbaozn/qly_pc
 * Description: Plugin có tính năng tự động cập nhật từ GitHub.
 * Version:     1.0.4
 * Author:      Võ Duy Bảo
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter('pre_set_site_transient_update_plugins', 'check_my_pc_update_via_api');
function check_my_pc_update_via_api($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    // Link GitHub API chính thức (Không lo bị cache)
    $api_url = 'https://api.github.com/repos/vdbaozn/qly_pc/releases/latest';
    
    // Đính kèm User-Agent bắt buộc khi gọi GitHub API
    $response = wp_remote_get($api_url, array(
        'timeout'    => 10,
        'headers'    => array(
            'User-Agent' => 'WordPress-Plugin-Updater'
        )
    ));
    
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return $transient;
    }

    $release_info = json_decode(wp_remote_retrieve_body($response));
    
    if (!function_exists('get_plugin_data')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    
    $plugin_file = plugin_basename(__FILE__);
    $plugin_data = get_plugin_data(__FILE__);
    $current_version = $plugin_data['Version'];

    // Lấy tag_name (Ví dụ: "v1.0.1" -> loại bỏ chữ "v" thành "1.0.1")
    $remote_version = ltrim($release_info->tag_name, '1.0.1');

    if (version_compare($current_version, $remote_version, '<')) {
        $obj = new stdClass();
        $obj->slug        = dirname($plugin_file);
        $obj->plugin      = $plugin_file;
        $obj->new_version = $remote_version;
        // Tự động lấy link zip của Release
        $obj->package     = $release_info->zipball_url; 
        $obj->url         = $release_info->html_url;
        
        $transient->response[$plugin_file] = $obj;
    }

    return $transient;
}

// Giúp WordPress theo dấu redirect khi tải zip từ Release
add_filter('http_request_args', 'fix_github_api_download', 10, 2);
function fix_github_api_download($args, $url) {
    if (strpos($url, 'github.com') !== false || strpos($url, 'api.github.com') !== false) {
        $args['sslverify'] = false;
        $args['headers']['User-Agent'] = 'WordPress-Plugin-Updater';
    }
    return $args;
}
//end update plugin