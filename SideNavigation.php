<?php 
    if( GetCurrentPage() != 'login.php' ){
        ?>
            <div class="col-sm-3 col-md-2 sidebar">
                <?php 
                    $pageCtrl = new PageController($dbConn);
                    $pageListing = $pageCtrl->GetPages();
                    if( $pageListing != null && count($pageListing) > 0 ){
                        echo '<ul class="nav nav-sidebar">';
                        foreach( $pageListing as $pg ){
                            if( $pg->ShowInSideNavi == 1){
                                echo '<li><a href="#">'.$pg->PageName.'</a></li>';
                            }
                        }
                        echo '</ul>';
                    }
                    print_r($pageListing);
                ?>
                
                    <!--<li class="active"><a href="#">Overview</a></li>
                    <li><a href="#">Reports</a></li>
                    <li><a href="#">Analytics</a></li>
                    <li><a href="#">Export</a></li>-->
          
          <!--<ul class="nav nav-sidebar">
            <li><a href="">Nav item</a></li>
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
            <li><a href="">More navigation</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
          </ul>-->
        </div>
        <?php
    }
?>