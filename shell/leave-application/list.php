<p><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id=0">Apply Leave</a></button></p>
<?php
    echo 'php version: '.phpversion();
?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Total leave</th>
                <th>Status</th>
                <th>Approved By</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
                <?php
                    $currentUserId = $loginCtrl->GetUserId();
                    $leaveCtrl->GetLeaveByUserId($currentUserId);
                    // foreach( $leaveCtrl->GetLeaveByUserId($currentUserId) as $usrLeave ){
                    //     echo '<tr>';
                    //     echo '<td>'.datetime::createfromformat('Y-m-d h:m:s',$usrLeave->LeaveDateFrom)->format('d M Y').'</td>';
                    //     echo '<td>'.datetime::createfromformat('Y-m-d h:m:s',$usrLeave->LeaveDateTo)->format('d M Y').'</td>';
                    //     echo '<td>'.$usrLeave->TotalLeave.'</td>';
                    //     echo '<td>'.$usrLeave->TotalLeave.'</td>';
                    //     echo '<td>'.$usrLeave->TotalLeave.'</td>';
                    //     echo '<td><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id='.$usrLeave->Id.'">Edit</a></button></td>';
                    //     echo '</tr>';
                    // }
                ?>
        </tbody>
    </table>
</div>