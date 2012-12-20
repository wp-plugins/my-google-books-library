<?php

/* 
Plugin Name: My Google Books Library
Plugin URI: http://hugo.activesquirrel.com/dev/my-google-books-library
Description: A simple plugin that displays any number of your Google Books bookshelves including custom bookshelves. This plugin also has a widget and [shortcode] functionality.
Version: 1.0
Author: Hugo Minnaar
Author URI: http://hugo.activesquirrel.com
License: GPL v2
	
Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 

	// Wordpress formalities here ...
	
	// Lets register things
	register_activation_hook(__FILE__, 'my_google_books_library_install');
	register_deactivation_hook(__FILE__, 'my_google_books_library_uninstall');
	add_action('admin_menu', 'my_google_books_library_admin_menu_create');
	add_action('widgets_init', create_function('', 'return register_widget("my_google_books_library_widget");')); // Register the widget

	// Prepare the array for our DB variables
	function my_google_books_library_install() {
		
		$plugin_options = array(
			'library_id' => '',
			'visibility_settings' => array(
				'show_powered_by' => false
			)
		);
		add_option('my_google_books_library_settings', $plugin_options);
	
	}

	function my_google_books_library_uninstall() {
		delete_option('my_google_books_library_settings');
	}

	// Create the admin menu
	function my_google_books_library_admin_menu_create() {
		add_options_page('My Google Books Library Settings', 'My Google Books Library', 'administrator', __FILE__, 'my_google_books_library_settings');
	}
	
	// Output this anywhere in the blog
	function my_google_books_library($shelf = '4', $max = '10') {
		$mgbl_settings = get_option('my_google_books_library_settings');
		$idNumber = ($mgbl_settings['library_id'])? $mgbl_settings['library_id']: '103176538541676992674';
			
		$title = shelfIdToName($idNumber, $shelf);
		?>
		<h2 class="widget-title"><?php echo $title; ?></h2>
        <?php
		$url = "https://www.googleapis.com/books/v1/users/".$idNumber."/bookshelves/".$shelf."/volumes?maxResults=" . $max;
		
		// Set up cURL
		$ch = curl_init();
		// Set the URL
		curl_setopt($ch, CURLOPT_URL, $url);
		// don't verify SSL certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// Return the contents of the response as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// Follow redirects
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		// Do the request
		$json = curl_exec($ch);
		curl_close($ch);
		
		$books = json_decode($json);
		$element_array = array();
		
		if(!empty($books->items)){
			foreach($books->items as $book){
				if(!empty($book)){
					
					if(!empty($book->volumeInfo->imageLinks->smallThumbnail)){
					$imageLink = $book->volumeInfo->imageLinks->smallThumbnail;
					}else{ $imageLink = "http://books.google.com/googlebooks/images/no_cover_thumb.gif";}
					
					if(!empty($book->volumeInfo->description)){
					$description = $book->volumeInfo->description;
					}else{ $description = "No description available"; }	
										
					$results[] = array('title' => $book->volumeInfo->title,
					'imageLink' => $imageLink,
					'infoLink' => $book->volumeInfo->infoLink,
					'authors' => $book->volumeInfo->authors[0],
					'description' => $description,
					);					
				}
			}
			
			//to-do add different templates for displaying books. e.g. list, grid, etc
			echo "<table>";
			foreach($results as $key => $value){
				echo "<tr><td style='vertical-align:middle;'>";
				echo "<a href='" . $value['infoLink'] . "' target='_blank'><img style='float:left;margin:0 10px 0 0;' src='" . $value['imageLink'] . "'/></a> ";
				echo "</td><td style='vertical-align:middle;'>"; //height:120px;
				echo "<p><b>" . $value['title'] . "</b><br>";
				echo "<i>by " . $value['authors'] . "</i><br>";
				echo $value['description'] . "</p>";
				echo "</td></tr>";
			}
			echo "</table>";
		}
		?>
		
		<?php
	

		if($mgbl_settings['visibility_settings']['show_powered_by']) {
				
?>
			<br /><br />Plugin by <a href="http://hugo.activesquirrel.com">Hugo</a>.<br />
            
<?php
	
		}
	}

	// shortcode for use in posts/pages [my_google_books_library shelf="4" max="50"]
	function my_google_books_library_shortcode($atts) {
		
		extract( shortcode_atts( array(
		'shelf' => '4',
		'max' => '10',
	), $atts ) );
		
		my_google_books_library($shelf, $max);
	}
	
	add_shortcode( 'my_google_books_library', 'my_google_books_library_shortcode' );
	
	// The plugin admin page
	function my_google_books_library_settings() {
		
		$mgbl_settings = get_option('my_google_books_library_settings');
		$message = '';

		if(isset($_POST['mgbl_id'])) {
			$message = 'Settings updated.';
			$id = html_entity_decode($_POST['mgbl_id']);
			// Get the show settings
			$show_powered_by = $_POST['mgbl_show_powered_by'];

			$mgbl_settings['visibility_settings']['show_powered_by'] = ($show_powered_by) ? true : false;
			
			$mgbl_settings['library_id'] = $id;
			update_option('my_google_books_library_settings', $mgbl_settings);
		}

		$mgbl_settings = get_option('my_google_books_library_settings');
		
?>

		<div id="icon-options-general" class="icon32"></div><h2>My Google Books Library Settings</h2>
<?php

		if(strlen($message) > 0) {
		
?>
			<div id="message" class="updated">
				<p><strong><?php echo $message; ?></strong></p>
			</div>
<?php
		}
?>
                <form method="post" action="">
				<table class="form-table">
					<tr>
						<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__).'book.png'; ?>" /></th>
						<td>
							<p>Thank you for using this plugin.</p> 
                            <p>In order to use this plugin you need to have a Google account and set up <a href="http://books.google.com">Google Books.</a>
                            <br>Used in collaboration with a mobile app like 
                            <a href="https://play.google.com/store/apps/details?id=org.zezi.gb&feature=search_result#?t=W251bGwsMSwyLDEsIm9yZy56ZXppLmdiIl0.">My Library</a>
                            you can just scan the barcode of a book<br>you finished reading and see how it appears on your personal blog under e.g. Books I've Read.</p>
						</td>
					</tr>		
					<tr>
						<th scope="row"><label for="mgbl_id">Your Google Books ID</label></th>
						<td>
							<input type="text" name="mgbl_id" value="<?php echo stripslashes(htmlentities($mgbl_settings['library_id'])); ?>" />
							<br />
            				<span class="description">You can find your Google Books user ID in the URL when you go to one of your shelves in Google Books.
						<br>The id is displayed after ?uid= in the URL. In the example below it is 104176338546676692271.
						<br>e.g. http://books.google.co.za/books?uid=<b>104176338546676692271</b>.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="mgbl_shelfID">Custom Google Bookshelf ID</label></th>
						<td>
							<p>You can find the ID of your custom bookshelf in the URL when you go to one of your shelves in Google Books.
						<br>The id is displayed after as_coll= in the URL. In the example below it is 1001.
						<br>e.g. http://books.google.co.za/books?uid=104176338546676692271&amp;as_coll=<b>1001</b>&amp;source=gbs_lp_bookshelf_list.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="mgbl_defaultShelfID">Default Google Bookshelf ID's</label></th>
						<td>
							<p>Reading Now: 3
							<br>To Read: 2
							<br>Have Read: 4
							<br>Favorites: 0
							</p>
							
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="mgbl_shortcode">Shortcode</label></th>
						<td>
							<input type="text" name="mgbl_shortcode" size="50" value="[my_google_books_library shelf=&quot;4&quot; max = &quot;5&quot;]" />
							<br />
            				<span class="description">Copy and paste this shortcode on any page or post where you want to display your list of books.
            				<br>Remember to change the shelf (shelf ID) and max (maximum number of books to display) to suit your needs.</span>
						</td>
					</tr>					
					<tr>
						<th scope="row"><label for="mgbl_show_powered_by">Show Plugin by Message</label></th>
						<td>
		  					<input type="checkbox" name="mgbl_show_powered_by" value="true" <?php if($mgbl_settings['visibility_settings']['show_powered_by'] == true) { ?>checked="checked"<?php } ?> />
                            <br />
                            <span class="description">Check to show 'Plugin by Hugo' in output (optional, if you decide to check it, thank you for your support).</span>
						</td>
					</tr>		
				</table>					
				<p><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Update options') ?>" /></p>
				</form>


<?php

	} //My Google Books Library settings
	
	
	// Here, the widget code begins
	class my_google_books_library_widget extends WP_Widget {
		
		function my_google_books_library_widget() {
			$widget_options = array(
			'classname' => 'my_google_books_library_widget'
		);
		parent::WP_Widget('my_google_books_library_widget', 'My Google Books Library Widget', $widget_options);
		}
		
			function widget($args, $instance){
		extract($args, EXTR_SKIP);
		$mgbl_settings = get_option('my_google_books_library_settings');
		$idNumber = ($mgbl_settings['library_id'])? $mgbl_settings['library_id']: '103176538541676992674';
		$shelf = ($instance['shelf'])? $instance['shelf']: '0';
		$customShelf = ($instance['customShelf'])? $instance['customShelf']: '2';
		if (!empty($customShelf) && $shelf == "1"){
			$shelf = $customShelf;
		}
		$shelfName = ($instance['shelfName'])? $instance['shelfName']: shelfIdToName($idNumber, $shelf);
		$title = ($instance['title'])? $instance['title']: $shelfName;
		$maxResults = ($instance['maxResults'])? $instance['maxResults']: '10';
		?>
		<aside id="myGoogleBooksLibraryWidget" class="widget widget_myGoogleBooksLibrary">
        <h3 class="widget-title"><?php echo $title; ?></h3>
        <?php
		$url = "https://www.googleapis.com/books/v1/users/".$idNumber."/bookshelves/".$shelf."/volumes?maxResults=" . $maxResults;
		
		// Set up cURL
		$ch = curl_init();
		// Set the URL
		curl_setopt($ch, CURLOPT_URL, $url);
		// don't verify SSL certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// Return the contents of the response as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// Follow redirects
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		// Do the request
		$json = curl_exec($ch);
		curl_close($ch);
		
		$books = json_decode($json);
		$element_array = array();
		
		if(!empty($books->items)){
			foreach($books->items as $book){
				if(!empty($book)){
					if(!empty($book->volumeInfo->imageLinks->thumbnail)){
						$imageLink = $book->volumeInfo->imageLinks->thumbnail;
						$results[] = array('title' => $book->volumeInfo->title,
						'imageLink' => $imageLink,
						'infoLink' => $book->volumeInfo->infoLink);
					}else{
						$imageLink = "http://books.google.com/googlebooks/images/no_cover_thumb.gif";
					}
				}
			}
			//echo "<div style='margin-left:5px;'";
			foreach($results as $key => $value){
				echo "<a href='" . $value['infoLink'] . "' target='_blank'><img align='middle' src='" . $value['imageLink'] . "'/></a> " . "<br/>";
			}
		}
		?>
		</aside>
		<?php
	}
	
	function form($instance){
		?>
		<label for="<?php echo $this->get_field_id('shelf'); ?>">
		Shelf:
        <br/>
		<select id="<?php echo $this->get_field_id('shelf'); ?>"
			name="<?php echo $this->get_field_name('shelf'); ?>">
            <option value="3" <?php if(esc_attr($instance['shelf']) == 3) echo "selected"; ?>>Reading Now</option>
            <option value="2" <?php if(esc_attr($instance['shelf']) == 2) echo "selected"; ?>>To Read</option>
            <option value="4" <?php if(esc_attr($instance['shelf']) == 4) echo "selected"; ?>>Have Read</option>
            <option value="0" <?php if(esc_attr($instance['shelf']) == 0) echo "selected"; ?>>Favorites</option>
            <option value="1" <?php if(esc_attr($instance['shelf']) == 1) echo "selected"; ?>>Custom Shelf</option>
        </select>
		</label>
        <br/><label for="<?php echo $this->get_field_id('customShelf'); ?>">
		Custom Shelf ID:
        <br/>
		<input id="<?php echo $this->get_field_id('customShelf'); ?>"
			name="<?php echo $this->get_field_name('customShelf'); ?>"
			value="<?php echo esc_attr($instance['customShelf']); ?>" />
		</label>
		<br />
		<label for="<?php echo $this->get_field_id('maxResults'); ?>">
		Max Number of Books:
        <br/>
		<input id="<?php echo $this->get_field_id('maxResults'); ?>"
			name="<?php echo $this->get_field_name('maxResults'); ?>"
			value="<?php echo esc_attr($instance['maxResults']); ?>" />
		</label>
		<?php
	}


}

function shelfIdToName($idNumber, $shelf){
	$url = "https://www.googleapis.com/books/v1/users/".$idNumber."/bookshelves/".$shelf;
	
	// Set up cURL
	$ch = curl_init();
	// Set the URL
	curl_setopt($ch, CURLOPT_URL, $url);
	// don't verify SSL certificate
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// Return the contents of the response as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// Follow redirects
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	// Do the request
	$json = curl_exec($ch);
	curl_close($ch);
	
	$bookshelf = json_decode($json);
	
	if(!empty($bookshelf)){
		return $bookshelf->title;
	}
}

?>
