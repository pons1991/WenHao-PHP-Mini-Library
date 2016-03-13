<?php
    namespace Models\Database;
	class RoleAccess extends DBModal{
		var $RoleId;
        var $RoleAccessAttributes;
        
        var $Role_META = '{"ReferenceBy":"RoleId","Ignore":"true","QualifiedClassName":"Models\\\Database\\\Role"}';
        var $Role;
	}
?>