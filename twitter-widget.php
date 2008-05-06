<?php
/*
Plugin Name: Twitter Widget
Plugin URI: http://seanys.com/2007/10/12/twitter-wordpress-widget/
Description: Adds a sidebar widget to display Twitter updates (uses <a href="http://twitter.com/badges/which_badge">Twitter''s ''badges''</a>)
Version: 1.1
Author: Sean Spalding
Author URI: http://seanys.com/
License: GPL

This software comes without any warranty, express or otherwise, and if it
breaks your blog or results in your cat being shaved, it's not my fault.

*/

function widget_Twidget_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_Twidget($args) {

		// "$args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys." - These are set up by the theme
		extract($args);

		// These are our own options
		$options = get_option('widget_Twidget');
		$badge = $options['badge'];  // Twitter badge type
		$account = $options['account'];  // Your Twitter account name
		$userid = $options['userid'];  // Your Twitter User Id
		$title = $options['title'];  // Title in sidebar for widget
		$height = $options['height'];  // Height of flash
		$width = $options['width'];  // Height of flash
		$show = $options['show'];  // # of Updates to show

        // Output
		echo $before_widget ;
		
		switch ($badge){
			// start display
			case 0:
				// javascript text
				echo '<div id="twitter_div">'
									.$before_title.$title.$after_title;
				echo '<ul id="twitter_update_list"></ul></div>
							<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>';
				echo '<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/'.$account.'.json?callback=twitterCallback2&amp;count='.$show.'"></script>';
				break;
			case 1:
				// flash, with friends
				echo '<div style="width:200px;text-align:center">
								<embed src="http://static.twitter.com/flash/twitter_timeline_badge.swf" 
											 flashvars="user_id='.$userid.'&color1=0xFFFFCE&color2=0xFCE7CC&textColor1=0x4A396D&textColor2=0xBA0909&backgroundColor=0x92E2E5&textSize=10" 
											 width="'.$width.'" height="'.$height.'" align="middle" 
											 quality="high" 
											 name="twitter_timeline_badge" 
											 type="application/x-shockwave-flash" 
											 allowScriptAccess="always" 
											 type="application/x-shockwave-flash" 
											 pluginspage="http://www.adobe.com/go/getflashplayer">
								</embed><br />
								<a style="font-size: 10px; color: #0xBA0909; text-decoration: none" href="http://static.twitter.com/"><img src="http://static.twitter.com/images/twitter_bubble_logo.gif" border="0" /></a>
							</div>';
				break;
			case 2:
				// flash, just me
				echo '<div style="width:176px;text-align:center">
								<embed src="http://twitter.com/flash/twitter_badge.swf"  
											 flashvars="color1=16594585&type=user&id='.$userid.'"  
											 quality="high" 
											 width="'.$width.'" height="'.$height.'" 
											 name="twitter_badge" 
											 align="middle" 
											 allowScriptAccess="always" 
											 wmode="transparent" 
											 type="application/x-shockwave-flash" 
											 pluginspage="http://www.macromedia.com/go/getflashplayer" /><br />
					      <a style="font-size: 10px; color: #FD3699; text-decoration: none" href="http://twitter.com/'.$account.'">follow '.$account.' at http://twitter.com</a>
							</div>';
				break;
		}

		// echo widget closing tag
		echo $after_widget;
	}

	// Settings form
	function widget_Twidget_control() {

		// Get options
		$options = get_option('widget_Twidget');
		// options exist? if not set defaults
		if ( !is_array($options) )
			$options = array('badge'=>'0', 'account'=>'seanys', 'userid'=>'9206062', 'title'=>'Twitter Updates', 'height'=>'400', 'width'=>'200', 'show'=>'5');

        // form posted?
		if ( $_POST['Twitter-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['badge'] = strip_tags(stripslashes($_POST['Twitter-badge']));
			$options['account'] = strip_tags(stripslashes($_POST['Twitter-account']));
			$options['userid'] = strip_tags(stripslashes($_POST['Twitter-userid']));
			$options['title'] = strip_tags(stripslashes($_POST['Twitter-title']));
			$options['height'] = strip_tags(stripslashes($_POST['Twitter-height']));
			$options['width'] = strip_tags(stripslashes($_POST['Twitter-width']));
			$options['show'] = strip_tags(stripslashes($_POST['Twitter-show']));
			update_option('widget_Twidget', $options);
		}

		// Get options for form fields to show
		$badge = htmlspecialchars($options['badge'], ENT_QUOTES);
		$account = htmlspecialchars($options['account'], ENT_QUOTES);
		$userid = htmlspecialchars($options['userid'], ENT_QUOTES);
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$height = htmlspecialchars($options['height'], ENT_QUOTES);
		$width = htmlspecialchars($options['width'], ENT_QUOTES);
		$show = htmlspecialchars($options['show'], ENT_QUOTES);
		
		// get ready to populate select list
		$js_select = $badge == '0' ? ' selected' : ''; 
		$fwf_select = $badge == '1' ? ' selected' : ''; 
		$fjm_select = $badge == '2' ? ' selected' : ''; 

		// The form fields
		echo '<p style="text-align:right;">
				<label for="Twitter-badge">' . __('Badge:') . '
				<select style="width: 200px;" id="Twitter-badge" name="Twitter-badge">
					<option value="0"'.$js_select.'>HTML/JavaScript</option>
					<option value="1"'.$fwf_select.'>Flash, with friends</option>
					<option value="2"'.$fjm_select.'>Flash, just me</option>
				</select>
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-account">' . __('Account:') . '
				<input style="width: 200px;" id="Twitter-account" name="Twitter-account" type="text" value="'.$account.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-userid">' . __('User Id:') . '
				<input style="width: 200px;" id="Twitter-userid" name="Twitter-userid" type="text" value="'.$userid.'" />
				</label><br />Find this number by mousing over the <br />RSS link on your Twitter page.</p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-title">' . __('Title:') . '
				<input style="width: 200px;" id="Twitter-title" name="Twitter-title" type="text" value="'.$title.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-height">' . __('Height:') . '
				<input style="width: 80px;" id="Twitter-height" name="Twitter-height" type="text" value="'.$height.'" />
				</label>
				<label for="Twitter-width">' . __('Width:') . '
				<input style="width: 80px;" id="Twitter-width" name="Twitter-width" type="text" value="'.$width.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-show">' . __('Show:') . '
				<input style="width: 200px;" id="Twitter-show" name="Twitter-show" type="text" value="'.$show.'" />
				</label></p>';
		echo '<input type="hidden" id="Twitter-submit" name="Twitter-submit" value="1" />';
	}


	// Register widget for use
	register_sidebar_widget(array('Twitter', 'widgets'), 'widget_Twidget');

	// Register settings for use, 300x200 pixel form
	register_widget_control(array('Twitter', 'widgets'), 'widget_Twidget_control', 300, 200);
}

// Run code and init
add_action('widgets_init', 'widget_Twidget_init');

?>
