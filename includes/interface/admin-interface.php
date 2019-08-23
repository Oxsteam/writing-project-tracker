<?php
namespace Writing_Project_Tracker\Admin_Interface;

add_action ('wp_ajax_save_log_callback', 'Writing_Project_Tracker\Admin_Interface\writing_project_tracker_save_log_callback' );
add_action ('wp_ajax_select_change_callback', 'Writing_Project_Tracker\Admin_Interface\writing_project_tracker_select_change_callback');
add_action ('wp_ajax_delete_log', 'Writing_Project_Tracker\Admin_Interface\writing_project_tracker_delete_log');
add_action ('wp_ajax_run_shortcode_generator', 'Writing_Project_Tracker\Admin_Interface\writing_project_tracker_run_shortcode_generator');



function writing_project_tracker_customfields_metabox() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	// Get the location data if its already been entered
	$active= '';
	$est_word_count = '';
	$est_comp_date = '';

	if (get_post_meta($post->ID, WRITING_PROJECT_TRACKER_ACTIVE, true) === 'true')
		$active = 'checked';

	$est_word_count = get_post_meta($post->ID, WRITING_PROJECT_TRACKER_EST_WORD_COUNT, true);
	$est_comp_date = get_post_meta($post->ID, WRITING_PROJECT_TRACKER_EST_COMP_DATE, true);
	?>

	<div class="container">
		<div class="row marginbottom-10">
			<div class="col-md-3">
				<input type="checkbox" id="check_active" name="check_active" <?php echo $active ?>/>
				<label class="checkbox-label">Active</label>
			</div>
		</div>
		<div class="row marginbottom-10">
			<div class="col-md-3">
				<label for="txtgoalcompletedate">Est. Completion Date</label>
				<input class="form-control"  type="date" id="txtgoalcompletedate" name="txtgoalcompletedate" value="<?php echo $est_comp_date?>" />
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<label for="txtestwordcount">Est. Word Count</label>
				<input class="form-control"  type="text" id="txtestwordcount" name="txtestwordcount" value="<?php echo $est_word_count?>" />
			</div>
		</div>
	</div>
<?php
}

function writing_project_tracker_add_write_track_metaboxes() {
	add_meta_box(
	'Writing_Project_Tracker\Admin_Interface\writing_project_tracker_customfields_metabox', // $id
		'Write Track Project Information', // $title
		'Writing_Project_Tracker\Admin_Interface\writing_project_tracker_customfields_metabox', // $callback
		'wtproject', // $screen
		'normal', // $context
		'high' // $priority
	);
}

add_action( 'add_meta_boxes', 'Writing_Project_Tracker\Admin_Interface\writing_project_tracker_add_write_track_metaboxes' );
add_action( 'admin_menu', 'Writing_Project_Tracker\Admin_Interface\writing_tracker_project_log_admin_page' );

function writing_tracker_project_log_admin_page() {
	add_submenu_page( 'edit.php?post_type=wtproject', 'Logs', 'Logs', 'manage_options', 'wtlogs', 'Writing_Project_Tracker\Admin_Interface\writing_project_tracker_logs_html');
}

function writing_project_tracker_logs_edit_fields_render()
{
	?>
	<div class="row">
		<div class="col-md-4">
			<label for="startDatePicker" class="paddingtop-5" >Start Time</label>
			<div class="input-group date" id="startDatePicker" >
				<input id="start-date-input" name="start-date-input" type="text" class="form-control " />
				<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>
		<div class="col-md-4">
			<label for="endDatePicker" class="paddingtop-5" >End Time</label>
			<div class="input-group date" id="endDatePicker" >
				<input id="end-date-input" name="end-date-input" type="text" class="form-control "  />
				<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</span>
			</div>
		</div>
		<div class="col-md-4" >
			<label class="paddingtop-5" for="wordCount">Word Count</label>
		  <input type="number" step="1" min="0" class="form-control word-count-input" id="wordCount" name="wordCount" />
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<label class="paddingtop-5" for="notes" >Notes</label>
		</div>
		<div class="col-md-12">
			<input type="text" class="form-control notes-input" id="notes"  name="notes" />
		</div>
	</div>
	<?php
}


