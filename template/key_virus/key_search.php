<form method="post">
    <table class="wp-list-table widefat fixed striped table-view-list posts">        
        <tr><td>STT</td><td><a href="<?php echo $employee_edit;?>&order=pc">PC</a></td><td>NHÂN VIÊN</td><td>NGÀY ACTIVE</td><td>NGÀY HẾT HẠN</td><td>SỐ NGÀY</td><td>THỨ TỰ</td><td>SỬA</td><td>XÓA</td></tr>    <?php
        $order = "ngayhethan, sort";
        
        if(isset($_GET['order'])){
            if($_GET['order']=='pc'){
                $order = "pc";
                
            }
            
        }
        $pc = $_GET['pc'];
        $key_list = $wpdb->get_results("SELECT a.id_key, a.pc, a.ngayactive,a.ngayhethan, a.sort FROM $table_key_virus a, $table_part b WHERE a.pc = b.pc AND a.pc = $pc  ORDER BY a.$order");
       
        $i=0;
        foreach ($key_list as $item) {
            $pc = $item->pc;
            $client_item = $wpdb->get_row("SELECT * FROM $table_mem a, $table_part b  WHERE a.id_mem=b.id_mem AND b.pc = $pc");  
            
            $i++;
            $ngayactive = $item->ngayactive;
            $ngayactive = date('Y/m/d', strtotime($ngayactive));
            $ngayhethan = $item->ngayhethan;
            $ngayhethan = date('Y/m/d', strtotime($ngayhethan));

            $current_date = new DateTime();
            
            $start_date = new DateTime($ngayhethan);
            
            $thoiGianConLai = $current_date->diff($start_date);

            $thoiGianConLai = $thoiGianConLai->format("%R%a");
            $class="";
            if($thoiGianConLai < 7){
                $class="style='background: #ff0;'";
            }
            if($thoiGianConLai < 0){
                if($item->value=='other'){
                    $class="style='background: #99999999;'";
                }else{
                    $class="style='background: #ff000099;'";
                }
                
            }
            echo ' <tr '.$class.'>';
            echo '<td>'.$i.'</td>';
            echo '<td>'.$item->pc.'</td>';
            echo '<td>'.$client_item->full_name.'</td>';
            echo '<td>'.$ngayactive.'</td>';
            echo '<td>'.$ngayhethan.'</td>';
            echo '<td>'.$thoiGianConLai.'</td>';
            echo '<td>'.$item->sort.'</td>';
            echo '<td><a href="'.esc_url($employee_edit).'&actions=edit&id='.$item->id_key.'">Sửa</td><td><a href="'.esc_url($employee_edit).'&actions=del&id='.$item->id_key.'" onclick="check_del(event)">Xóa</td>';
            echo '</tr>';
        }    ?>
    </table>
</form>