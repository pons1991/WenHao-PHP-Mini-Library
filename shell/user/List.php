<p>This is to list user</p>
<p>To create new user: <a href="?action=edit&id=0">New user</a></p>
<table border="1">
	<thead>
		<th>Id</th>
		<th>Email</th>
		<th>Attribute</th>
		<th>Is Active</th>
		<th>Edit</th>
	</thead>
	<tbody>
		<?php 
			foreach( $userCtrl->GetUsers() as $usr ){
				echo '<tr>';
				echo '<td>'.$usr->Id.'</td>';
				echo '<td>'.$usr->Email.'</td>';
				echo '<td>'.$usr->CustomAttribute.'</td>';
				echo '<td>'.$usr->IsActive.'</td>';
				echo '<td><a href="?action=edit&id='.$usr->Id.'">Edit</a></td>';
				echo '</tr>';
			}
		?>
	</tbody>	
</table>