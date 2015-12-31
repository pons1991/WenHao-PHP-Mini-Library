<p><a href="?action=edit&id=0"><button type="button" class="btn btn-default btn-sm">Apply Leave</button></a></p>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Leave Type</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    foreach( $leaveCtrl->GetLeaveTypes() as $lv ){
                        echo '<tr>';
                        echo '<td>'.$lv->Id.'</td>';
                        echo '<td>'.$lv->LeaveName.'</td>';
                        echo '<td><a href="?action=edit&id='.$lv->Id.'"><button type="button" class="btn btn-default btn-sm">Edit</button></a></td>';
                        echo '</tr>';
                    }
                ?>
        </tbody>
    </table>
</div>