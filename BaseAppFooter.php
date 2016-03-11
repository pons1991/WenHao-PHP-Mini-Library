
</div>
    </div>
   <!-- boostrap placeholder - end -->
   
   <!-- Processing / Loading GIF -->
   <div class="modal fade" id="processingGif" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
       <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="<?php echo $GLOBALS["DOMAIN_NAME"]; ?>/Themes/img/loading.gif" class="img-responsive" />
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    if( $dbConn != null ){
        $dbConn->CloseConnection();
    }
?>