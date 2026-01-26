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
 * ============================================================
 * ARCHIVIO CPT "progetto" + fix breadcrumb duplicato
 * ============================================================
 *
 * CONTENUTO DI QUESTO BLOCCO:
 * 1) Forza l’abilitazione dell’archivio del CPT "progetto"
 *    e imposta lo slug dell’archivio a /progetti/
 * 2) Corregge il breadcrumb duplicato: Home / Progetti / Progetti
 *
 * NOTA:
 * - Il post_type key è "progetto" (singolare). È normale anche se nel menu
 *   lo mostri come "Progetti".
 * - Dopo qualunque modifica a rewrite/has_archive: Impostazioni -> Permalink -> Salva.
 */

/**
 * 1) Abilita archive del CPT "progetto"
 *
 * Perché serve:
 * - Se il CPT è registrato da un plugin o dal tema e non possiamo modificare
 *   direttamente register_post_type(), usiamo questo filtro per correggere
 *   gli argomenti "al volo".
 *
 * Cosa fa:
 * - abilita has_archive
 * - imposta lo slug "progetti" (quindi URL: /progetti/)
 * - assicura che sia interrogabile dal frontend
 */
add_filter('register_post_type_args', function ($args, $post_type) {

  // Interveniamo SOLO sul CPT interessato
  if ($post_type !== 'progetto') {
    return $args;
  }

  // Abilita l’archivio del CPT (template: archive-progetto.php)
  $args['has_archive'] = true;

  // Imposta lo slug dell’archivio (URL listing): /progetti/
  $args['rewrite'] = [
    'slug'       => 'progetti',
    'with_front' => false,
  ];

  // (Opzionale, ma utile) rende il CPT pubblico e queryable
  $args['public']             = true;
  $args['publicly_queryable'] = true;

  return $args;

}, 20, 2); // 20 = priorità: dopo i filtri standard (default 10)

/**
 * 2) Fix breadcrumb duplicato sull’archivio "Progetti"
 *
 * Problema:
 * - Il breadcrumb può risultare: Home / Progetti / Progetti
 *   dove:
 *   - penultimo crumb = link "Progetti"
 *   - ultimo crumb    = testo "Progetti" (active)
 *
 * Soluzione:
 * - Se gli ultimi due elementi hanno lo stesso testo, rimuoviamo il penultimo
 *   (il link) e teniamo l’ultimo (testo), così il breadcrumb termina con
 *   elemento non linkato, come da buona pratica.
 */
add_filter('breadcrumb_trail_items', function ($items) {

  // Applica SOLO nell’archivio del CPT "progetto"
  if (!is_post_type_archive('progetto')) {
    return $items;
  }

  // Sicurezza: ci servono almeno 3 elementi (Home, Archivio, Active)
  if (!is_array($items) || count($items) < 3) {
    return $items;
  }

  $last = count($items) - 1;

  // Confrontiamo il testo (senza HTML) degli ultimi due elementi
  $txt_last = trim(wp_strip_all_tags($items[$last]));      // ultimo (active)
  $txt_prev = trim(wp_strip_all_tags($items[$last - 1]));  // penultimo (link)

  // Se sono uguali, elimina il penultimo (link) e tieni l’ultimo (testo)
  if ($txt_last !== '' && $txt_last === $txt_prev) {
    unset($items[$last - 1]);
    $items = array_values($items);
  }

  return $items;

}, 20);

/**
 * ------------------------------------------------------------
 * 3) CALCOLO "DATA EVENTO PIÙ RECENTE" SUL PROGETTO
 * ------------------------------------------------------------
 * Obiettivo: ordinare la lista dei progetti in base alla data (campo ACF "data")
 * degli "Eventi Progetto" collegati dal field ACF "articoli" (post_object multiplo).
 *
 * Problema: la data è su un altro post_type ("evento-progetto"), quindi non
 * è comodo/performante ordinarla in tempo reale facendo join/loop ad ogni query.
 *
 * Soluzione: calcoliamo e salviamo sul progetto una meta tecnica:
 *   _last_event_date_ymd = intero in formato YYYYMMDD (es. 20260126)
 * Se non esiste data, salviamo 0.
 *
 * Poi in archivio ordiniamo per meta_value_num: così i "senza data" (0) vanno in coda
 * quando ordiniamo DESC.
 *
 * Questo hook è ACF-specifico e scatta quando ACF salva i campi.
 */
