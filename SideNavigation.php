<?php 
    $currentPageName = pipeline\SiteContext::GetCurrentPage();
    if( $currentPageName != 'login.php' && $currentPageName != 'logout.php' ){
        ?>
            <div class="col-sm-3 col-md-2 sidebar">
                <?php 
                    $pageCtrl = new Controllers\PageController($dbConn);
                    $loginCtrl = new Controllers\LoginController(null);
                    $roleId = $loginCtrl->GetUserRoleId();
                    $pageIds = $loginCtrl->GetUserAccessPageIds();
                    $pageListing = $pageCtrl->GetUserAccessPages();
                    if( $pageListing != null && count($pageListing) > 0 ){
                        echo '<ul class="nav nav-sidebar">';
                        foreach( $pageListing as $pg ){
                            if( $pg->ShowInSideNavi == 1 && in_array($pg->Id, $pageIds)){
                                echo '<li><a href="'.pipeline\LinkManager::GetFriendlyUrl($pg->Path).'">'.$pg->PageName.'</a></li>';
                            }
                        }
                        echo '</ul>';
                    }
                ?>
        </div>
        <?php
    }
?>