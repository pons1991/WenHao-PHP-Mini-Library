<?php
    namespace Models\Database;
	class LeaveAccess extends DBModal{
		var $RoleId;
        var $LeaveAccessAttributes;
        
        var $Role_META = '{"ReferenceBy":"RoleId","Ignore":"true"}';
        var $Role;
	}
?>