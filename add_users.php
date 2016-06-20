<?php 
//Antonio Dotson 2016
$title="add new user";
require_once("header.php");
?>
<script src="js-webshim/minified/extras/modernizr-custom.js"></script>
<script src="js-webshim/minified/polyfiller.js"></script>
  <script>        
            $(document).ready(function(){
    	     $("#econfirmPassword").blur(function(){
				var password = $("#mpassword").val();
				var confirmp = $("#econfirmPassword").val();
				if (password != confirmp) {
			   alert("Error passwords don't match");
				$("#mpassword").val(" ");
				$("#econfirmPassword").val(" ");
                   }
				});
			});
		</script>
        
  <script>
		$.webshims.setOptions('forms', { 
			customMessages: true,
			iVal: {fieldWrapper: '.form-element'}
		});
		
		$.webshims.setOptions('forms-ext', { 
			types: 'range date time number month color'
		});
		
		//load all defined
		//or load only a specific feature with $.webshims.polyfill('feature-name');
		$.webshims.polyfill();
		
		
		$(function(){
			var showHideFormsExt = function(){
				$('span.forms-ext-feature')[this.checked ? 'show' : 'hide']();
			};
			$('#show-forms-ext')
				.each(showHideFormsExt)
				.click(showHideFormsExt)
			;
			
		});
</script>
<section id="content">
<!-- Registration 1 -->
<div class="tray tray-center">

              <div class="admin-form theme-danger tab-pane mw1200" id="register1" role="tabpanel">

                <div class="panel panel-danger heading-border">

                  <div class="panel-heading">

                    <span class="panel-title">

                      <i class="fa fa-pencil-square"></i>Create Account

                    </span>

                  </div>

                  <!-- end .form-header section -->



                  <form method="post" action="add_users.php" id="form-register1">

                    <div class="panel-body p25">

                      <label for="names" class="field-label">Name</label>

                      <div class="section row">

                        <div class="col-md-6">

                          <label for="firstname" class="field prepend-icon">

                            <input type="text" required name="firstname" id="firstname" class="gui-input" placeholder="First name...">

                            <label for="firstname" class="field-icon">

                              <i class="fa fa-user"></i>

                            </label>

                          </label>

                        </div>

                        <!-- end section -->



                        <div class="col-md-6">

                          <label for="lastname" class="field prepend-icon">

                            <input type="text" required name="lastname" id="lastname" class="gui-input" placeholder="Last name...">

                            <label for="lastname" class="field-icon">

                              <i class="fa fa-user"></i>

                            </label>

                          </label>

                        </div>

                        <!-- end section -->



                      </div>

                      <!-- end section row section -->



                      <div class="section">

                        <label for="username" class="field-label">Choose your username</label>

                        <div class="smart-widget sm-right smr-170">

                          <label for="username" class="field prepend-icon">

                            <input type="text" required name="username" id="username" class="gui-input" placeholder="john-doe">

                            <label for="username" class="field-icon">

                              <i class="fa fa-user"></i>

                            </label>

                          </label>
                        </div>

                        <!-- end .smart-widget section -->

                      </div>

                      <!-- end section -->



                      <div class="section">

                        <label for="password" class="field-label">Create a password</label>

                        <label for="password" class="field prepend-icon">

                          <input type="password" required name="password" id="mpassword" class="gui-input">

                          <label for="password" class="field-icon">

                            <i class="fa fa-lock"></i>

                          </label>

                        </label>
                        

                      </div>

                      <!-- end section -->



                      <div class="section">

                        <label for="confirmPassword" class="field-label">Confirm your password</label>

                        <label for="confirmPassword" class="field prepend-icon">

                          <input type="password" required name="confirmPassword" id="econfirmPassword" class="gui-input">

                          <label for="confirmPassword" class="field-icon">

                            <i class="fa fa-unlock-alt"></i>

                          </label>

                        </label>
                         <div id="response"></div>
                      </div>

                      <!-- end section -->



                      
                      <!-- end .section row section --><!-- end section --><!-- end section -->



                      <div class="section">

                        <label for="email" class="field-label">Your email address</label>

                        <label for="email" class="field prepend-icon">

                          <input type="email" required name="email" id="email" class="gui-input" placeholder="example@domain.com...">

                          <label for="email" class="field-icon">

                            <i class="fa fa-envelope"></i>

                          </label>

                        </label>

                      </div>

                      <!-- end section -->



                      <div class="section">

                        <label for="mobile" class="field-label">level</label>

                        <label for="mobile" class="field prepend-icon">

                          <input type="number" required name="level" id="mobile" class="gui-input" placeholder="1 or 2">

                          <label for="mobile" class="field-icon">

                            <i class="fa fa-phone-square"></i>

                          </label>

                        </label>

                      </div>

                     



                    </div>

                    <!-- end .form-body section -->

                    <div class="panel-footer">

                      <input type="submit" name="submit" value="create" class="button btn-primary" />

                    </div>

                    <!-- end .form-footer section -->
<?php warning("level 1 have full access to the system, level 2 does not have access to mass mailing, notification, and ability to add or delete associate");?>
                  </form>

                </div>

<?php
$unq = array_key_value($_POST,'uni');
$submit =array_key_value($_POST,'submit');
if ($submit == 'create') {
$system_id = SYSTEM_ID;
$dh = new database;
$sql = $dh->query("select numberofusers from client where system_id = $system_id")->results();
$limit = $sql['numberofusers'];
$results = $dh->query("select count(uid) as members from client_auth_users where system_id = $system_id")->results();
$count = $results['members'];
	// the user has filled in this form and pressed the 'Sign!'
	// button - try to create an entry
if ($count >= $limit) {
print'<script>alert("Currently, additional users can not be added to your account, please call)</script>';
print"</div>";
require_once('footer.php');
exit;
}  
//cleanup_text removes all html tags and spaces
//array_key_value gets POST data even element has no value
//add_users funtion adds the data to the database
	$firstname = cleanup_text(array_key_value($_POST,'firstname'));
	$lastname =cleanup_text(array_key_value($_POST,'lastname'));
	$email = cleanup_text(array_key_value($_POST,'email'));
	$username = cleanup_text(array_key_value($_POST,'username'));
	$password = cleanup_text(array_key_value($_POST,'password'));
	$level = cleanup_text(array_key_value($_POST,'level'));
	$system_id = SYSTEM_ID;
	$password =sha1($password);
	
	add_users($firstname,$lastname,$email,$username,$password,$level,$system_id);
	
	}
print"<br /><br />";
?>
</div>
<?php require_once("footer.php"); ?>
