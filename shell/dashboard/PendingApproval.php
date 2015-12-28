<div class="panel panel-danger">
    <div class="panel-heading"><h3 class="panel-title">Pending for approval leave</h3> </div>
    <?php 
        $currentUserId = $loginCtrl->GetUserId();
        $leaveList = $leaveCtrl->GetLeaveByUserId($currentUserId);
        if( count($leaveList) > 0 ){
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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                    foreach( $leaveList as $usrLeave ){
                                        echo '<tr>';
                                        echo '<td>'.datetime::createfromformat('Y-m-d h:m:s',$usrLeave->LeaveDateFrom)->format('d M Y').'</td>';
                                        echo '<td>'.datetime::createfromformat('Y-m-d h:m:s',$usrLeave->LeaveDateTo)->format('d M Y').'</td>';
                                        echo '<td>'.$usrLeave->TotalLeave.'</td>';
                                        echo '<td>'.$usrLeave->LeaveStatus->StatusName.'</td>';
                                        if( $usrLeave->LeaveStatus->Id == 1 ){
                                            //new status
                                            echo '<td>&nbsp;</td>';
                                        }else{
                                            echo '<td>'.$usrLeave->AccessUser->Email.'</td>';
                                        }
                                        echo '<td><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id='.$usrLeave->Id.'">View</a></button></td>';
                                        echo '</tr>';
                                    }
                                ?>
                        </tbody>
                    </table>
                </div>
            <?php
        }else{
            ?>
                <div class="panel-body">No record found.</div>
            <?php
        }
    ?>
</div>