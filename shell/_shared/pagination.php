<?php 
        $pageIndex = GetPageIndex();
        $prevIndex = $pageIndex == 1 ? 1 : $pageIndex - 1;
        $nextIndex = $pageIndex + 1;
    ?>
    <nav>
        <ul class="pager">
            <?php 
                $qsArray["page"] = $prevIndex;
                echo '<li><a href="?'.BuildQueryString($qsArray).'">Previous</a></li>';
                
                $qsArray["page"] = 'page-index';
                echo '<li> Page : <input type="number" min="1" id="paginationTB" class="paginationTextBox" value="'.GetPageIndex().'" /> <a href="#" data-paginationhref="?'.BuildQueryString($qsArray).'" id="goButton" onclick="TriggerPagination(\'paginationTB\', $(this))" >Go</a></li>';
                
                $qsArray["page"] = $nextIndex;
                echo '<li><a href="?'.BuildQueryString($qsArray).'" >Next</a></li>';
            ?>
            
        </ul>
    </nav>