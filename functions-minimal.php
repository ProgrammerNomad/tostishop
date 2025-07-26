<?php
// Minimal test version
function tostishop_widgets_init() {
    register_sidebar(array(
        'name' => 'Footer Widget 1',
        'id' => 'footer-1',
    ));
}
add_action('widgets_init', 'tostishop_widgets_init');
?>
