<?php
    namespace Models\Database;
	class OrgRel extends DBModal{
		
		var $SuperiorUserId;
        var $UserId;
		
        var $SuperiorUser_META = '{"ReferenceBy":"SuperiorUserId","Ignore":"true","table":"AccessUser"}';
        var $SuperiorUser;
        
        var $User_META = '{"ReferenceBy":"UserId","Ignore":"true","table":"AccessUser"}';
        var $User;
        
	}
?>