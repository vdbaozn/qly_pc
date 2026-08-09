<?php global $wpdb; // Đối tượng cơ sở dữ liệu WordPress
$table_part = $wpdb->prefix . 'part';
$table_mem = $wpdb->prefix . 'employee'; 
$table_key_virus = $wpdb->prefix . 'key_virus';
$menu_slug = 'key_list'; // Thay 'menu-slug' bằng slug của menu bạn muốn lấy liên kết
$employee_edit = menu_page_url($menu_slug, false);?>
<div class="wrap">
    <div class="dflex">
        <h2>Danh sách bộ phận</h2>
        <a href="<?php echo $employee_edit;?>&actions=add">Thêm</a>
       
    </div>
    
 <?php
$action = isset($_GET['actions']);

if($action && $_GET['actions']=='del'){
    $id = $_GET['id'];

    // echo $id;
    $count_client = $wpdb->get_var("SELECT count(*) FROM $table_company_client WHERE id_part = $id");
    $row_bp = $wpdb->get_row("SELECT * FROM $table_company_client a, $table_part b WHERE a.id_part = $id AND a.id_part=b.id_part");

    if($count_client > 0){
        // echo 'Đang có khóa ngoại';
        echo 'Bộ phận <span class="foreign">'.$row_bp->part_name.'</span> tồn tại khóa ngoại trong <a href="'.admin_url().'admin.php?page=client_list&actions=search&id_part='.$row_bp->id_part.'"><span class="foreign">công ty khách hàng</span></a><br>';
    }else{
        $wpdb->delete(
            $table_key_virus,
            array('id_key' => $id),
            array('%d')
        );
        
        if ($wpdb->last_error) {
            echo 'Lỗi khi xóa bộ phận: ' . $wpdb->last_error;
        } else {
            echo 'Bộ phận đã được xóa thành công.';        ?>
                <script>
                    window.location.href = '<?php echo $employee_edit;?>'            
                </script>
            <?php
        }
    }

    
}


if(isset($_GET['actions'])){
    if($_GET['actions']=='search'){
        require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'key_search.php';
    }
    if($_GET['actions']=='edit'){
        require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'key_edit.php';
    }
    if($_GET['actions']=='add'){
        require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'key_add.php';
    }
        
}
else{
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'key_main.php'; ?>
    
<?php }?>
</div>