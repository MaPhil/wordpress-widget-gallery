<div class="wgp-container">
	<div class="wgp-header">
		<h1>
			Widget Galleries Plugin

		</h1>
		<button id="create-new-gallery" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
			create new gallery
		</button>
	</div>
	<hr>
	<script type="text/javascript">var galleries = {};</script>
	<?php
	global $wpdb;
	$gallery_table = $wpdb->prefix . "wgp_gallery";		
	$gallery_table = $wpdb->prefix . "wgp_gallery";		
	$galleries = $wpdb->get_results( "SELECT * FROM $gallery_table" );
	foreach ($galleries as $value){

		echo '<script type="text/javascript">galleries['.$value->id.'] = [];</script>';
		echo '<div id="gallery_'. $value->id. '" class="wgp-gallery-holder"><div class="wgp-gallery-head"><input id="gallery_name_'.$value->id.'" name="gallery_name" value="'.$value->name.'"/><button action="'.esc_html( admin_url( 'admin-post.php' ) ).'" id="gal_'. $value->id. '" class="update_name_of_gallery">update</button>';
		echo '<span id="del_'.$value->id.'" action="'.esc_html( admin_url( 'admin-post.php' ) ).'" class="delete-gallery dashicons dashicons-trash"></span>';
		echo '<hr/></div>';

		echo '<div class="gallery-image-holder">';


		 $image_entry_table = $wpdb->prefix . "wgp_image_entry"; 		
		 $image_entries = $wpdb->get_results( "SELECT * FROM $image_entry_table Where gallery_id=$value->id" );
		 foreach($image_entries as $image){
		 	echo '<div class="wgp-gallery-image-holder"><span id="del_'.$image->id.'" action="'.esc_html( admin_url( 'admin-post.php' ) ).'" style="    background-color: #f1f1f1;z-index: 30;;border-bottom-left-radius: 5px;" class="dashicons dashicons-trash delete-image"></span><span id="upd_'.$image->id.'" action="'.esc_html( admin_url( 'admin-post.php' ) ).'" style="    background-color: #f1f1f1;z-index: 30;;border-bottom-right-radius: 5px;" class="dashicons dashicons-edit update-image"></span><div id="i_'.$image->id.'&g_'.$value->id.'" class="wgp-gallery-image" style="background-image:url(\''.$image->path.'\')""></div>';
		 	echo '</div>';
			echo '<script type="text/javascript">galleries['.$value->id.']['.$image->id.'] = {id:"'.$image->id.'",path:"'.$image->path.'",caption:"'.$image->caption.'",sub_caption:"'.$image->sub_caption.'",url:"'.$image->url.'"};</script>';

		 }
		 echo '<div id="new_'.$value->id.'" action="'.esc_html( admin_url( 'admin-post.php' ) ).'" class="new-gallery-image"><span class="dashicons dashicons-plus"></span></div>';

		echo '</div>';

		echo '</div>';
	}
	
/*


;
		 	echo '<input name="caption" value="'.$image->caption.'">';
		 	echo '<input name="caption" value="'.$image->sub_caption.'">';
		 	echo '<input name="caption" value="'.$image->url.'">';
		 	echo '<input type="submit" value="Submit form"></form>
*/
	?>


	<div id="wgp-modal" class="wgp-modal">

		<div class="wgp-modal-content">
			<div class="wgp-close-section">
				<span class="wgp-close">&times;</span>
			</div>
			<div class="wgp-gallery-caption-holder">
				<hr>
				<form id="change_image_entry" action="<?php echo esc_html( admin_url( 'admin-post.php' ) );?>">
					<label>Caption</label>
					<textarea name="change_image_entry_caption"></textarea>
					<label>Sub Caption</label>
					<textarea name="change_image_entry_sub_caption"></textarea>
					<label>URL</label>
					<input type="text" name="change_image_entry_url">
					<div class="wgp-submit">
						<input type="submit" value="change settings">	
					</div>				
				</form>
		</div>

	</div>
</div>


