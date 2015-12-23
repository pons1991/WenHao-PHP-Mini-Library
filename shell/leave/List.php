<p><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id=0">New Leave Type</a></button></p>

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
                    foreach( $leaveCtrl->GetLeaves() as $lv ){
                        echo '<tr>';
                        echo '<td>'.$lv->Id.'</td>';
                        echo '<td>'.$lv->LeaveName.'</td>';
                        echo '<td><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id='.$lv->Id.'">Edit</a></button></td>';
                        echo '</tr>';
                    }
                ?>
        </tbody>
    </table>
</div>