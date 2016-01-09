<p><a href="?action=edit&id=0"><button type="button" class="btn btn-default btn-sm">Apply Leave</button></a></p>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>From</th>
                <th>To</th>
                <th>Leave</th>
                <th>Status</th>
                <th>Approved By</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
                <?php
                    $currentUserId = $loginCtrl->GetUserId();
                    $leaveList = $leaveCtrl->GetLeaveByUserId($currentUserId);
                    foreach( $leaveList as $usrLeave ){
                        echo '<tr>';
                        echo '<td>'.$usrLeave->LeaveType->LeaveName.'</td>';
                        echo '<td>'.datetime::createfromformat('Y-m-d 00:00:00',$usrLeave->LeaveDateFrom)->format('d M Y').'</td>';
                        echo '<td>'.datetime::createfromformat('Y-m-d 00:00:00',$usrLeave->LeaveDateTo)->format('d M Y').'</td>';
                        echo '<td>';
                        echo '<p>Current: '.$usrLeave->TotalLeave.'</p>';
                        echo '<p>Bringforward: '.$usrLeave->TotalBringForwardLeave.'</p>';
                        echo '</td>';
                        echo '<td>'.$usrLeave->LeaveStatus->StatusName.'</td>';
                        if( $usrLeave->LeaveStatus->Id == 1 ){
                            //new status
                            echo '<td>&nbsp;</td>';
                        }else{
                            echo '<td>'.$usrLeave->AccessUser->Email.'</td>';
                        }
                        echo '<td><a href="?action=edit&id='.$usrLeave->Id.'"><button type="button" class="btn btn-default btn-sm">View</button></a></td>';
                        echo '</tr>';
                    }
                ?>
        </tbody>
    </table>
</div>