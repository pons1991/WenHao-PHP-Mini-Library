<?php 
    $isEditing = false;
    $leaveList = null;
    
    $leaveTypeList = null;
    $pageList = $pageCtrl->GetPages();
    
    if (isset($_GET["searchButton"])){
        $isEditing = true;
        
        if( is_numeric($_GET["year"]) == true ){
            $leaveList = $leaveCtrl->GetLeaveByUserIdAndYear($_GET["email"], $_GET["year"]);
        }else{
            $leaveList = $leaveCtrl->GetLeaveByUserId($_GET["email"]);
        }
    }
?>
<form method="GET">
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="email">Email</label></div>
            <div class="col-sm-5">
                <select id="email" name="email" class="form-control">
                    <?php 
                        foreach( $userCtrl->GetUsers($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]) as $usr ){
                            if( $isEditing && $usr->Id == $_GET["email"] ){
                                echo '<option value="'.$usr->Id.'" selected>'.$usr->Email.'</option>';
                            }else{
                                echo '<option value="'.$usr->Id.'">'.$usr->Email.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="year">Year</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<input type="text" id="year" name="year" class="form-control" value="'.$_GET["year"].'" />';
                    }else{
                        echo '<input type="text" id="year" name="year" class="form-control" />';
                    }
                ?>
                
            </div>
        </div>
        
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <button type="submit" class="btn btn-default" name="searchButton" id="searchButton" value="Search">Search</button>
            </div>
        </div>
    </div>
</form>

<?php 
    if( $isEditing ){
        ?>
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
                                    
                                    echo '<td><a href="'.GetFriendlyUrl("/shell/leave-application/index.php?action=edit&id=".$usrLeave->Id).'"><button type="button" class="btn btn-default btn-sm">View</button></a></td>';
                                    echo '</tr>';
                                }
                            ?>
                    </tbody>
                </table>
            </div>
        <?php
    }
?>
