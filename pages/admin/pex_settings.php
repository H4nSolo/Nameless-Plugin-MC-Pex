<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Forum module - admin forum page
 */

// Can the user view the AdminCP?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	} else {
		// Check the user has re-authenticated
		if(!$user->isAdmLoggedIn()){
			// They haven't, do so now
			Redirect::to(URL::build('/admin/auth'));
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
 
$page = 'admin';
$admin_page = 'pex';

?>

<html lang="en">
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	
	<?php 
	$title = $language->get('admin', 'admin_cp');
	require('core/templates/admin_header.php'); 
	?>
  
	<!-- Custom style -->
	<style>
	textarea {
		resize: none;
	}
	</style>
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
    <link href="core/assets/plugins/switchery/switchery.min.css" rel="stylesheet">
  </head>

  <body>
    <div class="container">	
	  <?php require('modules/Core/pages/admin/navbar.php'); ?>
	  <div class="row">
		<div class="col-md-3">
		  <?php require('modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="card">
		    <div class="card-block">
			  <ul class="nav nav-pills">
				<li class="nav-item">
				  <a class="nav-link labels" href="<?php echo URL::build('/admin/pex'); ?>"><?php echo $pex_language->get('pex', 'pex_link_home'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link labels" href="<?php echo URL::build('/admin/pex_groups/'); ?>"><?php echo $pex_language->get('pex', 'pex_link_groups'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link labels" href="<?php echo URL::build('/admin/pex_user/'); ?>"><?php echo $pex_language->get('pex', 'pex_link_user'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link active" href="<?php echo URL::build('/admin/pex_settings/'); ?>"><?php echo $pex_language->get('pex', 'pex_link_settings'); ?></a>
				</li>
			  </ul>
		      <hr />

			  
			  <?php
			  
			  if(Input::exists()) {
				if(Token::check(Input::get('token'))) {
					// Get reactions value

					$pex_validate = new Validate();
					$pex_validation = $pex_validate->check($_POST, array(
						'pex_host' => array(
					    'required' => true,
						'min' => 6,
						'max' => 15
						),
						'pex_username' => array(
						'required' => true,
						'max' => 255 
						),
						'pex_password' => array(
						'required' => true,
						'min' => 2,
						'max' => 64							
						),
						'pex_dbname' => array(
						'required' => true,
						'max' => 255							
						)							
					)); 

					
					if(Input::get('websend_enable') == "1") {
						$ws_validate = new Validate();
						$ws_validation = $ws_validate->check($_POST, array(
							'websend_host' => array(
							'required' => true,
							'min' => 9,
							'max' => 15														
							),
							'websend_password' => array(
							'required' => true,
							'min' => 5,
							'max' => 64							
							)
						));
					}
						
						
					if($pex_validation->passed()){
					// Check & Update Pex Settings to Database
						try {
							$queries->update('module_pex', '1', array(
//							    'id' => '1',
								'pex_database_adress' => htmlspecialchars(Input::get('pex_host')),
								'pex_database_port' => htmlspecialchars(Input::get('pex_port')),
								'pex_database_username' => htmlspecialchars(Input::get('pex_username')),
								'pex_database_password' => htmlspecialchars(Input::get('pex_password')),
								'pex_database_prefix' => htmlspecialchars(Input::get('pex_prefix')),
								'pex_database_dbname' => htmlspecialchars(Input::get('pex_dbname'))
							));

							
 							if(Input::get('websend_enable') == "1") {
								$queries->update('module_pex', '1', array(
									'pex_websend_enable' => htmlspecialchars(Input::get('websend_enable')),
									'pex_websend_ip' => htmlspecialchars(Input::get('websend_host')),
									'pex_websend_port' => htmlspecialchars(Input::get('websend_port')),
									'pex_websend_password' => htmlspecialchars(Input::get('websend_password'))
								));
							} else {
								$queries->update('module_pex', '1', array(
									'pex_websend_enable' => '0',
									'pex_websend_ip' => 'NULL',
									'pex_websend_port' => '0',
									'pex_websend_password' => 'NULL'
								));
							}
											
											
						} catch(Exception $e){
							$error = '<div class="alert alert-danger">Unable to Save PermissionsEx Module Settings: ' . $e->getMessage() . '</div>';
						}
					} else {
						$error = '<div class="alert alert-danger">';
						foreach($pex_validation->errors() as $item) {
							$error .= 'Check your Data Inputs! ['.$item.']';
						}
					$error .= '</div>';
					}
						
						
				} else {
					// Invalid token
					Session::flash('module_pex', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');
					//Redirect::to(URL::build('/admin/pex_settings'));
					die();
				}
			  } 
			  
			  
			  if(Session::exists('module_pex')){
			    echo Session::flash('module_pex');
			  }
			  if(isset($info)) echo $info;
			  if(isset($error)) echo $error;
			  
			  $token = Token::generate();
			  
			  $pex_settings = $queries->orderAll('module_pex', 'ID');
			  foreach($pex_settings as $pex_cfg){
				  //echo $pex_cfg->pex_websend_password;
				  
			  ?>
			  
			  
			  <!-- //Settings -->
			  <form action="" method="post">
			  <div class="panel panel-default">
			    <div class="panel-heading">
				  <h2>PermissionsEx <?php echo $language->get('admin', 'settings'); ?></h2>
				</div>
				<div class="panel-body">
				  <div class="row">
				    <div class="col-md-6">
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_adress'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Database Host:</span>
						<input type="text" class="form-control" id="pex_adress" name="pex_host" <?php if ($pex_cfg->pex_database_adress == "" || $pex_cfg->pex_database_adress == "NULL") { echo "placeholder=\"".$pex_language->get('pex', 'adress')."\"";} else { echo "value=\"".$pex_cfg->pex_database_adress."\" placeholder=\"".$pex_language->get('pex', 'adress')."\""; } ?>>
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_port'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Database Port:</span>
						<input type="text" class="form-control" id="pex_port" name="pex_port" <?php if ($pex_cfg->pex_database_port == "0" || $pex_cfg->pex_database_port == "NULL") { echo "placeholder=\"".$pex_language->get('pex', 'port')."\"";} else { echo "value=\"".$pex_cfg->pex_database_port."\" placeholder=\"".$pex_language->get('pex', 'port')."\""; } ?> maxlength="5">
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_username'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Database Username:</span>
						<input type="text" class="form-control" id="pex_username" name="pex_username" <?php if ($pex_cfg->pex_database_username == "0" || $pex_cfg->pex_database_username == "NULL") { echo "placeholder=\"".$pex_language->get('pex', 'username')."\"";} else { echo "value=\"".$pex_cfg->pex_database_username."\" placeholder=\"".$pex_language->get('pex', 'username')."\""; } ?>>
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_password'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Database Password:</span>
						<input type="password" class="form-control" id="pex_password" name="pex_password" <?php if ($pex_cfg->pex_database_password == "0" || $pex_cfg->pex_database_password == "NULL") { echo "placeholder=\"".$pex_language->get('pex', 'password')."\"";} else { echo "placeholder=\"HIDDEN\""; } ?>>
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_prefix'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Database Prefix:</span>
						<input type="text" class="form-control" id="pex_prefix" name="pex_prefix" <?php if ($pex_cfg->pex_database_prefix == "0" || $pex_cfg->pex_database_prefix == "NULL") { echo "placeholder=\"".$pex_language->get('pex', 'prefix')."\"";} else { echo "value=\"".$pex_cfg->pex_database_prefix."\" placeholder=\"".$pex_language->get('pex', 'prefix')."\""; } ?>>
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_dbname'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Database Name:</span>
						<input type="text" class="form-control" id="pex_databasename" name="pex_dbname" <?php if ($pex_cfg->pex_database_dbname == "0" || $pex_cfg->pex_database_dbname == "NULL") { echo "placeholder=\"".$pex_language->get('pex', 'databasename')."\"";} else { echo "value=\"".$pex_cfg->pex_database_dbname."\" placeholder=\"".$pex_language->get('pex', 'databasename')."\""; } ?>>
					</div>
				  </div>
				</div>
			  </div>
			  
			  <div class="panel panel-default">
			    <div class="panel-heading">
				  <h2>Websend <?php echo $language->get('admin', 'settings'); ?></h2>
				</div>
				<div class="panel-body">
				  <div class="row">
				    <div class="col-md-6">
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_websend_enable'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Websend Enable:</span>
		                <span class="pull-right">
		                  <input type="checkbox" name="websend_enable" id="integrated" class="js-switch" value="1"<?php if ($pex_cfg->pex_websend_enable == "0") { echo "";} else { echo " checked";} ?>>
	                    </span>
						<br>
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_websend_adress'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Websend Adress:</span>
						<input type="text" class="form-control" id="pex_websend_ip" name="websend_host" <?php if ($pex_cfg->pex_websend_ip == "0" || $pex_cfg->pex_websend_ip == "NULL") { echo "placeholder=\"".$pex_language->get('pex', 'adress')."\"";} else { echo "value=\"".$pex_cfg->pex_websend_ip."\" placeholder=\"".$pex_language->get('pex', 'adress')."\""; } ?>>
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_websend_port'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Websend Port:</span>
						<input type="text" class="form-control" id="pex_websend_port" name="websend_port" <?php if ($pex_cfg->pex_websend_port == "0" || $pex_cfg->pex_websend_port == "NULL") { echo "placeholder=\"".$pex_language->get('pex', 'port')."\"";} else { echo "value=\"".$pex_cfg->pex_websend_port."\" placeholder=\"".$pex_language->get('pex', 'port')."\""; } ?> maxlength="5">
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_websend_password'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Websend Password:</span>
						<input type="password" class="form-control" id="pex_websend_password" name="websend_password" <?php if ($pex_cfg->pex_websend_password == "0" || $pex_cfg->pex_websend_password == "NULL") { echo "placeholder=\"".$pex_language->get('pex', 'password')."\"";} else { echo "value=\"".$pex_cfg->pex_websend_password."\" placeholder=\"".$pex_language->get('pex', 'password')."\""; } ?>>
					</div>
				  </div>
				</div>
			  </div>
			  <input type="hidden" name="token" value="<?php echo $token; ?>">
			  <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>" />
			  </form>
		      <?php
			  }
			  ?>
			  

				
			</div>
		  </div>
		</div>
      </div>
    </div>
	<script src="/core/assets/plugins/switchery/switchery.min.js"></script>
	<script>
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

	elems.forEach(function(html) {
	  var switchery = new Switchery(html, {size: 'small'});
	});
	
	</script>
	<?php require('modules/Core/pages/admin/footer.php'); ?>

    <?php require('modules/Core/pages/admin/scripts.php'); ?>
  </body>
</html>