function writing_project_tracker_shortcode_fields_render()
{
?>
	<div id="shortcodeSelections" class="shortcode-selections mt-5">
		<h3>Shortcode Generator</h3>
		<div class="row">
		<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="form-group">
							<label for="progressBarType">Chart Type</label>
							<select class="project-bar-type form-control" id="progressBarType" name="progressBarType" >
								<option value="none">None</option>
								<option value="circle">Circle</option>
								<option value="semicircle">Semi Circle</option>
								<option value="linear">Line</option>
							</select>
					</div>
					<div class="form-group">
							<label for="progressBarEasing">Easing</label>
							<select id="progressBarEasing" class="form-control progress-bar-easing" >
								<option value="bounce">Bounce</option>
								<option value="easeOut">Ease Out</option>
								<option value="easeInOut">Ease In Out</option>
								<option value="linear">Linear</option>
							</select>
					</div>
					<div class="form-group">
							<label for="widthInput">Width (% of container)</label>
							<input type="number" class="form-control width-input" id="widthInput" min=10 step="5" value=10 />
					</div>
					<div class="form-group">
							<label for="strokeInput">Stroke (pixels)</label>
							<input type="number" class="form-control stroke-input" id="strokeInput" min=5 step="5" value=5 />
					</div>
					<div class="form-group">
							<label for="colorGraph">Graph Color</label>
							<input type="text" id="colorGraph" value="#bada55" class="my-color-field form-control stroke-color"  />
					</div>
					<div class="form-group">
							<label for="colorFont">Font Color</label>
							<input type="text" id="colorFont" value="#bada55" class="my-color-field form-control font-color" />
					</div>
					<div class="form-group">
							<label for="colorTrail">Trail Color</label>
							<input type="text" id="colorTrail" value="#bada55" class="my-color-field form-control trail-color"  />
					</div>
					<div class="form-group">
							<label for="fontSizeInput">Font Size</label>
							<input type="number" id="fontSizeInput" class="form-control font-size-input" min=1 step=1 value=1 />
					</div>
					<div class="form-group">
							<label class="checkbox-label" for="animateCheck">Animate</label>
							<input type="checkbox" id="animateCheck"  class="animate-check"/>
					</div>
					<div class="form-group">
						<label for="durationInput">Animation Time In Seconds</label>
						<input type="number" id="durationInput" class="duration-input form-control	" min=0  step="1" />
					</div>
					<div class="row">
						<h4 style="display:none;">Information Display Options <em>Coming soon</em></h4>
					</div>
					<div class="row">
						<ul  class="shortcode-project-attribute" >
							<li><input type="checkbox" class="sc-title-check" />Title</li>
							<li><input type="checkbox" class="sc-description-check" />Description</li>
							<li><input type="checkbox" class="sc-target-count-check" />Target Word Count</li>
							<li><input type="checkbox" class="sc-target-due-date-check" />Target Due Date</li>
							<li><input type="checkbox" class="sc-image-check" />Image</li>
							<li><input type="checkbox" class="sc-total-word-count-check" />Total Word Count</li>
							<li><input type="checkbox" class="sc-total-hours-check" />Total Hours</li>
							<li><input type="checkbox" class="sc-avg-words-hour-check" />Avg Words/Hour</li>
							<li><input type="checkbox" class="sc-days-remaining-check" />Days Remaining</li>
							<li><input type="checkbox" class="sc-estimate-comp-date-check" />Est Completion Date</li>
							<li><input type="checkbox" class="sc-loop-check" />Loop Data Display</li>
						</ul>
					</div>
					<div class="form-group">
						<label for="freezeDateInput">Freeze Date</label>
						<div class="input-group date" id="freezeDate" >
							<input id="freezeDateInput" name="freezeDateInput" type="text" class="form-control freeze-date-input" />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<button id="btnCreateShortcode" name="btnCreateShortcode" type="button" class="btn btn-block btn-primary btn-shortcode  ">Create</button>
				<button id="btnCancelShortcode" name="btnCancelShortcode" type="button" class="btn btn-light	 btn-block btn-shortcode ">Cancel</button>
			</div>
			<div class="row">
				<input class="form-control shortcode-script" type="text" id="shortcodeScript" />
				<button id="btnCopyShortcode" name="btnCopyShortCode" type="button" class="copy-shortcode btn btn-success btn-block btn-shortcode">Copy ShortCode to Clipboard</button>
			</div>
		</div>
<?php
}

