<p><a href="?action=edit&id=0"><button type="button" class="btn btn-default btn-sm">New Accumulative Leave</button></a></p>
<?php 
    $accumulativeLeaveList = $leaveCtrl->GetAccumulativeLeave();
?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Leave Type</th>
                <th># Leave</th>
                <th>Expiry Year</th>
                <th>Remarks</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    foreach( $accumulativeLeaveList as $acc ){
                        echo '<tr>';
                        echo '<td>'.$acc->AccessUser->Email.'</td>';
                        echo '<td>'.$acc->LeaveType->LeaveName.'</td>';
                        echo '<td>'.$acc->AccumulativeLeaveNumber.'</td>';
                        echo '<td>'.$acc->ExpiredYear.'</td>';
                        echo '<td>'.$acc->Remarks.'</td>';
                        echo '<td><a href="?action=edit&id='.$acc->Id.'"><button type="button" class="btn btn-default btn-sm">Edit</button></a></td>';
                        echo '</tr>';
                    }
                ?>
        </tbody>
    </table>
</div>