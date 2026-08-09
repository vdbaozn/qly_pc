<?php 
global $wpdb;
$employee_id = $_GET['id']; // Thay 5 bằng ID của nhân viên bạn muốn lấy thông tin

$employee_data = $wpdb->get_row("SELECT * FROM $table_part WHERE id_part = $employee_id");

if ($employee_data) {
    $employee_list = $wpdb->get_results("SELECT * FROM $table_mem WHERE status=0");  ?>
    <div class="wrap">        
        <form method="post">
            <table class="wp-list-table widefat fixed striped table-view-list posts">
                <tr><td colspan="2" class="txt_center"><h2>Sửa bộ phận</h2></td></tr>
                <tr><td>PC</td><td><span class="foreign input"><?php echo $employee_data->pc;?></span></td></tr>
                <tr><td>Value</td><td><input type="text" name="value" value="<?php echo $employee_data->value;?>" placeholder="Nhập value"></td></tr>               
            
                <tr><td>Nhân viên</td><td><select name="id_mem" >
                <option value="">Nhân viên</option>
                <?php 
                $select ="";
                foreach($employee_list as $item){
                    if($item->id_mem == $employee_data->id_mem){
                        $select ="selected";
                    } else{ $select ="";}
                    echo '<option '.$select.' value="'.$item->id_mem.'">'.$item->full_name.'</option>';
                }?>
            </select></td></tr>
            <tr><td colspan="2" class="txt_center"><input type="submit" name="submit" value="Lưu"></td></tr>
            </table>            
        </form>
    </div>

<?php } else {
    echo 'Không tìm thấy thông tin bộ phận.';
}

if (isset($_POST['submit'])) {
    $value = sanitize_text_field($_POST['value']);
    $id_mem = sanitize_text_field($_POST['id_mem']);
   
    $wpdb->update(
        $table_part,
        
        array('value'=>$value,'id_mem' => $id_mem),
        array('id_part' => $employee_id),
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