function writing_project_tracker_logs_html(){
	?>
	<form id="wptLogForm" method="post" role="form">
		<div class="row no-gutters">
			<div class="col-md-8 logger-main-container" id="wtpLoggerMain" name="wtLoggerMain">
				<h2>Write Track Logging</h2>
					<div class="form-group">
						<div class="row">
							<div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 marginbottom-10">
								<div class="form-group">
									<label class="paddingtop-5 control-label pull-left" for="selectWTPProject">Project</label>
									<select class="form-control" id="selectWTPProject" name="selectWTPProject">
										<option value="000" selected></option>
										<?php
											$args = array( 'post_type' => WRITING_PROJECT_TRACKER_POST_TYPE);
											$loop = new \WP_Query( $args );
											while ( $loop->have_posts() ) : $loop->the_post();
												if (get_post_meta(get_the_ID(), WRITING_PROJECT_TRACKER_ACTIVE, true) == "true"){
													$estWordCount = get_post_meta(get_the_ID(), WRITING_PROJECT_TRACKER_EST_WORD_COUNT);
													$estCompDate = get_post_meta(get_the_ID(), WRITING_PROJECT_TRACKER_EST_COMP_DATE);
													echo '<option value="' . get_the_ID() . '" data-estWordCount="' . $estWordCount[0] . '" data-estCompDate="' .$estCompDate[0] .'" >' . get_the_title( the_ID() ) . '</option>';
												}
											endwhile;
										?>
									</select>
								</div>
					</div>
					<div class="col-md-3 col-lg-3 col-xs-6 col-sm-3 justify-content-end align-self-center  ">
						<button id="btnAddLog" name="btnAddLog" type="button" class="btn btn-primary btn-block mt-2 ">Add Log</button>
					</div>
					<div class="col-md-3 col-lg-3 col-xs-6 col-sm-3 justify-content-end align-self-center">
						<button id="btnShowShortcode" name="btnShowShortcode" type="button" class=" btn btn-success btn-block mt-2">ShortCode</button>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="edit-control-group">
							<?php
								writing_project_tracker_logs_edit_fields_render();
							?>
							<button id="btnSaveLog" name="btnSaveLog" type="button" class="btn btn-primary pull-right margintop-5 marginright-10 btn-block">Save</button>
							<button id="btnCancelLog" name="btnCancelLog" type="button" class="btn btn-light pull-right margintop-5 btn-block">Cancel</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div id="shortcodecontrolgroup">
							<?php
								writing_project_tracker_shortcode_fields_render();
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 hidden mt-5" id="logTableContainer">
						<h3>Logs</h3>
						<div class="row pre-scrollable">
							<table id="logTable" name="logTable" class="table log-table"></table>
							<label class="no-rows hidden">There are no logs entered for this project</label>
						</div>
					</div>
				</div>
				</div>
			</div>

			<div class="col-md-4 col-lg-4 pull-right mt-5">
				<h2>Stats</h2>
				<br />
				<div class="form-group status-container">
					<label class="status-label status-label">Status: </label><br />
					<label class="est-target-date-label">Estimated Completion Date:</label><br />
					<label class="est-total-word-count-label">Estimated Word Count:</label>
					<hr />
					<label class="total-word-count-label">Total Word Count:</label><br />
					<label class="total-sessions-label">Total Sessions:</label><br />
					<label class="start-date-label">Date Started:</label><br />
					<label class="last-date-label">Date Last Worked:</label><br />
					<label class="words-per-hour-label">Words Per Hour:</label><br />
					<label class="words-per-session-label">Words Per Session:</label><br />
					<label class="words-per-day-label">Words Per Day (24 hours):</label><br />
					<label class="hours-per-session-label">Hours Per Session:</label><br />
					<label class="hours-per-day-label">Hours Per Day (24 hours):</label><br />
					<label class="remaining-hours-label">Est Remaining Hours:</label><br />
					<label class="remaining-sessions-label">Est Remaining Sessions:</label><br />
					<label class="est-comp-date-label">Est Completion Date:</label><br />
				</div>
				</div>

		</div>
	</form>
<?php
}


