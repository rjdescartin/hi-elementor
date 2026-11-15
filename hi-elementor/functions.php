<?php
// Enqueue parent style, child CSS, and child JS
function theme_enqueue_assets() {
    $parent_style = 'hi-hello-style';

    // Parent theme style
    wp_enqueue_style(
        $parent_style,
        get_template_directory_uri() . '/style.css',
        array(),
        filemtime( get_template_directory() . '/style.css' )
    );

    // Child theme CSS (main.css)
    $child_css = get_stylesheet_directory() . '/assets/css/main.css';
    if ( file_exists( $child_css ) ) {
        wp_enqueue_style(
            'child-main-style',
            get_stylesheet_directory_uri() . '/assets/css/main.css',
            array( $parent_style ),
            filemtime( $child_css )
        );
    }

    // Child theme JS (main.js)
    $child_js = get_stylesheet_directory() . '/assets/js/main.js';
    if ( file_exists( $child_js ) ) {
        wp_enqueue_script(
            'child-main-js',
            get_stylesheet_directory_uri() . '/assets/js/main.js',
            array(), // add 'jquery' here if your script uses it
            filemtime( $child_js ),
            true // load in footer
        );
    }
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_assets' );

function current_year_shortcode() {
    return date('Y');
}
add_shortcode('year', 'current_year_shortcode');

add_filter( 'use_block_editor_for_post', '__return_false' );


/**
 * Allow shortcodes inside selected Elementor widget output.
 */
function rb_render_shortcodes_in_elementor_widgets( $widget_content, $widget ) {
    if ( empty( $widget_content ) || strpos( $widget_content, '[' ) === false ) {
        return $widget_content;
    }

    $allowed_widgets = array(
        'heading',
        'text-editor',
    );

    if (
        method_exists( $widget, 'get_name' ) &&
        ! in_array( $widget->get_name(), $allowed_widgets, true )
    ) {
        return $widget_content;
    }

    return do_shortcode( $widget_content );
}
add_filter( 'elementor/widget/render_content', 'rb_render_shortcodes_in_elementor_widgets', 10, 2 );