<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    class XforWC_AddTabs_Metabox {

        public static $tab = array();

		public static function add_metabox() {
            add_meta_box( 'add-tabs-xforwc', 'Add Tabs for WooCommerce', array( 'XforWC_AddTabs_Metabox', 'metabox' ), 'product' );
		}

		public static function get_tabs_with_meta() {
            $with = array();

            $tabs = SevenVXGet()->get_option( 'tabs', 'add_tabs_xforwc' );

            if ( empty( $tabs ) ) {
                return $with;
            }

            foreach( $tabs as $k => $v ) {
                if ( $v['type'] == 'product_meta' && $v['add_field'] == 'yes' ) {
                    $with[] = $v;
                }
            }

            return $with;
        }

		public static function metabox() {
            $tabs = self::get_tabs_with_meta();

            if ( empty( $tabs ) ) {
?>
                <div class="add-tabs-xforwc-metabox-wrapper">
                    <?php esc_html_e( 'Meta keys set in Product Tabs settings will appear here', 'xforwoocommerce' ); ?>
                </div>
<?php
                return false;
            }
?>
            <div class="add-tabs-xforwc-metabox-wrapper">
                <input type="hidden" name="add-tabs-xforwc-nonce" value="<?php echo wp_create_nonce( 'add-tabs-xforwc' ); ?>" />
<?php
                foreach( $tabs as $k => $v ) {
                    self::$tab = $v;
                    self::get_tab_option();
                }
?>
            </div>
<?php
        }

        public static function get_tab_option() {
            $name = sanitize_title( self::$tab['name'] );

            switch( self::$tab['meta_type'] ) {
                
                case 'image' :
?>
                    <h3><?php echo esc_html( self::$tab['name'] . ' (' . self::$tab['key'] . ')' ) . ' ' . esc_html__( 'image URL', 'xforwoocommerce' ); ?></h3>
                    <input type="text" name=<?php echo esc_attr( self::$tab['key'] ) ?>" value="<?php echo esc_attr( get_post_meta( get_the_ID(), self::$tab['key'], true ) ); ?>" />               
<?php
                break;
                
                case 'image' :
?>
                    <h3><?php echo esc_html( self::$tab['name'] . ' (' . self::$tab['key'] . ')' ) . ' ' . esc_html__( 'YouTube video URL', 'xforwoocommerce' ); ?></h3>
                    <input type="text" name=<?php echo esc_attr( self::$tab['key'] ) ?>" value="<?php echo esc_attr( get_post_meta( get_the_ID(), self::$tab['key'], true ) ); ?>" />               
<?php
                break;

                case 'csv' :
?>
                    <h3><?php echo esc_html( self::$tab['name'] . ' (' . self::$tab['key'] . ')' ) . ' ' . esc_html__( 'CSV file URL', 'xforwoocommerce' ); ?></h3>
                    <input type="text" name="<?php echo esc_attr( self::$tab['key'] ) ?>" value="<?php echo esc_attr( get_post_meta( get_the_ID(), self::$tab['key'], true ) ); ?>" />               
<?php
                break;

                case 'html' :
                default :
?>
                    <h3><?php echo esc_html( self::$tab['name'] . ' (' . self::$tab['key'] . ')' ) . ' ' . esc_html__( 'text, HTML, shortcode', 'xforwoocommerce' ); ?></h3>
                    <textarea name="<?php echo esc_attr( self::$tab['key'] ) ?>"><?php echo wp_kses_post( get_post_meta( get_the_ID(), self::$tab['key'], true ) ); ?></textarea>
<?php
                break;
            }
        }

		public static function get_data( $type, $data ) {
            switch ( $type ) {
                case 'image' :
                case 'video' :
                case 'csv' :
                    return esc_url( $data );
                break;
                case 'html' :
                default :
                     return wp_kses_post( $data );
                break;
            }
        }

		public static function save_metabox( $post_id ) {

			if ( isset( $_POST[ 'add-tabs-xforwc-nonce'] ) && !wp_verify_nonce( $_POST[ 'add-tabs-xforwc-nonce'], 'add-tabs-xforwc' ) ) {
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

            $tabs = self::get_tabs_with_meta();

            if ( !empty( $tabs ) ) {
                foreach( $tabs as $k => $v ) {
                    if ( isset( $_POST[ $v['key']] ) && $_POST[ $v['key']] !== '' ) {
                        update_post_meta( $post_id, $v['key'], self::get_data( $v['meta_type'], $_POST[ $v['key']] ) );
                    }
                    else {
                        delete_post_meta( $post_id,  $v['key'] );
                    }
                }
            }

		}

	}

    add_action( 'add_meta_boxes', array( 'XforWC_AddTabs_Metabox', 'add_metabox' ) );
	add_action( 'save_post', array( 'XforWC_AddTabs_Metabox', 'save_metabox' ) );