function writing_project_tracker_create_shortcode_attr($tag, $value)
{
	if (($tag != "") && (($value != "") && ($value != 'false')))
		return $tag . '="' . $value . '" ';
	else
		return "";
}

function writing_project_tracker_run_shortcode_generator()
{
	error_log(print_r($_POST, true));
	$id = $_POST['projectid'];
	$type			 = $_POST['type'];
	$title 		 = $_POST['title'];
	$chart_width  = $_POST['chartWidth'];
	$stroke_width = $_POST['sWidth'];
	$color			= $_POST['color'];
	$description	 = $_POST['description'];
	$est_word_count = $_POST['est_word_count'];
	$est_due_date	 = $_POST['est_due_date'];
	$image_url  = $_POST['image_url'];
	$total_word_count  = $_POST['total_word_count'];
	$total_hours = $_POST['total_hours'];
	$avg_words_per_hour = $_POST['avg_words_per_hour'];
	$target_date = $_POST['target_date'];
	$est_completion_date = $_POST['est_completion_date'];
	$lock_progress_bar = $_POST['lock_progress_bar'];
	$lock_date = $_POST['lock_date'];
	$font_size = $_POST['fontsize'];
	$font_color = $_POST['fontcolor'];
	$trail_color = $_POST['trailcolor'];
	$easing = $_POST['easing'];
	$animate = $_POST['animate'];
	$duration = $_POST['duration'];
	$shortString =  "[wtdisplayproject " . writing_project_tracker_create_shortcode_attr('projectid', $id);
	$shortString .= writing_project_tracker_create_shortcode_attr('type', $type);
	$shortString .= writing_project_tracker_create_shortcode_attr('title', $title);
	$shortString .= writing_project_tracker_create_shortcode_attr('color', $color);
	$shortString .= writing_project_tracker_create_shortcode_attr('strokeWidth', $stroke_width);
	$shortString .= writing_project_tracker_create_shortcode_attr('chartWidth', $chart_width);
	$shortString .= writing_project_tracker_create_shortcode_attr('description', $description);
	$shortString .= writing_project_tracker_create_shortcode_attr('est_word_count', $est_word_count);
	$shortString .= writing_project_tracker_create_shortcode_attr('est_due_date', $est_due_date);
	$shortString .= writing_project_tracker_create_shortcode_attr('image_url', $image_url);
	$shortString .= writing_project_tracker_create_shortcode_attr('total_word_count', $total_word_count);
	$shortString .= writing_project_tracker_create_shortcode_attr('total_hours', $total_hours);
	$shortString .= writing_project_tracker_create_shortcode_attr('avg_words_per_hour', $avg_words_per_hour);
	$shortString .= writing_project_tracker_create_shortcode_attr('target_date', $target_date);
	$shortString .= writing_project_tracker_create_shortcode_attr('est_completion_date', $est_completion_date);
	$shortString .= writing_project_tracker_create_shortcode_attr('lock_progress_bar', $lock_progress_bar);
	$shortString .= writing_project_tracker_create_shortcode_attr('lock_date', $lock_date);
	$shortString .= writing_project_tracker_create_shortcode_attr('font_size', $font_size);
	$shortString .= writing_project_tracker_create_shortcode_attr('font_color', $font_color);
	$shortString .= writing_project_tracker_create_shortcode_attr('easing', $easing);
	$shortString .= writing_project_tracker_create_shortcode_attr('trailcolor', $trail_color);
	$shortString .= writing_project_tracker_create_shortcode_attr('animate', $animate);
	$shortString .= writing_project_tracker_create_shortcode_attr('duration', $duration);
	$shortString .= "]";
	echo json_encode($shortString);
	die();
}

