
<?php 
if (isset($_POST['submit'])) {
    $full_name = sanitize_text_field($_POST['full_name']);
    $ngayvao = sanitize_text_field($_POST['ngayvao']);
    $sort = sanitize_text_field($_POST['sort']);
   
    $wpdb->insert(
        $table_mem, 
        array('full_name'=>$full_name,'ngayvao'=>$ngayvao,'sort'=>$sort)
    );

    ?><script>
    window.location.href = '<?php echo $employee_edit;?>'            
</script><?php
} 
  ?>

<div class="wrap">
    
    <form method="post">
        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <tr><td colspan="2" class="txt_center"><h2>Thêm nhân viên</h2></td></tr>
            <tr><td>Họ tên</td><td><input type="text" name="full_name" placeholder="Nhập Họ Tên"></td></tr>
            <tr><td>Thứ tự</td><td><input type="text" name="sort" placeholder="Nhập thứ tự"></td></tr>
            <tr><td>Ngày vào</td><td><input type="date" name="ngayvao"></td></tr>
            <tr><td colspan="2" class="txt_center"><input type="submit" name="submit" value="Lưu"></td></tr>
        </table>
    </form>
</div>