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
			  
			  <!-- Groups Listings -->
			  <div class="panel panel-default">
			    <div class="panel-heading">
				  <h2><?php echo $pex_language->get('pex', 'pex_groups_title'); ?></h2>
				</div>
				
				<div class="panel-body">
				  <div class="row">
				    <div class="col-md-9">
					
					
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
								echo $pex_data->name.'<br>';
							}
						}
					}
			?>			
					
					
					</div>
				  </div>
				</div>
				
				

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