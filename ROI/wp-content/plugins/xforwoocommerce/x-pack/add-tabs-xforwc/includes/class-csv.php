<?php

class XforWC_AddTabs_CSV {

    public static $tab = array();

    public static function get_table( $tab ) {

        self::$tab = $tab;

        $csv = empty( $tab['csv'] ) ? false : array_map( 'str_getcsv', file( esc_url( $tab['csv'] ) ) );

        if ( $csv ) {

            $output = '<table class="xforwc-at-table-' . sanitize_title ( $tab['name']  ) . '">';

            foreach( $csv as $k => $v ) {
                $output .= '<tr>';

                if ( $k == 0 ) {
                    $output .= XforWC_AddTabs_CSV::_get_table_header( $v );
                }
                else {
                    $output .= XforWC_AddTabs_CSV::_get_table_row( $v );
                }


                $output .= '</tr>';
            }
    
            $output .= '</table>';
    
            return $output;

        }

    }

    public static function _get_table_header( $header ) {
        $output = '';

        foreach ( $header as $k => $v ) {
            $output .=  '<th class="xforwc-at-table-col-' . esc_attr( $k ) . '">' . wp_kses_post( self::_get_csv_value( $v ) ) . '</th>';
        }

        return $output;
    }

    public static function _get_table_row( $rows ) {
        $output = '';

        foreach ( $rows as $k => $v ) {
            $output .=  '<td class="xforwc-at-table-col-' . esc_attr( $k ) . '">' . wp_kses_post( self::_get_csv_value( $v ) ) . '</td>';
        }

        return $output;
    }

    public static function _get_csv_value( $value ) {
        if ( empty( self::$tab['options'] ) ) {
            return $value;
        }

        return self::_find_value( $value );
    }

    public static function __get_value_html( $value ) {
        return do_shortcode( wp_kses_post( $value['html'] ) );
    }
    public static function __get_value_image( $value ) {
        return '<img src="' . esc_url( $value['image'] ) . '" />';
    }

    public static function _find_value( $value ) {
        foreach ( self::$tab['options'] as $k => $v ) {
            if ( $v['value'] == $value ) {
                return call_user_func( 'self::__get_value_' . $v['type'], $v);
            }
        }

        return $value;
    }

}
