<?php 
global $wpdb;
$employee_id = $_GET['id']; // Thay 5 bằng ID của nhân viên bạn muốn lấy thông tin

$employee_data = $wpdb->get_row("SELECT * FROM $table_key_virus WHERE id_key = $employee_id");

if ($employee_data) {
    $ngayactive = $employee_data->ngayactive; 
    $ngayactive = date('Y/m/d', strtotime($ngayactive));
    $ngayhethan = $employee_data->ngayhethan; 
    $ngayhethan = date('Y/m/d', strtotime($ngayhethan));  
    ?>
    <div class="wrap">        
        <form method="post">
            <table class="wp-list-table widefat fixed striped table-view-list posts">
                <tr><td colspan="2" class="txt_center"><h2>Sửa bộ phận</h2></td></tr>
                <tr><td>PC</td><td><span class="foreign input"><?php echo $employee_data->pc;?></span></td></tr>
                <tr><td>Ngày active</td><td><input type="text" name="ngayactive" value="<?php echo $ngayactive;?>" placeholder="Nhập ngày active"></td></tr>
                <tr><td>Ngày hết hạn</td><td><input type="text" name="ngayhethan" value="<?php echo $ngayhethan;?>" placeholder="Nhập ngày hết hạn"></td></tr>
                <tr><td>Thứ tự</td><td><input type="text" name="sort" value="<?php echo $employee_data->sort;?>" placeholder="Nhập thứ tự"></td></tr>
            
            <tr><td colspan="2" class="txt_center"><input type="submit" name="submit" value="Lưu"></td></tr>
            </table>            
        </form>
    </div>

<?php } else {
    echo 'Không tìm thấy thông tin bộ phận.';
}

if (isset($_POST['submit'])) {
    $ngayactive = sanitize_text_field($_POST['ngayactive']);
    $ngayhethan = sanitize_text_field($_POST['ngayhethan']);
    $sort = sanitize_text_field($_POST['sort']);
    $ngayactive = strtotime($ngayactive);
    $ngayactive = intval(date('Ymd', $ngayactive));
    $ngayhethan = strtotime($ngayhethan);
    $ngayhethan = intval(date('Ymd', $ngayhethan));
   
    $wpdb->update(
        $table_key_virus,
        
        array('ngayactive'=>$ngayactive,'ngayhethan'=>$ngayhethan,'sort'=>$sort),
        array('id_key' => $employee_id),
        array(
            '%s', // Định dạng cho trường 'name' (string)
            '%s', // Định dạng cho trường 'email' (string)
        ),
        array('%d') // Định dạng cho trường 'id' (integer)
    );
    
    if ($wpdb->last_error) {
        echo 'Lỗi khi cập nhật thông tin bộ phận: ' . $wpdb->last_error;
    } else {
        echo 'Thông tin bộ phận đã được cập nhật thành công.';        ?>
            <script>
                window.location.href = '<?php echo $employee_edit;?>'            
            </script>
        <?php
    }
}


?>