add_action('acf/save_post', function ($post_id) {

  // ACF può chiamare questo hook anche per options, user, ecc.
  // Noi vogliamo solo i post reali.
  if (!is_numeric($post_id)) {
    return;
  }

  $post_id = (int) $post_id;

  // Interveniamo solo sui Progetti
  if (get_post_type($post_id) !== 'progetto') {
    return;
  }

  /**
   * Field ACF "articoli" (post_object multiplo, return_format ID)
   * Contiene gli ID degli eventi collegati (post_type: evento-progetto)
   */
  $event_ids = get_field('articoli', $post_id);

  // Nessun evento collegato => data = 0
  if (empty($event_ids) || !is_array($event_ids)) {
    update_post_meta($post_id, '_last_event_date_ymd', 0);
    return;
  }

  $max_ymd = 0;

  foreach ($event_ids as $eid) {
    $eid = (int) $eid;

    /**
     * Campo ACF "data" sull'evento:
     * - type: date_picker
     * - return_format: d/m/Y (dal tuo JSON export)
     */
    $d = get_field('data', $eid);
    if (empty($d)) {
      continue;
    }

    // Converte "d/m/Y" in DateTime
    $dt = date_create_from_format('d/m/Y', $d);
    if (!$dt) {
      continue;
    }

    // Converte in YYYYMMDD (numerico) per ordinare facilmente
    $ymd = (int) $dt->format('Ymd');
    if ($ymd > $max_ymd) {
      $max_ymd = $ymd;
    }
  }

  update_post_meta($post_id, '_last_event_date_ymd', $max_ymd);

}, 20); // priority 20: dopo che ACF ha salvato i campi

/**
 * ------------------------------------------------------------
 * 4) FILTRI + RICERCA ACF + ORDINAMENTO ARCHIVIO PROGETTI
 * ------------------------------------------------------------
 * Questo è il cuore della funzionalità:
 * - legge i parametri GET del form (q, beneficiario, avanzamento, ord)
 * - modifica la main query dell’archivio CPT
 *
 * Usiamo pre_get_posts perché è l’hook standard per modificare la query principale
 * prima che WordPress generi la SQL. 
 */
add_action('pre_get_posts', function ($query) {

  // Non tocchiamo admin e query secondarie
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  // Interveniamo SOLO sull’archivio del CPT "progetto"
  if (!is_post_type_archive('progetto')) {
    return;
  }

  /**
   * Costruiamo una meta_query:
   * - relation AND globale (tutti i filtri devono valere)
   * - una sotto-query OR per la ricerca libera (q) sui campi ACF
   *
   * NOTA: meta_query con molti LIKE può pesare sul DB se i record sono tanti.
   * Se crescono molto i progetti, valutare indicizzazione/soluzioni dedicate.
   */
  $meta_query = ['relation' => 'AND'];

  // -------------------------
  // Filtro: beneficiario
  // -------------------------
  if (!empty($_GET['beneficiario'])) {
    $meta_query[] = [
      'key'     => 'beneficiario',
      'value'   => sanitize_text_field(wp_unslash($_GET['beneficiario'])),
      'compare' => 'LIKE',
    ];
  }

  // -------------------------
  // Filtro: avanzamento
  // -------------------------
  if (!empty($_GET['avanzamento'])) {
    $meta_query[] = [
      'key'     => 'avanzamento',
      'value'   => sanitize_text_field(wp_unslash($_GET['avanzamento'])),
      'compare' => 'LIKE',
    ];
  }

  // -------------------------
  // Ricerca libera: q su campi ACF (Progetto)
  // -------------------------
  if (!empty($_GET['q'])) {
    $term = sanitize_text_field(wp_unslash($_GET['q']));

    /**
     * Lista dei field name ACF su cui cerchiamo (dal tuo export JSON "Campi di Progetto")
     * Includiamo sia text che textarea; includiamo anche rif_comune (number) perché spesso
     * l’utente può voler cercare un codice/protocollo.
     *
     * Limite solo ai campi testuali.
     */
    $acf_keys_search = [
      'titolo',
      'descrizione',
      'beneficiario',
      'rif_comune',
      'azione_st',
      'azione_pr_fesr',
      'obiettivo_specifico',
      'azione_di_riferimento',
      'settore_dintervento',
      'importo_intervento',
      'cup',
      'avanzamento',
    ];

    // Sotto query OR: basta che il termine compaia in almeno uno dei campi
    $sub = ['relation' => 'OR'];
    foreach ($acf_keys_search as $k) {
      $sub[] = [
        'key'     => $k,
        'value'   => $term,
        'compare' => 'LIKE',
      ];
    }

    $meta_query[] = $sub;
  }

  // Applica meta_query solo se abbiamo almeno un filtro/ricerca
  if (count($meta_query) > 1) {
    $query->set('meta_query', $meta_query);
  }

  /**
   * -------------------------
   * ORDINAMENTO
   * -------------------------
   * ord può essere:
   * - event_desc: per _last_event_date_ymd DESC (senza data = 0 => in coda)
   * - event_asc:  per _last_event_date_ymd ASC (senza data = 0 => in testa)
   * - title_asc / title_desc
   */
  $ord = isset($_GET['ord']) ? sanitize_text_field(wp_unslash($_GET['ord'])) : 'event_desc';

  if ($ord === 'title_asc') {
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
  } elseif ($ord === 'title_desc') {
    $query->set('orderby', 'title');
    $query->set('order', 'DESC');
  } else {
    // Default: ordinamento per data evento più recente
    $query->set('meta_key', '_last_event_date_ymd');
    $query->set('orderby', 'meta_value_num');

    if ($ord === 'event_asc') {
      $query->set('order', 'ASC');
    } else {
      $query->set('order', 'DESC');
    }
  }

}, 30); // priority 30: per farlo girare dopo altri eventuali filtri tema/plugin

?>