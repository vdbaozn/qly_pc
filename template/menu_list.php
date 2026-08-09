<?php 
function custom_admin_menu() {
    // Thêm một menu tùy chỉnh vào trái admin menu
    add_menu_page(
        'Nhân viên', // Tên của menu
        'Nhân viên', // Tên hiển thị trên admin menu
        'manage_options', // Quyền truy cập cần thiết
        'employee', // Slug của menu (duy nhất)
        'custom_menu_callback', // Callback function để hiển thị nội dung menu
        'dashicons-admin-generic', // Icon của menu (có thể thay đổi)
        99 // Thứ tự hiển thị
    );
  
    // Thêm một sub-menu cho menu chính
    add_submenu_page(
      'employee', // Slug của menu chính
      'PC', // Tên hiển thị trên sub-menu
      'PC', // Tên hiển thị trên trang quản trị
      'manage_options', // Quyền truy cập cần thiết
      'part_list', // Slug của sub-menu (duy nhất)
      'part_list_callback' // Callback function để hiển thị nội dung sub-menu
    );

    /*add_submenu_page(
      'employee', // Slug của menu chính
      'KEY', // Tên hiển thị trên sub-menu
      'KEY', // Tên hiển thị trên trang quản trị
      'manage_options', // Quyền truy cập cần thiết
      'key_list', // Slug của sub-menu (duy nhất)
      'key_list_callback' // Callback function để hiển thị nội dung sub-menu
    );*/

    add_submenu_page(
      'employee', // Slug của menu chính
      'EMAIL', // Tên hiển thị trên sub-menu
      'EMAIL', // Tên hiển thị trên trang quản trị
      'manage_options', // Quyền truy cập cần thiết
      'email_list', // Slug của sub-menu (duy nhất)
      'email_list_callback' // Callback function để hiển thị nội dung sub-menu
    );

    add_submenu_page(
        null,             // Slug của Menu Cha
        'Backup',                 // Title trang con
        'Backup',                 // Tên submenu hiển thị
        'manage_options',                  // Quyền hạn
        'pc_backup',          // Slug duy nhất của Submenu
        'my_backup_pc_callback'  // Hàm hiển thị nội dung trang con
    );
      
  }

  function custom_menu_callback() {
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'employee/nhanvien_index.php'; 
  }  

  function part_list_callback() {
    // Nội dung của sub-menu tùy chỉnh ở đây
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'part/part_list.php';
  }
  
  function key_list_callback() {
    // Nội dung của sub-menu tùy chỉnh ở đây
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'key_virus/key_list.php';
  }
  
  function email_list_callback() {
    // Nội dung của sub-menu tùy chỉnh ở đây
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'employee/email_list.php';
  }

  function my_backup_pc_callback() {
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'backup.php';
  }