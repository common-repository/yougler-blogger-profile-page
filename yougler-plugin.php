<?php
/* 
Plugin Name: Yougler Blogger Profile Page
Plugin URI: http://www.yougler.com/wordpress-plugins/yougler-blogger-profile-plugin.php
Version: v1.01
Author: <a href="http://www.yougler.com/pete">Pete Wingard</a>
Description: A plugin to allow bloggers to leave contact information on their blogs without using scripts or workarounds like john (at) doe.com.

This plugin is a modification of the Plugin "Devlounge Plugin Series" created by Ronald Huereca at http://www.devlounge.net. Please visit his site to support his great work and knowledge.

Copyright 2007  Ronald Huereca 
Copyright 2008  Pete Wingard  (contact me at http://www.yougler.com/pete)

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/
if (!class_exists("DevloungePluginSeries")) {
	class DevloungePluginSeries {
		var $adminOptionsName = "DevloungePluginSeriesAdminOptions";
		function DevloungePluginSeries() { //constructor
			
		}
		function init() {
			$this->getAdminOptions();
		}
		//Returns an array of admin options
		function getAdminOptions() {
			$devloungeAdminOptions = array('show_header' => 'true',
				'add_content' => 'true', 
				'comment_author' => 'true', 
				'content' => '');
			$devOptions = get_option($this->adminOptionsName);
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option)
					$devloungeAdminOptions[$key] = $option;
			}				
			update_option($this->adminOptionsName, $devloungeAdminOptions);
			return $devloungeAdminOptions;
		}
		
		function addHeaderCode() {
			$devOptions = $this->getAdminOptions();
			if ($devOptions['show_header'] == "false") { return; }
			?>
<!-- Yougler Blogger Profile Page -->
			<?php
		
		}
		function addContent($content = '') {
			$devOptions = $this->getAdminOptions();
			if ($devOptions['add_content'] == "true") {
				$youglerURL=get_the_author_url();
				$content .="<br /><br />_______________<br />".$devOptions['content']."<br />Author's Yougler  Profile is at &nbsp;<a href=\"".$youglerURL."\">".the_author()."</a>.";
			}
			return $content;
		}
		function authorUpperCase($author = '') {
			$devOptions = $this->getAdminOptions();
			if ($devOptions['comment_author'] == "true") {
				$author = strtoupper($author);
			}
			return $author;
		}
		//Prints out the admin page
		function printAdminPage() {
					$devOptions = $this->getAdminOptions();
										
					if (isset($_POST['update_devloungePluginSeriesSettings'])) { 
						if (isset($_POST['devloungeHeader'])) {
							$devOptions['show_header'] = $_POST['devloungeHeader'];
						}	
						if (isset($_POST['devloungeAddContent'])) {
							$devOptions['add_content'] = $_POST['devloungeAddContent'];
						}	
						if (isset($_POST['devloungeAuthor'])) {
							$devOptions['comment_author'] = $_POST['devloungeAuthor'];
						}	
						if (isset($_POST['devloungeContent'])) {
							$devOptions['content'] = apply_filters('content_save_pre', $_POST['devloungeContent']);
						}
						update_option($this->adminOptionsName, $devOptions);
						
						?>
<div class="updated"><p><strong><?php _e("Settings Updated.", "DevloungePluginSeries");?></strong></p></div>
					<?php
					} ?>
<div class=wrap>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<h2>Yougler Blogger Profile</h2>
<h3>Signature to Add to the End of a Post</h3>
<textarea name="devloungeContent" style="width: 80%; height: 100px;"><?php _e(apply_filters('format_to_edit',$devOptions['content']), 'DevloungePluginSeries') ?></textarea>
<h3>Allow Yougler Comment Code in the Header?</h3>
<p>Selecting "No" will disable the comment code inserted in the header.</p>
<p><label for="devloungeHeader_yes"><input type="radio" id="devloungeHeader_yes" name="devloungeHeader" value="true" <?php if ($devOptions['show_header'] == "true") { _e('checked="checked"', "DevloungePluginSeries"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="devloungeHeader_no"><input type="radio" id="devloungeHeader_no" name="devloungeHeader" value="false" <?php if ($devOptions['show_header'] == "false") { _e('checked="checked"', "DevloungePluginSeries"); }?>/> No</label></p>

<h3>Allow Signature and Yougler Blogger Profile Page Link to be Added to the End of a Post?</h3>
<p>Selecting "No" will disable the this from being added into the end of a post.</p>
<p><label for="devloungeAddContent_yes"><input type="radio" id="devloungeAddContent_yes" name="devloungeAddContent" value="true" <?php if ($devOptions['add_content'] == "true") { _e('checked="checked"', "DevloungePluginSeries"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="devloungeAddContent_no"><input type="radio" id="devloungeAddContent_no" name="devloungeAddContent" value="false" <?php if ($devOptions['add_content'] == "false") { _e('checked="checked"', "DevloungePluginSeries"); }?>/> No</label></p>



<div class="submit">
<input type="submit" name="update_devloungePluginSeriesSettings" value="<?php _e('Update Settings', 'DevloungePluginSeries') ?>" /></div>
</form>
 </div>
					<?php
				}//End function printAdminPage()
	
	}

} //End Class DevloungePluginSeries

if (class_exists("DevloungePluginSeries")) {
	$dl_pluginSeries = new DevloungePluginSeries();
}

//Initialize the admin panel
if (!function_exists("DevloungePluginSeries_ap")) {
	function DevloungePluginSeries_ap() {
		global $dl_pluginSeries;
		if (!isset($dl_pluginSeries)) {
			return;
		}
		if (function_exists('add_options_page')) {
	add_options_page('Devlounge Plugin Series', 'Yougler Blogger Profile', 9, basename(__FILE__), array(&$dl_pluginSeries, 'printAdminPage'));
		}
	}	
}

//Actions and Filters	
if (isset($dl_pluginSeries)) {
	//Actions
	add_action('admin_menu', 'DevloungePluginSeries_ap');
	add_action('wp_head', array(&$dl_pluginSeries, 'addHeaderCode'), 1);
	add_action('activate_devlounge-plugin-series/devlounge-plugin-series.php',  array(&$dl_pluginSeries, 'init'));
	//Filters
	add_filter('the_content', array(&$dl_pluginSeries, 'addContent'),1); 
}

?>