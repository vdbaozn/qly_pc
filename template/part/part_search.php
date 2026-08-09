<form method="post">
    <table class="wp-list-table widefat fixed striped table-view-list posts">        
        <tr><td>STT</td><td>PC</td><td>NHÂN VIÊN</td><td>NGÀY ACTIVE</td><td>NGÀY HẾT HẠN</td><td>SỐ NGÀY</td><td>SỬA</td><td>XÓA</td></tr>    <?php
        $order = "pc";
        
        $id = $_GET['id_mem'];
        $part_list = $wpdb->get_results("SELECT * FROM $table_part WHERE id_mem = $id  order by $order");
       

        $i=0;
        foreach ($part_list as $item) {
            $id_mem = $item->id_mem;
            $pc = $item->pc;
            $client_item = $wpdb->get_row("SELECT * FROM $table_mem WHERE id_mem = $id_mem");        
            $key_item = $wpdb->get_row("SELECT * FROM $table_key_virus WHERE pc = $pc");  
            $i++;
            $ngayactive = $key_item->ngayactive;
            
            if($ngayactive > 0){
                $ngayactive = date('Y/m/d', strtotime($ngayactive));
            }
            $ngayhethan = $key_item->ngayhethan;
            if($ngayhethan > 0){
                $ngayhethan = date('Y/m/d', strtotime($ngayhethan));
            }
            $current_date = new DateTime();
            
            $start_date = new DateTime($ngayhethan);
            
            $thoiGianConLai = $current_date->diff($start_date);

            $thoiGianConLai = $thoiGianConLai->format("%R%a");
            
            $class="";
            if($thoiGianConLai < 1){
                $class="style='background: #ff000099;'";
            }
            if($client_item->full_name==""){
                $class="style='background: #99999999;'";
            }
            if($ngayactive < 1){
                $thoiGianConLai = "";
            }
            
            echo ' <tr '.$class.'>';
            echo '<td>'.$i.'</td>';
            echo '<td>'.$item->pc.'</td>';
            echo '<td>'.$client_item->full_name.'</td>';
            echo '<td>'.$ngayactive.'</td>';
            echo '<td>'.$ngayhethan.'</td>';
            echo '<td>'.$thoiGianConLai.'</td>';
            echo '<td><a href="'.esc_url($employee_edit).'&actions=edit&id='.$item->id_part.'">Sửa</td><td><a href="'.esc_url($employee_edit).'&actions=del&id='.$item->id_part.'" onclick="check_del(event)">Xóa</td>';
            echo '</tr>';
        }    ?>
    </table>
</form>