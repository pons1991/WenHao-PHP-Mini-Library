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
                    foreach( $userCtrl->GetUsers(GetPageIndex(), GetPageSize()) as $usr ){
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
    
    <?php 
        $pageIndex = GetPageIndex();
        $prevIndex = $pageIndex == 1 ? 1 : $pageIndex - 1;
        $nextIndex = $pageIndex + 1;
    ?>
    <nav>
        <ul class="pager">
            <?php 
                $qsArray["page"] = $prevIndex;
                echo '<li><a href="'.BuildQueryString($qsArray).'">Previous</a></li>';
                
                $qsArray["page"] = 'page-index';
                echo '<li> Page : <input type="number" min="1" id="paginationTB" class="paginationTextBox" value="'.GetPageIndex().'" /> <a href="#" data-paginationhref="?'.BuildQueryString($qsArray).'" id="goButton" onclick="TriggerPagination(\'paginationTB\', $(this))" >Go</a></li>';
                
                $qsArray["page"] = $nextIndex;
                echo '<li><a href="'.BuildQueryString($qsArray).'" >Next</a></li>';
            ?>
            
        </ul>
    </nav>
</div>