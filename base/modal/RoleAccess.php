<?php
	class RoleAccess extends DBModal{
		var $RoleId;
        var $RoleAccessAttributes;
        
        var $Role_META = '{"ReferenceBy":"RoleId","Ignore":"true"}';
        var $Role;
	}
?>