<?php

/*
Plugin Name: Embassy Downloader 
Plugin URI: http://wordpress.org/plugins/embassy-downloader/
Description: Embassy Downloader array
Author: jeton ramadani
Author URI: http://jetonr.com/
Version: 1.0
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/*
 * Include the shared funtions
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/plugin-post-type-and-taxonomy.php' );


/**
 * Generates an array with embassies detail within the given country
 *  
 * @param  string $from_country country to get embasies from
 * @return array  $embassy_arr  embasie in array
 */
function grab_embassy_info( $from_country ) {
   
    $from_country = sanitize_title( $from_country );
    $content = wp_remote_retrieve_body( wp_remote_get( 'http://embassy.goabroad.com/embassies-of/' . $from_country, array( 'timeout' => 60 ) ) );
    $embassy_arr = array();
    $i = 1;
    
    
    preg_match_all('#<article>([^`]*?)</article>#', $content, $embassies );
    
    
    foreach ( $embassies[1] as $embassy ) {
       
        preg_match( '#<span class=\"embassy-name\">(.*?)<\/span>#', $embassy, $title );
        preg_match( '#<div><span>City</span>(.*?)</div>#', $embassy, $city );
        preg_match( '#<div><span>Phone</span>(.*?)</div>#', $embassy, $phones );
        preg_match( '#<div><span>Fax</span>(.*?)</div>#', $embassy, $fax );
        preg_match( '#<span class=\"embassy-address\">(\n.*?)<\/span>#', $embassy, $address );
        preg_match( '#<span>Website</span>(.*?)<a href=\"(.*)\">(.*)<\/a>#', $embassy, $website );
        preg_match( '#<img[^>]*?(?=alt=)[^>]*>#', $embassy, $img_tag );
        preg_match( '#src="([^"]*)"#', $img_tag[0], $flag );
        preg_match( '#alt="([^"]*)"#', $img_tag[0], $to_country );
        preg_match( '#<a\s.*?href=[\'"]mailto:(.*?)[\'"].*?>.*?</a>#', $embassy, $email );
        
        if ( strpos($title[1], "Embassy") ) {
            $embassy_arr[$i]['type'] = "embassy";
        } elseif ( strpos($title[1], "Consulate") ) {
            $embassy_arr[$i]['type'] = "consulate";
        } elseif ( strpos($title[1], "Permanent Mission" ) ) {
            $embassy_arr[$i]['type'] = "permanent-mission";
        } else {
            $embassy_arr[$i]['type'] = "n/a";
        }
     
        $embassy_arr[$i]['from']     = $from_country;
        $embassy_arr[$i]['location'] = sanitize_title($to_country[1]);
        $embassy_arr[$i]['title']    = $title[1];
        $embassy_arr[$i]['flag']     = $flag[1]; 
        $embassy_arr[$i]['city']     = ( $city[1] != '' || $city[1] != null ) ? trim($city[1]) : 'n/a';
        $embassy_arr[$i]['phones']   = ( $phones[1] != '' || $phones[1] != null ) ? trim($phones[1]) : 'n/a';
        $embassy_arr[$i]['fax']      = ( $fax[1] != '' || $fax[1] != null ) ? trim($fax[1]) : 'n/a';
        $embassy_arr[$i]['address']  = ( $address[1] != '' || $address[1] != null ) ? trim($address[1]) : 'n/a';
        $embassy_arr[$i]['website']  = ( $website[3] != '' || $website[3] != null ) ? $website[3] : 'n/a';
        $embassy_arr[$i]['email']    = ( $email[1] != '' || $email[1] != null ) ? trim($email[1]) : 'n/a';
        
        $i++;
    }
    
    return $embassy_arr;
}

function sc_embassy_countries() {
    return array("Afghanistan","Albania","Algeria","Andorra","Angola","Antigua & Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Central African Republic","Chad","Chile","China","Colombia","Comoros","Cook Islands","Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Democratic Republic of Congo","Denmark","Djibouti","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Fiji","Finland","France","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Greenland","Grenada","Guatemala","Guinea","Guinea - Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Ivory Coast","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","Niue","North Korea","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Republic of Congo","Romania","Russia","Rwanda","Samoa","San Marino","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South","South Africa","South Korea","Spain","Sri Lanka","St. Kitts and Nevis","St. Vincent & Grenadines","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Venezuela","Vietnam","Western Sahara","Yemen","Zambia","Zimbabwe");
}

