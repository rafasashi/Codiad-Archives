<!--
    Copyright (c) Codiad & Rafasashi, distributed
    as-is and without warranty under the MIT License. 
    See http://opensource.org/licenses/MIT for more information.
    This information must remain intact.
-->
<?php 

echo'<form>';

    echo'<label>Extract contents</label>';
	
    //echo'<p>Enter new name</p>';
    //echo'<input type="text" id="extract_name" value="'. $_GET['name']'.">';
	
    echo'<button onclick="codiad.Extract.extract(); return false;">Extract here</button>';
	
    echo'<button onclick="codiad.modal.unload(); return false;">Close</button>';
	
echo'</form>';

?>
