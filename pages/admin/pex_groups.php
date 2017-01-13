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
				  <a class="nav-link active" href="<?php echo URL::build('/admin/pex_groups/'); ?>"><?php echo $pex_language->get('pex', 'pex_link_groups'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link labels" href="<?php echo URL::build('/admin/pex_user/'); ?>"><?php echo $pex_language->get('pex', 'pex_link_user'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link labels" href="<?php echo URL::build('/admin/pex_settings/'); ?>"><?php echo $pex_language->get('pex', 'pex_link_settings'); ?></a>
				</li>
			  </ul>
		      <hr />
			  <h3 style="display:inline;"><?php echo $pex_language->get('pex', 'pex_navlinktitle'); ?> | Groups</h3>
			  <span class="pull-right"><a href="<?php echo URL::build('/admin/pex_groups/', 'action=addGroup'); ?>" class="btn btn-primary"><?php echo $pex_language->get('pex', 'pex_group_add'); ?></a></span>
			  <br /><br />

			<?php
			
			$check_sql = $queries->orderAll('module_pex', 'ID');
			foreach($check_sql as $sql_check){
			  if($sql_check->pex_database_adress == NULL && $sql_check->pex_database_dbname == NULL) {
				  echo '<div class="alert alert-danger">Attention: Permissions Database Settings not set or wrong!</div>';
				  die();
			  } else {
				  $check_db = DB_Custom::getInstance($sql_check->pex_database_adress, $sql_check->pex_database_dbname, $sql_check->pex_database_username, $sql_check->pex_database_password);
				  $check_db->get('permissions_inheritance', 'system');
				  //die();
			  }
				  
			}
			
				if(!isset($_GET['action']) && !isset($_GET['pex_groups'])){
			
			?>
			  
			  <!-- Groups Listings -->
			  <div class="panel panel-default">
			    <div class="panel-heading">
				  <h2><?php echo $pex_language->get('pex', 'pex_groups_title'); ?></h2>
				</div>
				
			<?php
					$rank_level = 10000;
					// Select Pex Settings
					$pex_sql = $queries->orderAll('module_pex', 'ID');
					foreach($pex_sql as $pex_data){
						
						// Connect to the external Pex Database
						$db = DB_Custom::getInstance($pex_data->pex_database_adress, $pex_data->pex_database_dbname, $pex_data->pex_database_username, $pex_data->pex_database_password);

						// Select and Read External Database
						//$data = $db->get('permissions_entity', array('type', '=', 0));
						$data = $db->orderAll('permissions', 'value', 'DESC');
						$results = $data->results();
						foreach($results as $pex_data) {
							if(ctype_digit($pex_data->value) && strlen($pex_data->name)<="30" && $pex_data->name != "system") {
			
			?>
				<div class="panel-body">
				  <div class="row">
				    <div class="col-md-9">
						<span style="font-size:26px;"><a class="btn btn-primary" role="button" data-toggle="collapse" href="#group_<?php echo $pex_data->name; ?>" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-eye" aria-hidden="true"></i></a> <?php echo $pex_data->name; ?></span>
						<div class="collapse" id="group_<?php echo $pex_data->name; ?>" style="width: 700px">
						  <div class="table-responsive">
							<table class="table table-hover">
							  <thead>
							    <tr>
								  <th>#:</th>
							      <th>Permission:</th>
								  <th>World:</th>
								  <th>Wert:</th>
							    </tr>
							  </thead>
							  <tbody>
			<?php
							$n=1;
							
							// Read Permissions Groups on external Database
							$data2 = $db->get('permissions', array('name', '=', $pex_data->name));
							$results2 = $data2->results();
							// Post Ranklevel, Default, Prefix, Suffix
							foreach($results2 as $pex_group_info) {
								
								if($pex_group_perms->permission == "rank") {
									echo '<tr>';
									echo '<td>&nbsp;</td>';
									echo '<td>Rank:</td>';
									echo '<td>'.$pex_group_info->value.'</td>';
									echo '<td>&nbsp;</td>';
									echo '</tr>';
								} elseif($pex_group_info->permission == "default") {
									echo '<tr>';
									echo '<td>&nbsp;</td>';
									echo '<td>Default:</td>';
									echo '<td>'.$pex_group_info->value.'</td>';
									echo '<td>&nbsp;</td>';
									echo '</tr>';									
								} elseif($pex_group_info->permission == "prefix") {
									echo '<tr>';
									echo '<td>&nbsp;</td>';
									echo '<td>Prefix:</td>';
									echo '<td>'.$pex_group_info->value.'</td>';
									echo '<td>&nbsp;</td>';
									echo '</tr>';									
								} elseif($pex_group_info->permission == "suffix") {
									echo '<tr>';
									echo '<td>&nbsp;</td>';
									echo '<td>Suffix:</td>';
									echo '<td>'.$pex_group_info->value.'</td>';
									echo '<td>&nbsp;</td>';
									echo '</tr>';									
								}
							}
							
							$data3 = $db->get('permissions_inheritance', array('child', '=', $pex_data->name));
							$results3 = $data3->results();
							foreach($results3 as $pex_group_inheritance) {
								if($pex_group_inheritance->type == "0") {
									echo '<tr>';
									echo '<td>&nbsp;</td>';
									echo '<td>Inheritance:</td>';
									echo '<td>'.$pex_group_inheritance->parent.'</td>';
									echo '<td>&nbsp;</td>';
									echo '</tr>';
								}									
							}
							
							?>
							<tr>
							  <td colspan="4"><hr></td>
							</tr>
							<?php
							
							
							// Post Group Permissions for Quicklist
							foreach($results2 as $pex_group_perms) {
								
								// Proof of Special Information that not must display
								if($pex_group_perms->permission == "rank" || $pex_group_perms->permission == "default" || $pex_group_perms->permission == "prefix" || $pex_group_perms->permission == "suffix") {
								} else {
									echo '<tr>';
									echo '<td>'.$n.'</td>';
									echo '<td style="word-break:break-all;word-wrap:break-word">'.$pex_group_perms->permission.'</td>';
									echo '<td>'.$pex_group_perms->world.'</td>';
									echo '<td>'.$pex_group_perms->value.'</td>';
									echo '</tr>';
									$n++;
								}
							}
			?>
							  </tbody>
							</table>
						  </div>
					    </div>
					  </div>
					  <div class="col-md-3">
						<span class="pull-right">
						  <a href="<?php echo URL::build('/admin/pex_groups/', 'action=editGroup&groupid='.$pex_data->name);?>" class="btn btn-success btn-sm"><i class="fa fa-cog" aria-hidden="true"></i> Edit</a>
						  <a href="<?php echo URL::build('/admin/pex_groups/', 'action=delGroup&groupid='.$pex_data->name);?>" class="btn btn-warning btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a>
						</span>
					  </div>
				  </div>
				</div>
			    <hr>
			<?php
							}
						}
			?>
			  </div>			  
			  <!-- Group Listing End-->

			<?php

					}
				} elseif($_GET['action'] == "addGroup") {
					if(Input::exists()) {
						if(Token::check(Input::get('token'))) {
							
							//Validate the Input Group informations 
							$pex_validate = new Validate();
							$pex_validation = $pex_validate->check($_POST, array(
								'groupname' => array(
								'required' => true,
								'min' => 3,
								'max' => 20
								),
								'prefix' => array(
								'required' => true,
								'max' => 50 
								)							
							));	

							if($pex_validation->passed()){
								// Check & Add New Group to Database
								try {
									$data =	$queries->create('forums', array(
										'forum_title' => htmlspecialchars(Input::get('forumname')),
										'forum_description' => htmlspecialchars($description),
										'forum_order' => $last_forum_order + 1,
										'forum_type' => Input::get('forum_type')
									));
									
								} catch(Exception $e){
									$error = '<div class="alert alert-danger">Unable to Save PermissionsEx Group:<br><br>' . $e->getMessage() . '</div>';
								}
							} else {
								$error = '<div class="alert alert-danger">';
								foreach($pex_validation->errors() as $item) {
									$error .= $item.'<br>';
								}
								$error .= '</div>';
							}
						
						} else {
							// Invalid token
							Session::flash('module_pex', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');
						}
					}

				if(Session::exists('module_pex')){
					echo Session::flash('module_pex');
				}
				if(isset($info)) echo $info;
				if(isset($error)) echo $error;
			  
				$token = Token::generate();					
			?>
			  <form action="" method="post">
			  <div class="panel panel-default">
			    <div class="panel-heading">
				  <h2><?php echo $pex_language->get('pex', 'pex_group_add'); ?></h2>
				</div>
				<div class="panel-body">
				  <div class="row">
				    <div class="col-md-6">
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_adress'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Groupname:</span>
						<input type="text" class="form-control" id="groupname" name="groupname" placeholder="Groupname">
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_port'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Rank:</span>
						<input type="text" class="form-control" id="rank" name="rank" placeholder="Rank Level" maxlength="4">
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_username'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Prefix:</span>
						<input type="text" class="form-control" id="prefix" name="prefix" placeholder="Prefix">
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_password'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Suffix:</span>
						<input type="password" class="form-control" id="suffix" name="suffix" placeholder="Suffix">
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_password'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Parent Group:</span>
						<select class="form-control" id="InputType" name="parent">
							<option value="none">none</option>
			<?php
							$pex_sql = $queries->orderAll('module_pex', 'ID');
							foreach($pex_sql as $pex_data){
						
								// Connect to the external Pex Database
								$db = DB_Custom::getInstance($pex_data->pex_database_adress, $pex_data->pex_database_dbname, $pex_data->pex_database_username, $pex_data->pex_database_password);
							
								$parent_data = $db->orderAll('permissions', 'value', 'DESC');
								$parent = $parent_data->results();
								foreach($parent as $parents) {
									if(ctype_digit($parents->value) && strlen($parents->name)<="30" && $parents->name != "system" && $parents->type == "0") {
										echo '<option value="'.$parents->name.'">';
										echo $parents->name;
										echo '</option>';	
									}										
								}
							}

			?>			
						</select>
						<br>
						<a class="btn btn-info btn-sm" href="#" data-toggle="popover" data-content="<?php echo $pex_language->get('pex', 'pex_help_mysql_prefix'); ?>" data-html="true"><i class="fa fa-question-circle"></i></a> <span style="font-size:16px;">Default:</span>
						<select class="form-control" id="InputType" name="default">
							<option value="false">false</option>
							<option value="true">true</option>
						</select>
					</div>
				  </div>
				</div>
			  </div>
			  <input type="hidden" name="token" value="<?php echo $token; ?>">
			  <input type="submit" class="btn btn-primary" value="<-- <?php echo $language->get('general', 'cancel'); ?>" />
			  <span class="pull-right">
			    <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>" />
			  </div>
			  </form>
			<?php
				} elseif($_GET['action'] == "editGroup") {
					echo 'editGroup';
				} elseif($_GET['action'] == "delGroup") {
					
				}

			?>			

             </div>
		  </div>
		</div>
      </div>
    </div>
	<?php require('modules/Core/pages/admin/footer.php'); ?>

    <?php require('modules/Core/pages/admin/scripts.php'); ?>
	
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>
	
    <script type="text/javascript">
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	elems.forEach(function(html) {
		var switchery = new Switchery(html);
	});
	
  	function colourUpdate(that) {
    	var x = that.parentElement;
    	if(that.checked) {
    		x.className = "bg-success";
    	} else {
    		x.className = "bg-danger";
    	}
	}
	function toggle(group) {
		if(document.getElementById('Input-view-' + group).checked) {
			document.getElementById('Input-view-' + group).checked = false;
		} else {
			document.getElementById('Input-view-' + group).checked = true;
		}
		if(document.getElementById('Input-topic-' + group).checked) {
			document.getElementById('Input-topic-' + group).checked = false;
		} else {
			document.getElementById('Input-topic-' + group).checked = true;
		}
		if(document.getElementById('Input-post-' + group).checked) {
			document.getElementById('Input-post-' + group).checked = false;
		} else {
			document.getElementById('Input-post-' + group).checked = true;
		}
		if(document.getElementById('Input-view_others-' + group).checked) {
			document.getElementById('Input-view_others-' + group).checked = false;
		} else {
			document.getElementById('Input-view_others-' + group).checked = true;
		}
		if(document.getElementById('Input-moderate-' + group).checked) {
			document.getElementById('Input-moderate-' + group).checked = false;
		} else {
			document.getElementById('Input-moderate-' + group).checked = true;
		}

		colourUpdate(document.getElementById('Input-view-' + group));
		colourUpdate(document.getElementById('Input-topic-' + group));
		colourUpdate(document.getElementById('Input-post-' + group));
		colourUpdate(document.getElementById('Input-view_others-' + group));
		colourUpdate(document.getElementById('Input-moderate-' + group));
	}
	for(var g in groups) {
		colourUpdate(document.getElementById('Input-view-' + groups[g]));
		if(groups[g] != "0") {
			colourUpdate(document.getElementById('Input-topic-' + groups[g]));
			colourUpdate(document.getElementById('Input-post-' + groups[g]));
			colourUpdate(document.getElementById('Input-view_others-' + groups[g]));
			colourUpdate(document.getElementById('Input-moderate-' + groups[g]));
		}
	}
	
	// Toggle all columns in row
	function toggleAll(that){
		var first = (($(that).parents('tr').find(':checkbox').first().is(':checked') == true) ? false : true);
		$(that).parents('tr').find(':checkbox').each(function(){
			$(this).prop('checked', first);
			colourUpdate(this);
		});
	}
    </script>
  </body>
</html>