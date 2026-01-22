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


/**
 * informa dci che il post-type 'progetti' vuole la sua pagina-lista

 * Con ACF abbiamo registrato un post-type 'progetti', ciascuno
 * presentato con la pagina single-progetto.php.  Qui informiamo il
 * tema che questo post-type è da presentare come lista in una sua
 * pagina archive-progetto.php
 */
add_filter('register_post_type_args', function ($args, $post_type) {

    // non capisco perché, ma qui va al singolare.
    if ($post_type === 'progetto') {
        $args['has_archive'] = true;

        // Scegli lo slug dell’archive (URL listing)
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


/**
 * accorcia la lista dei breadcrumb, se gli ultimi due elementi sono uguali.

 * rimuove il penultimo (che è un link) e mantiene l'ultimo (che è un testo).
 */

add_filter('breadcrumb_trail_items', function($items) {
    if (is_array($items) && count($items) >= 3) {
        $last = count($items) - 1;
        $txt_last = trim(wp_strip_all_tags($items[$last]));      // ultimo (active)
        $txt_prev = trim(wp_strip_all_tags($items[$last - 1]));  // penultimo (link)
        // Se sono uguali, elimina il penultimo (link) e tieni l'ultimo (testo)
        if ($txt_last !== '' && $txt_last === $txt_prev) {
            unset($items[$last - 1]);
            $items = array_values($items);
        }
    }
    return $items;
}, 20);


?>
