<p><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id=0">New Prorate</a></button></p>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Year</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
                <?php
                    $proratedList = $leaveCtrl->GetProRatedLeaveList();
                    foreach( $proratedList as $pl ){
                        echo '<tr>';
                        echo '<td>'.$pl->AccessUser->Email.'</td>';
                        echo '<td>'.$pl->ProRatedYear.'</td>';
                        echo '<td><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id='.$pl->Id.'">View</a></button></td>';
                        echo '</tr>';
                    }
                ?>
        </tbody>
    </table>
</div>