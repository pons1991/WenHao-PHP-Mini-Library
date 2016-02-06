<p><a href="?action=edit&id=0"><button type="button" class="btn btn-default btn-sm">New User</button></a></p>

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
                    foreach( $userCtrl->GetUsers(GetPageSize()) as $usr ){
                        echo '<tr>';
                        echo '<td>'.$usr->Id.'</td>';
                        echo '<td>'.$usr->Email.'</td>';
                        echo '<td>'.$usr->CustomAttribute.'</td>';
                        echo '<td><a href="?action=edit&id='.$usr->Id.'"><button type="button" class="btn btn-default btn-sm">Edit</button></a></td>';
                        echo '</tr>';
                    }
                ?>
        </tbody>
    </table>
    <nav>
        <ul class="pager">
            <li><a href="#" class="btn-primary">Previous</a></li>
            <li> Page : <input type="number" min="1" class="paginationTextBox" /> <a href="#">Go</a></li>
            <li><a href="#" class="btn-primary">Next</a></li>
        </ul>
    </nav>
</div>