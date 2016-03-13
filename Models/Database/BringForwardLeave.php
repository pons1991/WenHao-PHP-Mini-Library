<?php
    namespace Models\Database;
	class BringForwardLeave extends DBModal{
		
		var $UserId;
        var $BringForwardFromYear;
        var $BringForwardAttributes;
        
        var $AccessUser_META = '{"ReferenceBy":"UserId","Ignore":"true","QualifiedClassName":"Models\\\Database\\\AccessUser"}';
        var $AccessUser;
		
	}
?>