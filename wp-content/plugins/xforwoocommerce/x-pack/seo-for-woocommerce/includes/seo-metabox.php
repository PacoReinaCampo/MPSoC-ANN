<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_SEO_Metabox {

		public static function add_metabox() {
			add_meta_box( 'autopilot-seo', 'Autopilot SEO', array( 'XforWC_SEO_Metabox', 'metabox' ) );
		}

		public static function metabox() {
			$post_type = get_post_type( get_the_ID() );
			$post_type = in_array( $post_type, array( 'post', 'page', 'product' ) ) ? $post_type : 'post';
?>
			<div class="seo-metabox-wrapper">
				<input type="hidden" name="autopilot-seo_nonce" value="<?php echo wp_create_nonce( 'autopilot-seo' ); ?>" />

				<h3><?php esc_html_e( 'Keywords', 'xforwoocommerce' ); ?></h3>

				<textarea name="autopilot-keywords" class="seo-keywords"><?php echo esc_html( get_post_meta( get_the_ID(), '_autopilot_seo_keywords', true ) ); ?></textarea>

				<p><?php esc_html_e( 'Enter keywords separated by comma', 'xforwoocommerce' ); ?></p>

				<hr />

				<h3><?php esc_html_e( 'Title format', 'xforwoocommerce' ); ?></h3>

				<textarea name="autopilot-seo_title" class="seo-terms seo-type-<?php echo esc_attr( $post_type ); // OK ?>"><?php echo esc_html( get_post_meta( get_the_ID(), '_autopilot_seo_title', true ) ); ?></textarea>

				<p><?php esc_html_e( 'Enter title format that will appear in search results', 'xforwoocommerce' ); ?></p>

				<hr />

				<h3><?php esc_html_e( 'Description format', 'xforwoocommerce' ); ?></h3>

				<textarea name="autopilot-seo_desc" class="seo-terms seo-type-<?php esc_attr( $post_type ); // OK ?>"><?php echo esc_html( get_post_meta( get_the_ID(), '_autopilot_seo_desc', true ) ); ?></textarea>

				<p><?php esc_html_e( 'Enter description format that will appear in search results', 'xforwoocommerce' ); ?></p>

				<hr />

				<h3><?php esc_html_e( 'Facebook image URL', 'xforwoocommerce' ); ?></h3>

				<input type="text" name="autopilot-seo_facebook_image" value="<?php echo esc_html( get_post_meta( get_the_ID(), '_autopilot_seo_facebook_image', true ) ); ?>" />

				<p><?php esc_html_e( 'Enter Facebook image URL of image to appear instead of the featured image (1200x628px is best for Facebook)', 'xforwoocommerce' ); ?></p>

				<hr />

				<h3><?php esc_html_e( 'Twitter image URL', 'xforwoocommerce' ); ?></h3>

				<input type="text" name="autopilot-seo_twitter_image" value="<?php echo esc_html( get_post_meta( get_the_ID(), '_autopilot_seo_twitter_image', true ) ); ?>" />

				<p><?php esc_html_e( 'Enter Twitter image URL of image to appear instead of the featured image (1024x512px is best for Twitter)', 'xforwoocommerce' ); ?></p>

			</div>
<?php
		}

		public static function save_metabox( $post_id ) {

			if ( isset( $_POST[ 'autopilot-seo_nonce'] ) && !wp_verify_nonce( $_POST[ 'autopilot-seo_nonce'], 'autopilot-seo' ) ) {
				return $post_id;
			}

			if ( empty( $_POST ) || ( isset( $_POST['vc_inline'] ) && $_POST['vc_inline'] == true ) ) {
				return $post_id;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			if ( wp_is_post_revision( $post_id ) ) {
				return $post_id;
			}

			global $pagenow;
			if ( $pagenow == 'admin-ajax.php' ) {
				return $post_id;
			}

			if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
				if ( !current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			}
			else {
				if ( ! current_user_can( 'edit_post', $post_id ) )
					return $post_id;
			}

			if ( isset( $_POST['autopilot-keywords'] ) && $_POST['autopilot-keywords'] !== '' ) {
				$data = preg_replace( '/[^a-zA-Z,0-9 -]/', '', wp_strip_all_tags( $_POST['autopilot-keywords'] ) );
				update_post_meta( $post_id, '_autopilot_seo_keywords', $data );
			}
			else {
				delete_post_meta( $post_id, '_autopilot_seo_keywords' );
			}

			if ( isset( $_POST['autopilot-seo_title'] ) && $_POST['autopilot-seo_title'] !== '' ) {
				update_post_meta( $post_id, '_autopilot_seo_title', wp_strip_all_tags( $_POST['autopilot-seo_title'] ) );
			}
			else {
				delete_post_meta( $post_id, '_autopilot_seo_title' );
			}

			if ( isset( $_POST['autopilot-seo_desc'] ) && $_POST['autopilot-seo_desc'] !== '' ) {
				update_post_meta( $post_id, '_autopilot_seo_desc', wp_strip_all_tags( $_POST['autopilot-seo_desc'] ) );
			}
			else {
				delete_post_meta( $post_id, '_autopilot_seo_desc' );
			}

			if ( isset( $_POST['autopilot-seo_facebook_image'] ) && $_POST['autopilot-seo_facebook_image'] !== '' ) {
				update_post_meta( $post_id, '_autopilot_seo_facebook_image', esc_url( $_POST['autopilot-seo_facebook_image'] ) );
			}
			else {
				delete_post_meta( $post_id, '_autopilot_seo_facebook_image' );
			}

			if ( isset( $_POST['autopilot-seo_twitter_image'] ) && $_POST['autopilot-seo_twitter_image'] !== '' ) {
				update_post_meta( $post_id, '_autopilot_seo_twitter_image', esc_url( $_POST['autopilot-seo_twitter_image'] ) );
			}
			else {
				delete_post_meta( $post_id, '_autopilot_seo_twitter_image' );
			}

		}

	}

	add_action( 'add_meta_boxes', array( 'XforWC_SEO_Metabox', 'add_metabox' ) );
	add_action( 'save_post', array( 'XforWC_SEO_Metabox', 'save_metabox' ) );
