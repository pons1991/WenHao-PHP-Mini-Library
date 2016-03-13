<?php 
    $currentPage = pipeline\SiteContext::GetCurrentPage();
    if( $currentPage != 'login.php' &&  $currentPage != 'logout.php'){
        ?>
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo pipeline\LinkManager::GetFriendlyUrl(""); ?>">Leave Application</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <?php 
                                $loginCtrl = new Controllers\LoginController(null);
                                echo '<li><img style="border-radius: 50%; margin-top: 5px; margin-bottom: 5px;" width="50" height="50" src="'.pipeline\LinkManager::GetFriendlyUrl($loginCtrl->GetUseProfileImagePath()).'" /></li>';
                                echo '<li><a href="#">Welcome back, '.$loginCtrl->GetUserName().'</a></li>';
                                echo '<li><a href="'.pipeline\LinkManager::GetFriendlyUrl("/logout.php").'">Logout</a></li>';
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        <?php
    }
?>