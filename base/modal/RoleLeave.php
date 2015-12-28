<?php
	class RoleLeave extends DBModal{
		var $RoleId;
        var $LeaveAttribute;
        
        var $Role_META = '{"ReferenceBy":"RoleId","Ignore":"true"}';
        var $Role;
	}
?>