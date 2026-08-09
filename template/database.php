<?php 
// $table_mem = $wpdb->prefix . 'employee';
// $table_part = $wpdb->prefix . 'part';
// $table_key_virus = $wpdb->prefix . 'key_virus';

// $charset_collate = $wpdb->get_charset_collate();

/**
 * Hàm chính thực thi khi kích hoạt plugin
 */
function my_plugin_activate() {
    global $wpdb;
    $table_mem = $wpdb->prefix . 'employee';
    $table_part = $wpdb->prefix . 'part';
    $table_key_virus = $wpdb->prefix . 'key_virus';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_mem (
        `id_mem` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `email` varchar(50) NOT NULL,
        `sort` int(11) NOT NULL,
        `full_name` varchar(100) NOT NULL,
        `ngayvao` date DEFAULT NULL,
        `status` int(11) NOT NULL

    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $sql2 = "CREATE TABLE $table_part (
      `id_part` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
      `pc` varchar(200) NOT NULL,
      `value` varchar(200) not null,
      `id_mem` int(11) NOT NULL, FOREIGN KEY (id_mem) REFERENCES $table_mem(id_mem)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $sql3 = "CREATE TABLE $table_key_virus (
      `id_key` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
      `pc` varchar(200) not null,
      `ngayactive` INT NOT NULL,
      `ngayhethan` INT NOT NULL,
      `sort` int not null
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

   
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql2);
    dbDelta($sql3);
   
}



?>