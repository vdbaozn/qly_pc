
<?php 
if (isset($_POST['submit'])) {
    $pc = sanitize_text_field($_POST['pc']);
    $value = sanitize_text_field($_POST['value']);
    $id_mem = sanitize_text_field($_POST['id_mem']);
    $count_kh = $wpdb->get_var("SELECT count(*) FROM $table_part WHERE pc = $pc");
    if($count_kh > 0){
        echo 'PC '.$pc.' đã tồn tại!';
    }else{
        $wpdb->insert(
            $table_part, // Thay bằng tên bảng cơ sở dữ liệu của bạn
            array('pc' => $pc,'value'=>$value,'id_mem' => $id_mem)
        );
    
        ?><script>
            window.location.href = '<?php echo $employee_edit;?>'            
        </script><?php
    }
   
    
}
$employee_list = $wpdb->get_results("SELECT a.id_mem, a.full_name FROM $table_mem a LEFT JOIN $table_part b ON a.id_mem = b.id_mem WHERE b.id_mem IS NULL AND a.status=0");
?>

<div class="wrap">
    
    <form method="post">
        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <tr><td colspan="2" class="txt_center"><h2>Thêm PC</h2></td></tr>
            <tr><td>PC</td><td><input type="text" name="pc" placeholder="Nhập PC"></td></tr>
            <tr><td>Value</td><td><input type="text" name="value" placeholder="Nhập value"></td></tr>
            <tr><td>Nhân viên</td><td><select name="id_mem" >
            <option value="">Chọn nhân viên</option>
            <?php foreach($employee_list as $item){
                echo '<option value="'.$item->id_mem.'">'.$item->full_name.'</option>';
            }?>
        </select></td></tr>
            <tr><td colspan="2" class="txt_center"><input type="submit" name="submit" value="Lưu"></td></tr>
        </table>
        
        
        
    </form>
</div>
