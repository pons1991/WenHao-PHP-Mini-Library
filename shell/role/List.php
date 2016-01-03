<p><a href="?action=edit&id=0"><button type="button" class="btn btn-default btn-sm">New Role</button></a></p>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Role</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
                <?php
                    $roleLeaveList = $roleCtrl->GetRoleLeaveList();
                    foreach( $roleLeaveList as $roleLeave ){
                        echo '<tr>';
                        echo '<td>'.$roleLeave->Role->RoleName.'</td>';
                        echo '<td><a href="?action=edit&id='.$roleLeave->Id.'"><button type="button" class="btn btn-default btn-sm">View</button></a></td>';
                        echo '</tr>';
                    }
                ?>
        </tbody>
    </table>
</div>