/**
 * All embasies in one array
 * 
 * @param  int   $limit
 * @param  int   $start_from
 * 
 * @return array $country_arr
 * 
 */
function all_embassies_arr( $limit = false, $start_from = false ) {
   
    ini_set('max_execution_time', 1200); // 1200 seconds = 20 minutes
    
    $country_arr = array();
    
    $num = 0;
    $i = 0;
    
    foreach ( sc_embassy_countries() as $country ) {
       
        if ( $start_from != false && $num++ < $start_from ) {
            continue;
        }
        
        if ( $limit != false && $i++ >= $limit ) {
            break;
        } 
        
        $country = sanitize_title( $country );
        $country_arr[$country] = grab_embassy_info($country);
    }

    return $country_arr;
/*    $date = date("g_i_F_j_Y"); 
    $fp = fopen( "embassies_data_$date.json", 'w' );
    fwrite($fp, json_encode( all_embassies_arr() ) );
    fclose($fp);*/

}



function local_embasies_data(){

    $data = file_get_contents( plugin_dir_path( __FILE__ ) . 'data/embassies_data.json');
    $data = json_decode( $data, true );

    return $data;    
}

function sc_insert_tax_terms(){

    // Insert Embassy Cuntries
    foreach ( sc_embassy_countries() as $country ) {

        safe_wp_insert_term( $country, 'countries' );
    }

    // Insert Embassy Types Tax
    $types = array( 'Embassy', 'Consulate', 'Permanent Mission', 'High Commission');
    
    foreach ( $types as $type ) {

        safe_wp_insert_term( $type, 'types' );
    }
}
add_action( 'init', 'sc_insert_tax_terms' );


function safe_wp_insert_term( $term_name, $taxonomy, $args = false ) {

    $term = term_exists( $term_name, $taxonomy );

    if ( $term === 0 || $term === null ) {

        wp_insert_term( $term_name, $taxonomy, $args );
    }  
}


function insert_emabassy_by_country( $embassy_country ) {

    ini_set('max_execution_time', 1200); // 1200 seconds = 20 minute
    
    $data    = local_embasies_data();
    $embassy_country = sanitize_title( $embassy_country );

    foreach ( $data[$embassy_country] as $embassy ) {               

        $embassy['phones'] = str_replace(array(';',' / ','and','or'), ",", $embassy['phones']);
        $embassy['phones'] = preg_replace('/\s*,\s*/', ",", $embassy['phones']);

        $embassy['fax'] = str_replace(array(';',' / ','and','or'), ",", $embassy['fax']);
        $embassy['fax'] = preg_replace('/\s*,\s*/', ",", $embassy['fax']);

        $embassy['email'] = str_replace(array(';',' / ',' and ',' or '), ",", $embassy['email']);
        $embassy['email'] = preg_replace('/\s*,\s*/', ",", $embassy['email']);

        if ($embassy['email'] == "-") {
            $embassy['email'] = "n/a";
        }

        safe_post_insert( $embassy );
    }

}

function safe_post_insert( $embassy ){

    $embassy_check = get_page_by_title( $embassy['title'],'OBJECT','embassy' );

    if( $embassy_check == NULL ) {

        insert_embassy_post( $embassy );

    } else {

        $embassy_data = get_metadata( 'post', $embassy_check->ID );

        if ( ($embassy_data['sc_emb_address'][0] !== $embassy['address'] ) && ( $embassy_data['sc_emb_email'][0] !== $embassy['email'] ) && ( $embassy_data['sc_emb_phone'][0] !== $embassy['phones'] ) ) {
            
            insert_embassy_post( $embassy );
        }
    }

}

