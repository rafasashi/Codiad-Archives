<?php
/*
 * Copyright (c) Codiad & Rafasashi, distributed
 * as-is and without warranty under the MIT License. 
 * See http://opensource.org/licenses/MIT for more information.
 * This information must remain intact.
 */
    error_reporting(0);

    require_once('../../common.php');
    checkSession();
    
    switch($_GET['action']) {
        
        case 'unzip':
		
            if (isset($_GET['path'])) {
                
				$source = getWorkspacePath($_GET['path']);
				
				$source_info=pathinfo($source);
                
				if(!isset($source['extension'])||empty($source['extension'])){
					
					echo '{"status":"error","message":"Not an archive"}';
				}
				else{
					
					$des = dirname($source);
					
					if($source['extension']=='zip') {
						
						if(class_exists('ZipArchive') && $zip = new ZipArchive) {
		
							if($res = $zip->open($source)){

								// extract it to the path we determined above
								if($zip->extractTo($des)){
									
									echo '{"status":"success","message":"File unziped"}';
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
						else {
							
							echo '{"status":"error","message":"ZipArchive extension missing"}';
						}
					}
					else
						
						echo '{"status":"error","message":"Looks like a .'.$source['extension'].'"}';
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
