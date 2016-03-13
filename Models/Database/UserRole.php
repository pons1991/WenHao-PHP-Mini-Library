<?php
    namespace Models\Database;
	class UserRole extends DBModal{
		var $RoleId;
		var $UserId;
        
        var $Role_META = '{"ReferenceBy":"RoleId","Ignore":"true","QualifiedClassName":"Models\\\Database\\\Role"}';
        var $Role;
        var $AccessUser_META = '{"ReferenceBy":"UserId","Ignore":"true","QualifiedClassName":"Models\\\Database\\\AccessUser"}';
        var $AccessUser;
	}

?>