function writing_project_tracker_select_change_callback()
{
	$results = array();
	$send_results = false;

	$id = $_POST['postid'];
	if (is_numeric($id)){
	$all_logs = get_post_meta($id, LOG_META_KEY_VALUE);
	foreach($all_logs as $meta_key_post=>$meta_value_post){
			$log_items = get_post_meta($meta_value_post);
			if ($meta_value_post != "0"){
				$inner_result['logPostId'] = $meta_value_post;
				foreach($log_items as $meta_key=>$meta_value){
				  $inner_result[$meta_key] = $meta_value[0];
			}
			$results[] = $inner_result;
			$send_results = true;
		}
	}
}

	if ($send_results)
		echo json_encode($results);
	else
		echo json_encode(false);

	die();
}


function writing_project_tracker_delete_log()
{
	if (is_numeric($_POST['logid']))
	{
		$log_id = $_POST['logid'];
		$post_id = $_POST['postid'];
		$all_logs = get_post_meta($log_id);
		delete_post_meta($post_id, LOG_META_KEY_VALUE, $log_id);
		foreach($all_logs as $log){

			delete_post_meta($log_id, LOG_META_START_DATE);
			delete_post_meta($log_id, LOG_META_END_DATE);
			delete_post_meta($log_id, LOG_META_WORD_COUNT);
			delete_post_meta($log_id, LOG_META_NOTES);
		}
//need to delete the one with the postid.  was missing it.
  	wp_delete_post($log_id, true);
	}
}



function writing_project_tracker_save_log_callback() {
	if (is_numeric($_POST['postid']))
	{
		$post_id = $_POST['postid'];

		if (validateDate($_POST['startdate'], 'Y-m-d H:i'))
			$start_date = $_POST['startdate'];
		if (validateDate($_POST['enddate'], 'Y-m-d H:i'))
			$end_date = $_POST['enddate'];
		if (is_numeric($_POST['wordcount']))
			$word_count = $_POST['wordcount'];
		$notes = sanitize_text_field($_POST['notes']);

		$existing_log_id = sanitize_text_field($_POST['logrowid']);

	}

	if ($existing_log_id == -1 )
	{
		global $wpdb;
		$query = GET_LOG_BY_LOGID;
		$output = $wpdb->get_results($wpdb->prepare($query, $post_id, $start_date));

		if (empty($output))
		{
			$log_post_id = wp_insert_post(array(
				'post_status' => 'publish',
				'post_type' => LOG_META_KEY_VALUE,
				'post_title' => 'log',
				'post_content' => "log entry for project " . $post_id
			));
			add_post_meta($post_id, LOG_META_KEY_VALUE, $log_post_id);
			add_post_meta($log_post_id, LOG_META_START_DATE, $start_date);
			add_post_meta($log_post_id, LOG_META_END_DATE, $end_date);
			add_post_meta($log_post_id, LOG_META_WORD_COUNT, $word_count);
			add_post_meta($log_post_id, LOG_META_NOTES, $notes);
		}
		else
			echo json_encode($output);
	}
	else
	{
		update_post_meta($existing_log_id, LOG_META_START_DATE, $start_date);
		update_post_meta($existing_log_id, LOG_META_END_DATE, $end_date);
		update_post_meta($existing_log_id, LOG_META_WORD_COUNT, $word_count);
		update_post_meta($existing_log_id, LOG_META_NOTES, $notes);
	}
	die();
}
?>
