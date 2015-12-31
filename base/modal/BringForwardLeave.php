<?php
	class BringForwardLeave extends DBModal{
		
		var $UserId;
        var $BringForwardFromYear;
        var $BringForwardAttributes;
        
        var $AccessUser_META = '{"ReferenceBy":"UserId","Ignore":"true"}';
        var $AccessUser;
		
	}
?>