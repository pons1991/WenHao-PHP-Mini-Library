<p><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id=0">New Role</a></button></p>

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
                        echo '<td><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id='.$roleLeave->Id.'">View</a></button></td>';
                        echo '</tr>';
                    }
                ?>
        </tbody>
    </table>
</div>