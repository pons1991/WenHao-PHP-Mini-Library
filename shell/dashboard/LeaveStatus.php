<div class="panel panel-success">
    <div class="panel-heading"><h3 class="panel-title">Your Leave Status</h3> </div>
    <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Leave</th>
                        <th>Bringforward Leave</th>
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
                                echo '<td>'.datetime::createfromformat('Y-m-d 00:00:00',$usrLeave->LeaveDateFrom)->format('d M Y').'</td>';
                                echo '<td>'.datetime::createfromformat('Y-m-d 00:00:00',$usrLeave->LeaveDateTo)->format('d M Y').'</td>';
                                echo '<td>'.$usrLeave->TotalLeave.'</td>';
                                echo '<td>'.$usrLeave->TotalBringForwardLeave.'</td>';
                                echo '<td>'.$usrLeave->LeaveStatus->StatusName.'</td>';
                                if( $usrLeave->LeaveStatus->Id == 1 ){
                                    //new status
                                    echo '<td>&nbsp;</td>';
                                }else{
                                    echo '<td>'.$usrLeave->ApprovedByUser->Email.'</td>';
                                }
                                echo '<td><a href="?action=edit&id='.$usrLeave->Id.'"><button type="button" class="btn btn-default btn-sm">View</button></a></td>';
                                echo '</tr>';
                            }
                        ?>
                </tbody>
            </table>
        </div>
</div>