<?php
  /**
   * Archive template: Persone pubbliche
   *
   * specializza il template archive.php
   * NOTA: Il form NON applica direttamente i filtri: invia parametri GET.
   * La query viene modificata in functions.php tramite pre_get_posts.
   */
  
  get_header();
  
  /**
   * 1) Recupero valori correnti dei filtri (da URL).
   *    Li usiamo per ripopolare i campi del form dopo l’invio (UX).
   */
  $comune = isset($_GET['comune']) ? sanitize_text_field(wp_unslash($_GET['comune'])) : '';
  
  /**
   * URL canonico dell’archivio del CPT.
   * Ci serve per l’action del form e per il link di "reset".
   */
  $archive_url = get_post_type_archive_link('persona_pubblica');
  
?>

<main id="main">
  <div class="container" id="main-container">

    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <?php get_template_part("template-parts/common/breadcrumb"); ?>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-12 col-lg-10 py-3">

        <h1 class="mb-2"><?php post_type_archive_title(); ?></h1>
        <p class="lead mb-4">Tutte le persone, con schede di dettaglio.</p>

        <!-- FORM FILTRI / RICERCA / ORDINAMENTO -->
        <form method="get" class="mb-4" action="<?php echo esc_url($archive_url); ?>">
          <div class="row g-3">

            <!-- Filtro per comune (campo ACF text) -->
            <div class="col-12 col-lg-3">
              <select class="form-select" id="comune" name="comune" onchange="form.submit()">
                <option value="">&lt;qualsiasi comune&gt;</option>
                <?php
                    global $wpdb;
                  $query = "select id, post_title "
                      . "from wp_posts "
                      . "where post_type='luogo' "
                      . "order by post_title";
                  $values = $wpdb->get_results($query, ARRAY_A);
                  foreach ($values as $v) {
                      printf(
                          '<option value="%s"%s>%s</option>',
                          esc_attr($v['id']),
                          selected($comune, $v['id'], false),
                          esc_html($v['post_title'])
                      );
                  }
                ?>
              </select>
            </div>

          </div>
        </form>

        <!-- LISTING -->
        <?php if (have_posts()) : ?>
          <div class="row g-3">
            <?php while (have_posts()) :
              the_post();
              $descrizione  = get_field('descrizione') ?? '';

            ?>
              <div class="col-12 col-md-6 col-xl-4">
                <?php get_template_part("template-parts/persona/card-ico"); ?>
              </div>
            <?php endwhile; ?>
          </div>

          <div class="mt-4">
			<nav class="pagination-wrapper justify-content-center col-12">
				<?php echo dci_bootstrap_pagination(); ?>
            </nav>
          </div>

        <?php else : ?>
          <p class="text-muted">Nessun progetto pubblicato.</p>
        <?php endif; ?>

      </div>
    </div>

  </div>
</main>

<?php get_footer(); ?>
