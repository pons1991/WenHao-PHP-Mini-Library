<?php 
    $currentPageName = GetCurrentPage();
    if( $currentPageName != 'login.php' && $currentPageName != 'logout.php' ){
        ?>
            <div class="col-sm-3 col-md-2 sidebar">
                <?php 
                    $pageCtrl = new PageController($dbConn);
                    $pageListing = $pageCtrl->GetUserAccessPages();
                    if( $pageListing != null && count($pageListing) > 0 ){
                        echo '<ul class="nav nav-sidebar">';
                        foreach( $pageListing as $pg ){
                            if( $pg->ShowInSideNavi == 1){
                                if (strpos($pg->Path, $currentPageName) !== false) {
                                    echo '<li class="active"><a href="'.GetFriendlyUrl($pg->Path).'">'.$pg->PageName.'</a></li>';
                                }else{
                                    echo '<li><a href="'.GetFriendlyUrl($pg->Path).'">'.$pg->PageName.'</a></li>';
                                }
                                
                            }
                        }
                        echo '</ul>';
                    }
                ?>
        </div>
        <?php
    }
?>