<?php
/**
* Plugin Name: Video Tutorials for Clients
* Plugin URI: https://markethax.com/plugins-wordpress/video-user-manuals
* Description: A video tutorial plugin to teach your clients how to use their brand new websites.
* Version: 1.1
* Author: MarketHax
* Author URI: https://markethax.com
**/

function VUMA_register_cpt_tutorial_resource() {
 
    $labels = array(
        'name' => _x( 'Tutoriales', 'tutorial_resource' ),
        'singular_name' => _x( 'Tutorial', 'tutorial_resource' ),
        'add_new' => _x( 'Añadir nuevo', 'tutorial_resource' ),
        'add_new_item' => _x( 'Añadir nuevo tutorial', 'tutorial_resource' ),
        'edit_item' => _x( 'Editar tutorial', 'tutorial_resource' ),
        'new_item' => _x( 'Nuevo tutorial', 'tutorial_resource' ),
        'view_item' => _x( 'Ver tutorial', 'tutorial_resource' ),
        'search_items' => _x( 'Buscar tutorial', 'tutorial_resource' ),
        'not_found' => _x( 'No se encontraron tutoriales', 'tutorial_resource' ),
        'not_found_in_trash' => _x( 'No se encontraron videos en el basurero', 'tutorial_resource' ),
        'menu_name' => _x( 'Manual', 'tutorial_resource' ),
    );
 
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Tutoriales para administrar tu sitio web',
        'supports' => array( 'title', 'editor'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 2,
        'menu_icon' => 'dashicons-media-spreadsheet',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => 'film',
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );
 
    register_post_type( 'tutorial_resource', $args );
}
add_action( 'init', 'VUMA_register_cpt_tutorial_resource' );
  

function VUMA_custom_menu()
{   
    add_menu_page( 'Page Title', 'Tutoriales', 'edit_posts', 'menu_slug', 'VUMA_xs_tutorial', 'dashicons-format-video', 3);
}
add_action('admin_menu', 'VUMA_custom_menu');

function VUMA_xs_tutorial() {
    $type = 'tutorial_resource';
    $args=array(
    'post_type' => $type,
    'post_status' => 'publish');

    $my_query = new WP_Query($args);
    $maxcols = 3;
    $i = 0;
    echo "<table>";
    echo "<tr>";
	if( $my_query->have_posts() ) {
		while ($my_query->have_posts()) : $my_query->the_post(); ?>
        <?php
        if ($i == $maxcols) {
        $i = 0;
        echo "</tr><tr>";
        }
        ?>

        <td>
            <p style="font-size:16px"><strong><?php the_title(); ?></strong></p>
			<p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_content(); ?></a></p>
        </td>
        <?php 
            $j = 0;
            while ($j <= 5) {
                echo "<td>&nbsp;</td>";
                $j++;
            }
        ?>
        <?php
        $i++;?>
	<?php
		endwhile;
    }
    echo "</table>";
    wp_reset_query();
    
}
