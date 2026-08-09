
<div class="wrap">

        <div class="table__02">
            <form method="post">
            <table class="wp-list-table widefat fixed striped table-view-list posts">
                <tr><td>STT</td><td>PC</td><td>HỌ TÊN</td><td>STATUS</td><td>SỬA</td><td>XÓA</td></tr><?php
                // Truy vấn để lấy danh sách nhân viên từ bảng cơ sở dữ liệu của bạn
                
                $employees = $wpdb->get_results("SELECT * FROM $table_mem WHERE status=1 ");

                $table_part = $wpdb->prefix . 'part';         

                // Hiển thị thông tin từng nhân viên
                $i=0;
                foreach ($employees as $employee) {
                    $i++;
                    $status = "";
                    $ngayactive = $employee->ngayactive;
                    $ngayactive = date('Y/m/d', strtotime($ngayactive));
                    $ngayhethan = $employee->ngayhethan;
                    $ngayhethan = date('Y/m/d', strtotime($ngayhethan));       
                    $part = $wpdb->get_row("SELECT * FROM $table_part WHERE id_mem = $employee->id_mem");       

                    // Ngày hiện tại
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
                    echo '<td><a href="'.esc_url($employee_edit).'&actions=status&id='.$employee->id_mem.'">Hiện</a></td>';
                    echo '<td><a href="'.esc_url($employee_edit).'&actions=edit&id='.$employee->id_mem.'">Sửa</a></td><td><a href="'.esc_url($employee_edit).'&actions=del&id='.$employee->id_mem.'" onclick="check_del(event)">Xóa</td>';
                }
                ?>
            </table></form>
        </div>
</div>