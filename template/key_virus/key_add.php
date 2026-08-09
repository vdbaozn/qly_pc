
<?php 
$part_list = $wpdb->get_results("SELECT a.pc FROM $table_part a LEFT JOIN $table_key_virus b ON a.pc = b.pc WHERE b.pc IS NULL");
if (isset($_POST['submit'])) {
    $pc = sanitize_text_field($_POST['pc']);
    $ngayactive = sanitize_text_field($_POST['ngayactive']);
    $ngayhethan = sanitize_text_field($_POST['ngayhethan']);
    $sort = sanitize_text_field($_POST['sort']);
    $ngayactive = strtotime($ngayactive);
    $ngayactive = intval(date('Ymd', $ngayactive));
    $ngayhethan = strtotime($ngayhethan);
    $ngayhethan = intval(date('Ymd', $ngayhethan));

    // Lưu vào cơ sở dữ liệu
    $wpdb->insert(
        $table_key_virus, // Thay bằng tên bảng cơ sở dữ liệu của bạn
        array('pc' => $pc,'ngayactive'=>$ngayactive,'ngayhethan'=>$ngayhethan,'sort'=>$sort)
    );

    ?><script>
    window.location.href = '<?php echo $employee_edit;?>'            
</script><?php
}
?>

<div class="wrap">    
    <form method="post">
        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <tr><td colspan="2" class="txt_center"><h2>Thêm PC</h2></td></tr>
                <tr><td>PC</td><td><select name="pc" >
                <option value="">PC</option>
                <?php 
                $select ="";
                foreach($part_list as $item){
                    if($item->pc == $employee_data->pc){
                        $select ="selected";
                    } else{ $select ="";}
                    echo '<option '.$select.' value="'.$item->pc.'">'.$item->pc.'</option>';
                }?>
            </select></td></tr>
            <tr><td>Ngày active</td><td><input type="text" name="ngayactive" placeholder="Nhập ngày active"></td></tr>
                <tr><td>Ngày hết hạn</td><td><input type="text" name="ngayhethan" placeholder="Nhập ngày hết hạn"></td></tr>
                <tr><td>Thứ tự</td><td><input type="text" name="sort" placeholder="Nhập thứ tự"></td></tr>
            
            <tr><td colspan="2" class="txt_center"><input type="submit" name="submit" value="Lưu"></td></tr>
        </table>        
    </form>
</div>