function insert_embassy_post( $embassy ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    wp_defer_comment_counting( true );

    $post_data = array(
       'post_title'   => $embassy['title'],
       'post_type'    => 'embassy',
       'post_status'  => 'publish',
       );

    $post_id = wp_insert_post( $post_data );
    
    wp_set_object_terms( $post_id, $embassy['from'], 'countries' );
    wp_set_object_terms( $post_id, $embassy['type'], 'types' );            


    update_post_meta( $post_id, 'sc_emb_address', $embassy['address'] );
    update_post_meta( $post_id, 'sc_emb_email', $embassy['email'] );
    update_post_meta( $post_id, 'sc_emb_phone', $embassy['phones'] );
    update_post_meta( $post_id, 'sc_emb_fax', $embassy['fax'] );
    update_post_meta( $post_id, 'sc_emb_website', $embassy['website'] );
    update_post_meta( $post_id, 'sc_emb_location', $embassy['to'] );
    update_post_meta( $post_id, 'sc_emb_city', $embassy['city'] );

    wp_defer_comment_counting( false );    
}


function add_embasy_by_name(){

    foreach ( array_slice( sc_embassy_countries(), 95, 10 ) as $country ) {
        insert_emabassy_by_country( $country );
    }
    //insert_emabassy_by_country( 'jamaica' );
}
add_action( 'wp_footer', 'add_embasy_by_name' );

function countries_into_columns( $cols ) {
   
    // Grab the categories - top level only (depth=1)
    $args = array(
        'orderby'            => 'name',
        'order'              => 'ASC',
        'hide_empty'         => 0,
    );

    $cuntries_array = get_terms( 'countries', $args ) ;

    // Amount of categories (count of items in array)
    $results_total = count($cuntries_array);

    // How many categories to show per list (round up total divided by 2)
    $country_per_list = ceil($results_total / $cols );

    // Counter number for tagging onto each list
    $list_number = 1;

    // Set the category result counter to zero
    $result_number = 0;

    ?>

    <div style="display: block; clear: both; overflow: hidden; display: inline-block; position:relative">
        <ul class="cat_col" id="cat-col-<?php echo $list_number; ?>">
        <?php

        $alphabet_count = array();
        
        foreach( $cuntries_array as $country ) {

            $char = $country->name[0]; //first char
            $alphabet_count[$char]++;
        }

        foreach ( $alphabet_count as $k => $v ) {
                printf( '<a class="%1$s" href="#emb-%1$s" title="%3$s">%2$s</a>', strtolower($k), $k, $v ); 
        }        
        
        $lastChar = '';
        
        foreach( $cuntries_array as $country ) {
            
            $char = $country->name[0]; //first char
            
            $term_link = get_term_link( $country );
            
            $alphabet_count[$char]++;

            $result_number++;
            
            if ( strtolower($char) !== strtolower($lastChar) ) {

                echo '<h3 id="emb-'. strtolower($char) .'">'. $char .'</h3>'; //print A / B / C etc
                
                $lastChar = $char;
            }       
            
            if( $result_number % $country_per_list == 0 ) {
                            
                $list_number++;

                echo '<li><a style="display:block; width:100%;" href="' . esc_url( $term_link ) . '"><span style="float:left">' . $country->name . '</span><img style="float:right" src="' . plugins_url( 'images/countries/.png', __FILE__ ) . '" alt="'. $country->name .'" height="20"></a></li></ul>';
                if( $list_number > $cols ) { break; }
                echo '<ul class="cat_col" id="cat-col-'.$list_number.'">';

            } else {

                echo '<li><a style="display:block; width:100%;" href="' . esc_url( $term_link ) . '"><span style="float:left">' . $country->name . '</span><img style="float:right" src="' . plugins_url( 'images/countries/.png', __FILE__ ) . '" alt="'. $country->name .'" height="20"></a></li>';
            }
            
        }
        
        ?>
    </div>
<?php
}

function countries_col_shortcode( $atts ) {
      
      $atts = shortcode_atts( array(
          'col' => 2,
      ), $atts );
    
    ob_start();
    countries_into_columns( $atts['col'] );
    return  ob_get_clean();
}
add_shortcode( 'embassies', 'countries_col_shortcode' );