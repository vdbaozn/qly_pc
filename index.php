<?php
/**
 * Plugin Name: Quản lý PC
 * Plugin URI:  https://github.com/vdbaozn/qly_pc
 * Description: Plugin có tính năng tự động cập nhật từ GitHub.
 * Version:     1.0.4
 * Author:      Võ Duy Bảo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter('pre_set_site_transient_update_plugins', 'check_my_pc_update');
function check_my_pc_update($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    // Link raw tới file info.json trên GitHub
    $json_url = 'https://raw.githubusercontent.com/vdbaozn/qly_pc/main/info.json';

    // Gọi lấy dữ liệu từ GitHub
    $response = wp_remote_get($json_url, array('timeout' => 10));
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return $transient;
    }

    $remote_info = json_decode(wp_remote_retrieve_body($response));

    // Lấy thông tin plugin hiện tại
    $plugin_file = plugin_basename(__FILE__); // VD: duan/duan.php
    $plugin_data = get_plugin_data(__FILE__);
    $current_version = $plugin_data['Version'];

    // So sánh phiên bản (Remote > Local)
    if ($remote_info && version_compare($current_version, $remote_info->version, '<')) {
        $obj = new stdClass();
        $obj->slug = plugin_basename(__FILE__); // Đường dẫn chính xác dạng folder/file.php
        $obj->plugin = plugin_basename(__FILE__);
        $obj->new_version = $remote_info->version;
        $obj->package = $remote_info->download_url; // Link tải file zip

        // Thêm các thông tin hiển thị (Tùy chọn)
        $obj->url = 'https://github.com/vdbaozn/qly_pc';
        $transient->response[$plugin_file] = $obj;
    }

    return $transient;
}

// Bổ sung: Hiển thị thông tin popup chi tiết bản cập nhật (khi bấm View version 5.1.2 details)
add_filter('plugins_api', 'my_pc_popup_info', 20, 3);
function my_pc_popup_info($res, $action, $args) {
    if ($action !== 'plugin_information') {
        return $res;
    }

    $plugin_file = plugin_basename(__FILE__);
    if (isset($args->slug) && $args->slug === $plugin_file) {
        $response = wp_remote_get('https://raw.githubusercontent.com/vdbaozn/qly_pc/main/info.json');
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $remote_info = json_decode(wp_remote_retrieve_body($response));
            $res = new stdClass();
            $res->name = $remote_info->name;
            $res->slug = $plugin_file;
            $res->version = $remote_info->version;
            $res->download_link = $remote_info->download_url;
            $res->sections = array(
                'description' => $remote_info->sections->description,
                'changelog' => $remote_info->sections->changelog
            );
            return $res;
        }
    }
    return $res;
}

//end update plugin
 require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '/template/database.php';
 
register_activation_hook(__FILE__, 'my_plugin_activate');

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '/template/menu_list.php';

add_action('admin_menu', 'custom_admin_menu');

//function export

add_action( 'admin_init', 'handle_export_pc_xml' );

function handle_export_pc_xml() {
    // Kiểm tra xem người dùng có bấm nút "Export XML" hay không
    if ( isset( $_GET['action'] ) && $_GET['action'] === 'export_pc_xml' ) {
        
        // (Tùy chọn) Kiểm tra quyền Admin
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Bạn không có quyền thực hiện thao tác này!' );
        }

        global $wpdb;
        $table_mem = $wpdb->prefix . 'employee';
        $table_part = $wpdb->prefix . 'part';
        $table_key_virus = $wpdb->prefix . 'key_virus';

        // 2. Lấy dữ liệu từ Database
        $members  = $wpdb->get_results( "SELECT * FROM {$table_mem}", ARRAY_A );
        $parts     = $wpdb->get_results( "SELECT * FROM {$table_part}", ARRAY_A );
        $key_virus = $wpdb->get_results( "SELECT * FROM {$table_key_virus}", ARRAY_A );

        // Xóa bộ nhớ đệm đầu ra tránh dính mã HTML trống
        if ( ob_get_length() ) {
            ob_clean();
        }

        // 3. Khởi tạo XML gốc
        $xml = new SimpleXMLElement( '<?xml version="1.0" encoding="UTF-8"?><database_export/>' );

        // --- XUẤT BẢNG PROJECT ---
        $members_node = $xml->addChild( 'members' );
        if ( ! empty( $members ) ) {
            foreach ( $members as $row ) {
                $item = $members_node->addChild( 'member' ); // SỬA: Dùng thẻ con 'member' (số ít)
                foreach ( $row as $key => $value ) {
                    $item->addChild( $key, htmlspecialchars( $value ?? '' ) );
                }
            }
        }

        // 2. Export Linh kiện (Part)
        $parts_node = $xml->addChild( 'parts' );
        if ( ! empty( $parts ) ) {
            foreach ( $parts as $row ) {
                $item = $parts_node->addChild( 'part' );
                foreach ( $row as $key => $value ) {
                    $item->addChild( $key, htmlspecialchars( $value ?? '' ) );
                }
            }
        }

        // 3. Export Key Virus
        $keys_node = $xml->addChild( 'key_viruses' );
        if ( ! empty( $key_virus ) ) {
            foreach ( $key_virus as $row ) {
                $item = $keys_node->addChild( 'key_virus' );
                foreach ( $row as $key => $value ) {
                    $item->addChild( $key, htmlspecialchars( $value ?? '' ) );
                }
            }
        }

        // 4. Header ép trình duyệt tải file về
        $filename = 'export-database-' . date( 'Y-m-d_H-i' ) . '.xml';

        header( 'Content-Type: text/xml; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );

        echo $xml->asXML();
        exit;
    }
}

add_action( 'admin_init', 'handle_import_all_tables_pc_xml' );

function handle_import_all_tables_pc_xml() {
    // 1. Kiểm tra khi người dùng submit form Import
   // Chỉ xử lý khi đúng phương thức POST và có submit button
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['submit_import_pc_xml'] ) ) {

        // Kiểm tra Nonce và Quyền hạn
        if ( ! isset( $_POST['import_xml_pc_field'] ) || ! wp_verify_nonce( $_POST['import_xml_pc_field'], 'import_xml_pc_action' ) ) {
            wp_die( 'Phiên làm việc đã hết hạn hoặc Nonce không hợp lệ. Vui lòng tải lại trang và thử lại!' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Bạn không có quyền thực hiện thao tác này!' );
        }

        // Kiểm tra file upload
        if ( empty( $_FILES['xml_file']['tmp_name'] ) || $_FILES['xml_file']['error'] !== UPLOAD_ERR_OK ) {
            wp_die( 'Lỗi khi tải file lên! Vui lòng kiểm tra lại dung lượng file.' );
        }

        $file_tmp = $_FILES['xml_file']['tmp_name'];

        libxml_use_internal_errors( true );
        $xml = simplexml_load_file( $file_tmp );

        if ( $xml === false ) {
            wp_die( 'File XML bị lỗi cấu trúc, không thể đọc được!' );
        }

        global $wpdb;
        $table_mem = $wpdb->prefix . 'employee';
        $table_part = $wpdb->prefix . 'part';
        $table_key_virus = $wpdb->prefix . 'key_virus';

        // --- 4. IMPORT BẢNG PROJECT ---
        if ( isset( $xml->members->member ) ) {
            foreach ( $xml->members->member as $member ) {
                $data = array();
                foreach ( $member->children() as $col => $val ) {
                    $data[ $col ] = (string) $val;
                }
                if ( ! empty( $data ) ) {
                    $wpdb->replace( $table_mem, $data );
                }
            }
        }

        // Import Linh kiện
        if ( isset( $xml->parts->part ) ) {
            foreach ( $xml->parts->part as $part ) {
                $data = array();
                foreach ( $part->children() as $col => $val ) {
                    $data[ $col ] = (string) $val;
                }
                if ( ! empty( $data ) ) {
                    $wpdb->replace( $table_part, $data );
                }
            }
        }

        // Import Key Virus
        if ( isset( $xml->key_viruses->key_virus ) ) {
            foreach ( $xml->key_viruses->key_virus as $key_v ) {
                $data = array();
                foreach ( $key_v->children() as $col => $val ) {
                    $data[ $col ] = (string) $val;
                }
                if ( ! empty( $data ) ) {
                    $wpdb->replace( $table_key_virus, $data );
                }
            }
        }

        // 7. Chuyển hướng thông báo thành công
        $current_page = isset( $_POST['page'] ) ? sanitize_text_field( $_POST['page'] ) : 'pc_export';
        $redirect_url = add_query_arg(
            array(
                'page'    => $current_page,
                'message' => 'import_success'
            ),
            admin_url( 'admin.php' )
        );

        wp_redirect( esc_url_raw( $redirect_url ) );
        exit;
    }
}