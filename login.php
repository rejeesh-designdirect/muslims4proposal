<?
session_start();
ob_start();
require_once("lib/ajcms.php");
require_once("lib/profile.php");
$cms = new CMS();
if($_POST)
{
	$_REQUEST["profile_username"] = strtolower($_REQUEST["profile_username"]);
	$cms->getdata 	= true;
	$cms->tableName = "muslim_profile";
	$cms->validatePhrases($_REQUEST["profile_username"],"null", "Please enter a valid username");
	//$cms->validatePhrases($_REQUEST["profile_password"],"password", "Please enter a valid password");
	if($cms->error=="")
	{		
			$pro = new Profile();
			$pro->username = $_REQUEST["profile_username"];
			$pro->password = $_REQUEST["profile_password"];			
			$pro->LogIn();
			
			if($pro->profile->profile_status!="")
			{
				$cms->fvals   		 = $_REQUEST["profile_username"];
				$cms->fkeys    = "profile_username";	
				$cms->fieldArray = array("profile_username","profile_id","profile_status","profile_about_status","profile_personal_status","profile_religion_status","profile_education_status","profile_contact_status","profile_gallery_status");
				$cms->manageCms();	
				
				switch($pro->profile->profile_status)
				{
					case "0":
						header("location:login_verify_message.php");
						exit;
					break;
					
					case "3":
						header("location:banned_message.php");
						exit;						
					break;	
					
					default:							
						$act = new CMS(); 
						$act->tableName = "muslim_profile"; 
						$act->edit 		= true; 
						$act->fvals   		= $cms->output["profile_id"];
						$act->fkeys   = "profile_id";	
						$_REQUEST["profile_last_login"] = date("Y-m-d H:i:s");
						$_REQUEST["profile_online"] 	= "1";						
						$act->fieldArray =  array("profile_last_login","profile_online");
						$act->manageCms();		
														
						$_SESSION["profile_status"] 	= $cms->output["profile_status"];
						$_SESSION["profile_username"] 	= $cms->output["profile_username"];		
						$_SESSION["profile_id"] 		= $cms->output["profile_id"];	
							
						$_SESSION["profile_stage"]["about"] 	= $cms->output["profile_about_status"];
						$_SESSION["profile_stage"]["personal"] 	= $cms->output["profile_personal_status"];
						$_SESSION["profile_stage"]["religion"] 	= $cms->output["profile_religion_status"];
						$_SESSION["profile_stage"]["education"] = $cms->output["profile_education_status"];
						$_SESSION["profile_stage"]["contact"] 	= $cms->output["profile_contact_status"];
						$_SESSION["profile_stage"]["gallery"] 	= $cms->output["profile_gallery_status"];	
						
						$per = new CMS();
						$per->getdata 	 = true;
						$per->fvals   		 = $_SESSION["profile_id"];
						$per->fkeys    = "profile_id";
						$per->condition  = "k1=v1";
						$per->tableName  = "muslim_personal_details";
						$per->manageCms();	
						if(!sizeof($per->output))
						{
							$_SESSION["complete_url"] = "personal-information.php?msg=Please fill these details.";
							header("location:personal-information.php?msg=Please fill these details.");
							exit;
						}
						unset($per);
						
						$abt = new CMS();
						$abt->getdata 	 = true;
						$abt->fvals   		 = $_SESSION["profile_id"];
						$abt->fkeys    = "profile_id";
						$abt->condition  = "k1=v1";
						$abt->tableName  = "muslim_about_me";
						$abt->manageCms();	
						
						if(!sizeof($abt->output))
						{
							$_SESSION["complete_url"] = "about-you.php?msg=Please fill these details.";							
							header("location:about-you.php?msg=Please fill these details.");
							exit;
						}
						unset($abt);
						
						$abt1 = new CMS();
						$abt1->getdata 	 = true;
						$abt1->fvals   		 = $_SESSION["profile_id"];
						$abt1->fkeys    = "profile_id";
						$abt1->condition  = "k1=v1";
						$abt1->tableName  = "muslim_questionnaire";
						$abt1->manageCms();	
						
						if(!sizeof($abt1->output))
						{
							$_SESSION["complete_url"] = "questionnaire.php?msg=Please fill these details.";							
							header("location:questionnaire.php?msg=Please fill these details.");
							exit;
						}
						unset($abt);
						
						$edu = new CMS();
						$edu->getdata 	 = true;
						$edu->fvals   		 = $_SESSION["profile_id"];
						$edu->fkeys    = "profile_id";
						$edu->condition  = "k1=v1";
						$edu->tableName  = "muslim_education";
						$edu->manageCms();	
						
						/*if(!sizeof($edu->output))
						{
							$_SESSION["complete_url"] = "profile-update-education.php?msg=Please fill these details.";								
							header("location:profile-update-education.php?msg=Please fill these details.");
							exit;
						}*/
						unset($edu);
						
						$rel = new CMS();
						$rel->getdata 	 = true;
						$rel->fvals   		 = $_SESSION["profile_id"];
						$rel->fkeys    = "profile_id";
						$rel->condition  = "k1=v1";
						$rel->tableName  = "muslim_religion";
						$rel->manageCms();	
						
						/*if(!sizeof($rel->output))
						{
							$_SESSION["complete_url"] = "profile-update-religion.php?msg=Please fill these details.";
							header("location:profile-update-religion.php?msg=Please fill these details.");
							exit;
						}*/
						unset($rel);						
						
						$cnt = new CMS();
						$cnt->getdata 	 = true;
						$cnt->fvals   		 = $_SESSION["profile_id"];
						$cnt->fkeys    = "profile_id";
						$cnt->condition  = "k1=v1";
						$cnt->tableName  = "muslim_contact_info";
						$cnt->manageCms();	
						
						/*if(!sizeof($cnt->output))
						{
							$_SESSION["complete_url"] = "profile-update-contact-info.php?msg=Please fill these details.";
							header("location:profile-update-contact-info.php?msg=Please fill these details.");
							exit;
						}*/
						unset($cnt);
						
						$_SESSION["complete_url"] = "";
						header("location:myprofile.php");					
					break;				
				}
								
			}
			else
			{
				$cms->error.= "<li>Invalid username or password. Please Try again</li>";	
			}
	
	}
		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Musilm-Praposel</title>
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/custom-form-elements.js"></script>
</head>

<body>
<div class="layout">

	<div class="center-layout">
    
    	<?php include('include/header.php') ?>
        
        <div class="white-strip float-l">
        
<div class="white-caption float-l"> UK’s first muslim matrimony website with an online search feature and offline marriage agency.</div>
          <?php include('include/menu.php'); ?>
        
        </div>
        

        <div class="middle-container float-l">
        
        	<div class="registration">
            
            	  <div class="reg-head float-l">
                    <h4>Login</h4>
                    
                  </div>
                
                <div class="login_con">
                   <div class="login_inner">
                      <?
                $cms->showMsg(($_REQUEST["msg"]!="")?$_REQUEST["msg"]:"");
                ?>
                     <form id="form1" name="form1" method="post" action="">
                      <p>User Name *</p>
                       <input type="text" name="profile_username" value="<?=$_REQUEST['profile_username']?>" class="login_text_field" autocomplete="off" />
                        <p>Password *</p>
                       <input type="password" name="profile_password" class="login_text_field" autocomplete="off" />
                       <input type="submit" class="login_button" value="LOGIN" />
					    <p><a href="forgot-password.php">Forgot Password ?</a> / <a href="registration.php">New User ? Click here to Register</a></p>
                     </form>
                    </div>
                </div>
                
             
        
        </div>
        </div>

<?php include('include/footer.php'); ?>
        
        
    
    

</div>
</body>
</html>
