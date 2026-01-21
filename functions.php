<?php

/*
 * metto in coda il caricamento degli stili derivati da
 * designers-comuni-italia.

 * la priorità di caricamento per tutto quello che succede dentro wordpress è
 * al massimo 10, quindi basta indicare 11 per starci sopra.

 * la handle a cui associare questa nostra messa-in-coda la prendo dal
 * functions.php del tema template, nel corpo della funzione dci_scripts.

 */

add_action( 'wp_enqueue_scripts',
            function() {
                wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('dci-wp-style')  );
            },
            11);

//add_action( 'wp_enqueue_scripts', 'mio_tema_child_enqueue_styles', 11 );

/*/
function mio_tema_child_enqueue_styles() {
    // Carica CSS di Bootstrap Italia
    wp_enqueue_style( 'bootstrap-italia-css', get_stylesheet_directory_uri() . '/assets/css/bootstrap-italia-comuni.min.css' );
    // Carica JS di Bootstrap Italia
    wp_enqueue_script( 'bootstrap-italia-js', get_stylesheet_directory_uri() . '/assets/js/bootstrap-italia.min.js', array(), null, true );
}
/**/

?>

