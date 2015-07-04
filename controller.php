<?php //é recu_23323f23d9c0320b3e75868c5f918854
 	
	/*
 * Copyright (c) Codiad & Rafasashi, distributed
 * as-is and without warranty under the MIT License. 
 * See http://opensource.org/licenses/MIT for more information.
 * This information must remain intact.
 */	
	error_reporting(0);	
	
	
	//--------------------collect variables--------------------
	
	$variables=get_defined_vars();
	unset($variables['variables'],$variables['int_key'],$variables['mirror_path']);
	
	//--------------------merge variables-------------------------
	
	foreach($variables as $name => $value){
		modules::$variables['ide:editor'][$name]=$value;
	}
	
	//--------------------include script--------------------
	
	integrate_module($framework,'ide', IDE.'editor/'.'common.php');
	
	//--------------------restore variables--------------------
	
	if(isset(modules::$variables['ide:editor'])){
	
		foreach(modules::$variables['ide:editor'] as $___name => $___val){
	
			$$___name=$___val;
			unset($___name,$___val);
	
		}
	
	}
	
	checkSession();	
	
	switch($_GET['action']){
		
		
		
		
		case 'extract':		
		
		if(isset($_GET['path'])){
			
			
			
			
			$source=getWorkspacePath($_GET['path']);			
			
			$source_info=pathinfo($source);			
			
			if(!isset($source_info['extension'])||empty($source_info['extension'])){
				
				
				
				
				echo '{"status":"error","message":"Not an archive"}';				
			}
			
			else{
				
				
				
				
				$des=dirname($source);				
				
				if($source_info['extension']=='zip'){
					
					
					
					
					if(class_exists('ZipArchive')&&$zip=new ZipArchive){
						
						
						
						
						if($res=$zip->open($source)){
							
							
							
							
							// extract it to the path we determined above							
							if($zip->extractTo($des)){
								
								
								
								
								echo '{"status":"success","message":"File extracted"}';								
							}
							
							else{
								
								
								
								
								echo '{"status":"error","message":"Failed to extract contents"}';								
							}
							
							
							$zip->close();							
							
						}
						
						else{
							
							
							
							
							echo '{"status":"error","message":"Could not open zip archive"}';							
						}
						
					}
					
					else{
						
						
						
						
						echo '{"status":"error","message":"ZipArchive extension missing"}';						
					}
					
				}
				
				else{
					
					
					
					
					echo '{"status":"error","message":"Looks like a .'.$source_info['extension'].'"}';					
				}
				
			}
			
		}
		
		else{
			
			
			
			
			echo '{"status":"error","message":"Missing Parameter"}';			
		}
		
		break;		
		
		default:		
		echo '{"status":"error","message":"No Type"}';		
		break;		
	}
	
	
	
	function getWorkspacePath($path){
		
		
		
		
		//Security check		
		if(!Common::checkPath($path)){
			
			
			
			module_exit('{"status":"error","message":"Invalid path"}');			
		}
		
		if(strpos($path,"/")===0){
			
			
			
			//Unix absolute path			
			return $path;			
		}
		
		if(strpos($path,":/")!==false){
			
			
			
			//Windows absolute path			
			return $path;			
		}
		
		if(strpos($path,":\\")!==false){
			
			
			
			//Windows absolute path			
			return $path;			
		}
		
		return modules::$constants['ide:editor']['WORKSPACE']."/".$path;		
	}
	
	 ?>
