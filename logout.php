<?php 
	include "Base.php";
	EnableError();
	
	ClearSession();
?>

<?php include_once "header.php"; ?>

<div class="container">
        <h2 class="form-signin-heading">Logout..</h2>
        <?php RedirectExternal('http://bakhacheluxuries.com/'); ?>
</div>
		
<?php include_once "footer.php"; ?>