<?php global $wpdb; // Đối tượng cơ sở dữ liệu WordPress
$table_part = $wpdb->prefix . 'part';
$table_mem = $wpdb->prefix . 'employee'; 
$table_key_virus = $wpdb->prefix . 'key_virus';
$menu_slug = 'part_list'; // Thay 'menu-slug' bằng slug của menu bạn muốn lấy liên kết
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
    $row_pc = $wpdb->get_row("SELECT * FROM $table_part a WHERE a.id_part = $id ");
    $count_client = $wpdb->get_var("SELECT count(*) FROM $table_key_virus WHERE pc = $row_pc->pc");

    if($count_client > 0){
        // echo 'Đang có khóa ngoại';
        echo 'PC <span class="foreign">'.$row_pc->pc.'</span> tồn tại khóa ngoại trong <a href="'.admin_url().'admin.php?page=key_list&actions=search&pc='.$row_pc->pc.'"><span class="foreign">key virus</span></a><br>';
    }else{
        $wpdb->delete(
            $table_part,
            array('id_part' => $id),
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
    if($_GET['actions']=='edit'){
        require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'part_edit.php';
    }
    if($_GET['actions']=='add'){
        require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'part_add.php';
    }
        
}
else{
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'part_main.php'; ?>
    
<?php }?>
</div>