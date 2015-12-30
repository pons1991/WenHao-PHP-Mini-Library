<div class="panel panel-danger">
    <div class="panel-heading"><h3 class="panel-title">Pending for approval leave</h3> </div>
    <?php 
        $currentUserId = $loginCtrl->GetUserId();
        $userList = $userCtrl->GetOrgRelBySupervisorId($currentUserId);
        
        $userIdList = array();
        foreach( $userList as $usr ){
            array_push($userIdList, $usr->UserId);
        }
        
        $pendingApprovalLeave = $leaveCtrl->GetPendingLeaveByUserIds($userIdList);
        if( $pendingApprovalLeave != null && count($pendingApprovalLeave) > 0 ){
            ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>By</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Total leave</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                    foreach( $pendingApprovalLeave as $usrLeave ){
                                        echo '<tr>';
                                        echo '<td>'.$usrLeave->AccessUser->Email.'</td>';
                                        echo '<td>'.datetime::createfromformat('Y-m-d h:m:s',$usrLeave->LeaveDateFrom)->format('d M Y').'</td>';
                                        echo '<td>'.datetime::createfromformat('Y-m-d h:m:s',$usrLeave->LeaveDateTo)->format('d M Y').'</td>';
                                        echo '<td>'.$usrLeave->TotalLeave.'</td>';
                                        
                                        $friendlyUrl = GetFriendlyUrl("/shell/leave-application/index.php?action=approval&id=".$usrLeave->Id);
                                        echo '<td><a href="'.$friendlyUrl.'"><button type="button" class="btn btn-default btn-sm">View</button></a></td>';
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