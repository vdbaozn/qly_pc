<?php //header("Location: /index.php");
$employee_id = $_GET['id']; // Thay 5 bằng ID của nhân viên bạn muốn lấy thông tin
$employee_data = $wpdb->get_row("SELECT * FROM $table_mem WHERE id_mem = $employee_id");
if ($employee_data) {    
        ?>
    <div class="wrap">
        <form method="post">
        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <tr><td colspan="2" class="txt_center"><h2>Sửa nhân viên</h2></td></tr>

            <tr><td>Họ tên</td><td><input type="text" name="full_name" value="<?php echo $employee_data->full_name; ?>" placeholder="Nhập Họ Tên"></td></tr>
            <tr><td>Email</td><td><input type="text" name="email" value="<?php echo $employee_data->email; ?>" placeholder="Nhập Email"></td></tr>            
            <tr><td>Ngày vào</td><td><input type="date" name="ngayvao" value="<?php echo $employee_data->ngayvao;?>"></td></tr>
            <tr><td>Thứ tự</td><td><input type="text" name="sort" value="<?php echo $employee_data->sort;?>" placeholder="Nhập thứ tự"></td></tr>
                       
            <tr><td colspan="2" class="txt_center"><input type="submit" name="submit" value="Lưu"></td></tr>
        </table>            
        </form>
    </div>
    
<?php // Thêm các trường khác tùy theo cơ sở dữ liệu của bạn
} else {
    echo 'Không tìm thấy thông tin nhân viên.';
}

if (isset($_POST['submit'])) {
    $full_name = sanitize_text_field($_POST['full_name']);
    $email = sanitize_text_field($_POST['email']);
    $ngayvao = sanitize_text_field($_POST['ngayvao']);
    $sort = sanitize_text_field($_POST['sort']);
   

    $wpdb->update(
        $table_mem,
        array('full_name'=>$full_name,'sort'=>$sort,'email' => $email,'ngayvao' => $ngayvao),
        array('id_mem' => $employee_id),
        array(
            '%s', 
            '%s', 
        ),
        array('%d')
    );
    
    if ($wpdb->last_error) {
        echo 'Lỗi khi cập nhật thông tin nhân viên: ' . $wpdb->last_error;
    } else {
        
        echo 'Thông tin nhân viên đã được cập nhật thành công.<br>';
        $menu_slug = 'employee'; // Thay 'menu-slug' bằng slug của menu bạn muốn lấy liên kết
        $employee_edit = menu_page_url($menu_slug, false);
        
        wp_redirect( $employee_edit); 
        exit;?>

        <?php
    }
}
?>