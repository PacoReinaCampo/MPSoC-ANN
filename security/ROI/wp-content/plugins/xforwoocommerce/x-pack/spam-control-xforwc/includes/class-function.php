<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_SpamControl_Function {

        public static $options = array();

		public static function __start( $options ) {
			$job = wp_next_scheduled( 'xforwc_spam_control' );

			if ( $options['std']['spam_control'] == 'yes' ) {
				if ( $job ) {
					wp_unschedule_event( $job, 'xforwc_spam_control' );
				}

				wp_schedule_event( time(), SevenVXGet()->get_option( 'interval', 'spam_control_xforwc' ), 'xforwc_spam_control' );
			}
			else {
				if ( $job ) {
					wp_unschedule_event( $job, 'xforwc_spam_control' );
				}
			}
		}

        public static function _merge_blacklist( $spam ) {

			$blacklist = get_option( 'blacklist_keys', '' );

			if ( !is_string( $blacklist ) ) {
				return false;
			}

			$blacklist = array_flip( preg_split("/\r\n|\n|\r/", $blacklist ) );

			$_blacklist = array();
			$plugin_blacklist = array();

			if ( array_key_exists( '%%%XFORWC-SPAM-FILTER-START%%%', $blacklist ) ) {
				$plugin_blacklist = self::__get_plugin_blacklist( $blacklist );
			}

			$_blacklist['%%%XFORWC-SPAM-FILTER-START%%%'] = true;

			foreach( $spam as $k => $v ) {
				if ( !empty( $k ) && !array_key_exists( $k, $plugin_blacklist ) ) {
					$_blacklist[$k] = true;
				}
			}

			if ( !empty( $plugin_blacklist ) ) {
				foreach( $plugin_blacklist as $k => $v ) {
					if ( !empty( $k ) && !array_key_exists( $k, $_blacklist ) ) {
						$_blacklist[$k] = true;
					}
				}
			}

			if ( !empty( $_blacklist ) ) {
				$_blacklist['%%%XFORWC-SPAM-FILTER-END%%%'] = true;
			}

			self::__get_manual_blacklist( $_blacklist );
			
			update_option( 'blacklist_keys', self::__get_string_from( array_merge( $blacklist, $_blacklist ) ) );
		}

        public static function __get_string_from( $list ) {
			$string = '';

			foreach( $list as $k => $v ) {
				$string .= $k ."\n";
			}

			return $string;
		}

        public static function __get_plugin_blacklist( &$blacklist ) {
			$already_started = false;
			$plugin_blacklist = array();

			$manual_started = false;
			$manual_blacklist = array();
	
			foreach( $blacklist as $k => $v ) {
				if ( $k == '%%%XFORWC-SPAM-FILTER-START%%%' || $already_started ) {
					if ( !$already_started ) {
						$already_started = true;
					}

					unset( $blacklist[$k] );

					if ( !empty( $k ) ) {
						$plugin_blacklist[$k] = true;
						
						if ( $k == '%%%XFORWC-SPAM-FILTER-END%%%' ) {
							$already_started = false;
						}
					}
				}

				if ( $k == '%%%XFORWC-MANUAL-FILTER-START%%%' || $manual_started ) {
					if ( !$manual_started ) {
						$manual_started = true;
					}

					unset( $blacklist[$k] );

					if ( !empty( $k ) ) {

						$manual_blacklist[$k] = true;
			
						if ( $k == '%%%XFORWC-MANUAL-FILTER-END%%%' ) {
							$manual_started = false;
						}
					}
				}
			}

			return $plugin_blacklist;
		}

        public static function __get_manual_blacklist( &$_blacklist )  {
			$manual_blacklist = SevenVXGet()->get_option( 'blacklist', 'spam_control_xforwc' );

			if ( !empty( $manual_blacklist ) ) {
			
				if ( !is_string( $manual_blacklist ) ) {
					return false;
				}
	
				$manual_blacklist = array_flip( preg_split("/\r\n|\n|\r/", $manual_blacklist ) );

				if ( !empty( $manual_blacklist ) ) {
					$_blacklist['%%%XFORWC-MANUAL-FILTER-START%%%'] = true;
					
					foreach( $manual_blacklist as $k => $v ) {
						if ( !empty( $k ) && !array_key_exists( $k, $_blacklist ) ) {
							$_blacklist[$k] = true;
						}
					}

					$_blacklist['%%%XFORWC-MANUAL-FILTER-END%%%'] = true;
				}
			}
		}

        public static function _delete_comments( $comments ) {
			$ids = array();
			foreach( $comments as $comment ) {
				
				if ( wp_delete_comment( $comment, true ) ) {
					if ( !array_key_exists( $comment->comment_post_ID, $ids ) ) {
						$ids[$comment->comment_post_ID] = true;
					}
				}

			}

			if ( !empty( $ids ) ) {
				foreach( $ids as $id ) {
					clean_post_cache( $id );
				}
			}
		}

        public static function _check_spam() {

			$args = array(
				'status' => 'spam'
			);

			$spammed = get_comments( $args );

			$spam = array();

			foreach( $spammed as $comment ) {
				$entry = empty( $comment->comment_author_IP ) ? $comment->comment_author : $comment->comment_author_IP;
				if ( !array_key_exists( $entry, $spam ) ) {
					$spam[$entry] = true;
				}
			}

			if ( !empty( $spam ) ) {
				self::_merge_blacklist( $spam );

				if ( SevenVXGet()->get_option( 'clear_spam', 'spam_control_xforwc' ) == 'yes' ) {
					self::_delete_comments( $spammed );
				}
			}
			
			if ( SevenVXGet()->get_option( 'clear_trash', 'spam_control_xforwc' ) == 'yes' ) {
				$args = array(
					'status' => 'trash'
				);
	
				$trashed = get_comments( $args );

				if ( !empty( $trashed ) ) {
					self::_delete_comments( $trashed );
				}
			}
                
        }

    }

	add_action( 'xforwc_spam_control', array( 'XforWC_SpamControl_Function', '_check_spam' ) );

