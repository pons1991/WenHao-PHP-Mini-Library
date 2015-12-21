<p><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id=0">New user</a></button></p>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Email</th>
                <th>Attribute</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    foreach( $userCtrl->GetUsers() as $usr ){
                        echo '<tr>';
                        echo '<td>'.$usr->Id.'</td>';
                        echo '<td>'.$usr->Email.'</td>';
                        echo '<td>'.$usr->CustomAttribute.'</td>';
                        echo '<td><button type="button" class="btn btn-default btn-sm"><a href="?action=edit&id='.$usr->Id.'">Edit</a></button></td>';
                        echo '</tr>';
                    }
                ?>
        </tbody>
    </table>
</div>