<?php
    namespace Models\Database;
	class RoleLeave extends DBModal{
		var $RoleId;
        var $LeaveAttribute;
        
        var $Role_META = '{"ReferenceBy":"RoleId","Ignore":"true", "QualifiedClassName":"Models\Database\Role"}';
        var $Role;
	}
?>