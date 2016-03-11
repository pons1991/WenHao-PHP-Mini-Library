<?php
    namespace Models\Database;
	class UserRole extends DBModal{
		var $RoleId;
		var $UserId;
        
        var $Role_META = '{"ReferenceBy":"RoleId","Ignore":"true"}';
        var $Role;
        var $AccessUser_META = '{"ReferenceBy":"UserId","Ignore":"true"}';
        var $AccessUser;
	}

?>