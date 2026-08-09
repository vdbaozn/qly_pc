<?php 
global $wpdb; // Đối tượng cơ sở dữ liệu WordPress
$table_mem = $wpdb->prefix . 'employee';
$table_part = $wpdb->prefix . 'part';
$menu_slug = 'employee'; // Thay 'menu-slug' bằng slug của menu bạn muốn lấy liên kết
$employee_edit = menu_page_url($menu_slug, false);?>
<div class="wrap">
    <div class="dflex">
        <h2>Danh sách nhân viên</h2>
        <a href="<?php echo $employee_edit;?>&actions=add">Thêm</a>
        <a href="<?php echo $employee_edit;?>&actions=off">Off</a>
    </div><?php

    $action = isset($_GET['actions']);

    if($action && $_GET['actions']=='del'){
        $employee_id = $_GET['id'];
        // echo $employee_id;
        $count_part = $wpdb->get_var("SELECT count(*) FROM $table_part WHERE id_mem = $employee_id");
        
       
        $row_mem = $wpdb->get_row("SELECT a.id_mem, a.full_name FROM $table_mem a WHERE a.id_mem = $employee_id");
        // pre($row_kt);
       
        if($count_part > 0){
            echo 'Nhân viên <span class="foreign">'.$row_mem->full_name.'</span> tồn tại khóa ngoại trong <a href="'.admin_url().'admin.php?page=part_list&actions=search&id_mem='.$row_mem->id_mem.'"><span class="foreign">bảng PC</span></a><br>';
        }
        else{
            $wpdb->delete(
                $table_mem,
                array('id_mem' => $employee_id),
                array('%d')
            );
            
            if ($wpdb->last_error) {
                echo 'Lỗi khi xóa nhân viên: ' . $wpdb->last_error;
            } else {
                echo 'Nhân viên đã được xóa thành công.';        ?>
                <script>
                    window.location.href = '<?php echo $employee_edit;?>'            
                </script><?php
            }
        }

        echo '<br>'.$count;        
    } 

    if(isset($_GET['actions']) ){
        if($_GET['actions']=='add'){
            require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'employee_add.php';
        } 
        if($_GET['actions']=='edit'){
            require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'employee_edit.php';
        } 
        if($_GET['actions']=='off'){
            require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'employee_off.php';
        } 
        if($_GET['actions']=='status'){
            $id = $_GET['id'];
            $employee_data = $wpdb->get_row("SELECT * FROM $table_mem WHERE id_mem = $id");

            echo $employee_data->full_name;
            $status = $employee_data->status;
            if($status==1){
                $status=0;
            }
            else{
                $status=1;
            }
            
            $wpdb->update(
                $table_mem,
                array(
                    'status'=>$status
                ),
                array('id_mem' => $id),
                array(
                    '%s', 
                    '%s', 
                ),
                array('%d')
            );        ?>
            <script>
                window.location.href = '<?php echo admin_url()?>/admin.php?page=employee'            
            </script><?php
        } 
            
    }
    else{ 
        $employees = $wpdb->get_results("SELECT * FROM $table_mem WHERE status=0 order by sort");
        $table_client = $wpdb->prefix . 'company_client'; 
        $table_part = $wpdb->prefix . 'part';       
        $i=0;
        echo '<textarea name="" >';
        foreach ($employees as $employee) {
            if( $employee->email){
                echo $employee->email.',';
            }
            
        }
        echo '</textarea>';
        ?>
    
        <div class="table__02">
            <form method="post">
            <table class="wp-list-table widefat fixed striped table-view-list posts">
                <tr><td>STT</td><td>PC</td><td>HỌ TÊN</td><td>EMAIL</td><td>STATUS</td><td>SỬA</td><td>XÓA</td></tr><?php
                                
                
                foreach ($employees as $employee) {
                    $i++;
                    $status = "";
                    $ngayactive = $employee->ngayactive;
                    $ngayactive = date('Y/m/d', strtotime($ngayactive));
                    $ngayhethan = $employee->ngayhethan;
                    $ngayhethan = date('Y/m/d', strtotime($ngayhethan));       
                    $part = $wpdb->get_row("SELECT * FROM $table_part WHERE id_mem = $employee->id_mem");  
                    $current_date = new DateTime();
                    // Ngày vào
                    $start_date = new DateTime($ngayhethan);              

                    $thoiGianConLai = $current_date->diff($start_date);
                    $thoiGianConLai = $thoiGianConLai->format("%R%a");
                    $class="";
                    echo ' <tr '.$class.'>';
                    echo '<td>'.$i.'</td>';
                    echo '<td>'.$part->pc.'</td>';
                    echo '<td>' . $employee->full_name . '</td>';
                    echo '<td>' . $employee->email . '</td>';
                    echo '<td><a href="'.esc_url($employee_edit).'&actions=status&id='.$employee->id_mem.'">Hiện</a></td>';
                    echo '<td><a href="'.esc_url($employee_edit).'&actions=edit&id='.$employee->id_mem.'">Sửa</a></td><td><a href="'.esc_url($employee_edit).'&actions=del&id='.$employee->id_mem.'" onclick="check_del(event)">Xóa</td>';
                }
                ?>
            </table></form>
        </div><?php 
    }?>
</div>