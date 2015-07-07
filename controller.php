<?php
/*
 * Copyright (c) Codiad, Rafasashi & beli3ver, distributed
 * as-is and without warranty under the MIT License. 
 * See http://opensource.org/licenses/MIT for more information.
 * This information must remain intact.
 */
    error_reporting(0);

    require_once('../../common.php');
    checkSession();
    
    //need for rar filelist check
    $error = false;
    
    switch($_GET['action']) {
        
        case 'extract':
		
			if(isset($_GET['path'])){
			
				$source = getWorkspacePath($_GET['path']);
				
				$source_info=pathinfo($source);

				if(!isset($source_info['extension'])||empty($source_info['extension'])){
					
					echo '{"status":"error","message":"Not an archive"}';
				}
				else{
					
					$des = dirname($source);
					
					if($source_info['extension']=='zip') {
						
						if(class_exists('ZipArchive') && $zip = new ZipArchive) {
		
							if($res = $zip->open($source)){

								// extract it to the path we determined above
								if($zip->extractTo($des)){
									
									echo '{"status":"success","message":"File extracted"}';
								}
								else{
									
									echo '{"status":"error","message":"Failed to extract contents"}';
								}
								
								$zip->close();
							  
							} 
							else {
								
								echo '{"status":"error","message":"Could not open zip archive"}';
							}
						}
					}
					elseif($source_info['extension']=='tar') {
						
						if(class_exists('PharData') && $tar = new PharData($source)) {

							if($tar->extractTo($des)){
								
								echo '{"status":"success","message":"File extracted"}';
							}
							else{
								
								echo '{"status":"error","message":"Failed to extract contents"}';
							}
							
						}
						else {
							
							echo '{"status":"error","message":"PharData extension missing or cloud not open tar archive"}';
						}
					}
					elseif($source_info['extension']=='rar') {
						
						if(class_exists('rar_open') && $rar = new rar_open) {
		
							if($res = $rar->open($source)){
							
								$entries = rar_list($res);
								try {
									foreach ($entries as $entry) {
									    $entry->extract($des);
									}
								} catch (Exception $e) {
								    $error = true;
								}
								
								// extract it to the path we determined above
								if($error === false){
									
									echo '{"status":"success","message":"File extracted"}';
								}
								else{
									
									echo '{"status":"error","message":"Failed to extract contents"}';
								}
								
								$rar->close();
							  
							} 
							else {
								
								echo '{"status":"error","message":"Could not open rar archive"}';
							}
						}
						else {
							
							echo '{"status":"error","message":"Cloud not open rar archive"}';
						}
					}
					else {
						
						echo '{"status":"error","message":"Looks like a .'.$source_info['extension'].'"}';
					}
				}
			} 
			else {
				
                echo '{"status":"error","message":"Missing Parameter"}';
            }
            break;
        
        default:
            echo '{"status":"error","message":"No Type"}';
            break;
    }
    
    
    function getWorkspacePath($path) {
		
		//Security check
		if (!Common::checkPath($path)) {
			die('{"status":"error","message":"Invalid path"}');
		}
        if (strpos($path, "/") === 0) {
            //Unix absolute path
            return $path;
        }
        if (strpos($path, ":/") !== false) {
            //Windows absolute path
            return $path;
        }
        if (strpos($path, ":\\") !== false) {
            //Windows absolute path
            return $path;
        }
        return WORKSPACE . "/" . $path;
    }
?>
