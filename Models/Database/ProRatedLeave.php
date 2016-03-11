<?php
    namespace Models\Database;
	class ProRatedLeave extends DBModal{
		
		var $UserId;
        var $ProRatedYear;
        var $ProRatedAttributes;
        
        var $AccessUser_META = '{"ReferenceBy":"UserId","Ignore":"true"}';
        var $AccessUser;
		
	}
?>