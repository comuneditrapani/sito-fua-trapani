<?php

/*
 * metto in coda il caricamento degli stili derivati da
 * designers-comuni-italia.

 * la priorità di caricamento di default dentro wordpress è al massimo 10,
 * quindi a noi basta indicare 11 per starci sopra.

 * la handle a cui associare questa nostra messa-in-coda la prendo dal
 * functions.php del tema template, nel corpo della funzione dci_scripts.

 * inoltre prendo lo stile definito per dci-comuni, che si appoggia a
 * bootstrap-italia-comuni.min.css, che è pieno di codici letterali ripetuti,
 * e lo sostituisco con la nostra versione, dove ho levato le ripetizioni
 * letterali e ci ho messo riferimenti a variabili css.

 */

add_action('wp_enqueue_scripts',
           function() {
               wp_enqueue_style('child-style', get_stylesheet_uri(), array('dci-wp-style'));
               if (wp_style_is('dci-comuni') ) {
                   $localpath = get_stylesheet_directory_uri() . '/assets/css/bootstrap-italia-comuni.min.css';
                   wp_styles()->registered['dci-comuni']->src = $localpath;
               }
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

/* REGISTRAZIONE DEL POST TYPE 'progetto' COME ARCHIVIO  - START */
// Child theme: forza archivio per CPT "progetto"
add_filter('register_post_type_args', function ($args, $post_type) {

  if ($post_type === 'progetto') {
    $args['has_archive'] = true;

    // Scegli lo slug dell’archivio (URL listing)
    // Esempio: /progetti/  (puoi mettere 'attuazione-misure-pnrr' se vuoi)
    $args['rewrite'] = [
      'slug'       => 'progetti',
      'with_front' => false,
    ];

    // (opzionale, ma utile) assicura che sia interrogabile
    $args['public'] = true;
    $args['publicly_queryable'] = true;
  }

  return $args;
}, 20, 2);

?>

