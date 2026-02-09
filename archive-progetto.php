<?php
/**
 * Archive template: Progetti
 *
 * Questo template renderizza la pagina di archivio del CPT "progetto",
 * cioè la lista /progetti/ (o lo slug che avete configurato nel rewrite).
 *
 * Qui aggiungiamo:
 * - un form di filtro/ricerca (GET) coerente con lo stile Bootstrap Italia;
 * - un selettore di ordinamento.
 *
 * NOTA: Il form NON applica direttamente i filtri: invia parametri GET.
 * La query viene modificata in functions.php tramite pre_get_posts.
 */

get_header();

/**
 * 1) Recupero valori correnti dei filtri (da URL).
 *    Li usiamo per ripopolare i campi del form dopo l’invio (UX).
 */
$q            = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
$beneficiario = isset($_GET['beneficiario']) ? sanitize_text_field(wp_unslash($_GET['beneficiario'])) : '';
$avanzamento  = isset($_GET['avanzamento']) ? sanitize_text_field(wp_unslash($_GET['avanzamento'])) : '';
$ord          = isset($_GET['ord']) ? sanitize_text_field(wp_unslash($_GET['ord'])) : 'event_desc';

/**
 * URL canonico dell’archivio del CPT.
 * Ci serve per l’action del form e per il link di "reset".
 */
$archive_url = get_post_type_archive_link('progetto');

  // questa mi serve per ottenere il numero totale di post
  global $wp_query;
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
        <p class="lead mb-4">Elenco dei progetti, con schede di dettaglio.</p>

        <!-- FORM FILTRI / RICERCA / ORDINAMENTO -->
        <form method="get" class="mb-4" action="<?php echo esc_url($archive_url); ?>">
          <div class="row g-3">

            <!-- Ricerca libera (su campi ACF, vedere functions.php) -->
            <div class="col-12 col-lg-6">
              <label class="form-label" for="q">Ricerca</label>
              <input class="form-control" type="search" id="q" name="q"
                     value="<?php echo esc_attr($q); ?>"
                     placeholder="Cerca nei campi del progetto (beneficiario, CUP, descrizione, ecc.)">
            </div>

            <!-- Filtro per beneficiario (campo ACF text) -->
            <div class="col-12 col-lg-3">
              <label class="form-label" for="beneficiario">Beneficiario</label>
              <select class="form-select" id="beneficiario" name="beneficiario">
                <option value="">&lt;qualsiasi&gt;</option>
                <?php
                $values = ['Buseto Palizzolo', 'Valderice', 'Erice', 'Trapani', 'Paceco', 'Misiliscemi', 'Marsala', 'Petrosino', 'Mazara del Vallo', 'Campobello di Mazara', 'Castelvetrano'];
                foreach ($values as $v) {
                  printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr($v),
                    selected($beneficiario, $v, false),
                    esc_html($v)
                  );
                }
                ?>
              </select>
            </div>

            <!-- Filtro per avanzamento (campo ACF text con valori convenzionali) -->
            <div class="col-12 col-lg-3">
              <label class="form-label" for="avanzamento">Avanzamento</label>
              <select class="form-select" id="avanzamento" name="avanzamento">
                <option value="">&lt;qualsiasi&gt;</option>
                <?php
                // Valori indicati nell’istruzione del campo ACF "avanzamento" (vedi export JSON)
                    $values = ['Affidato incarico PFTE', 'Progetto di fattibilità tecnico economica', 'Studio di fattibilità tecnico economica', 'Documento di Integrazione e Programmazione', 'Proposto', 'Esecutivo in corso di redazione', 'Esecutivo da aggiornare', 'Esecutivo', 'Approvato', 'In corso di verifica', 'Cantierabile', 'Avviato', 'Concluso', 'Consegnato'];
                foreach ($values as $v) {
                  printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr($v),
                    selected($avanzamento, $v, false),
                    esc_html($v)
                  );
                }
                ?>
              </select>
            </div>

            <!-- Ordinamento -->
            <div class="col-12 col-lg-4">
              <label class="form-label" for="ord">Ordina per</label>
              <select class="form-select" id="ord" name="ord">
                <option value="">&lt;scegli&gt;</option>
                <option value="event_desc" <?php selected($ord, 'event_desc'); ?>>Data (evento più recente → meno recente)</option>
                <option value="event_asc"  <?php selected($ord, 'event_asc');  ?>>Data (evento meno recente → più recente)</option>
                <option value="title_asc"  <?php selected($ord, 'title_asc');  ?>>Titolo (A → Z)</option>
                <option value="title_desc" <?php selected($ord, 'title_desc'); ?>>Titolo (Z → A)</option>
              </select>
              <div class="form-text">Se la data evento non è presente, il progetto finisce in coda.</div>
            </div>

            <!-- CTA -->
            <div class="col-12 d-flex gap-2">
              <button class="btn btn-primary" type="submit">Applica</button>
              <a class="btn btn-outline-primary" href="<?php echo esc_url($archive_url); ?>">Reset</a>
            </div>

            <div class="col-12 d-flex gap-2">
              <?php echo $wp_query->found_posts; ?> progetti trovati
            </div>
          </div>
        </form>

        <!-- LISTING -->
        <?php if (have_posts()) : ?>
          <div class="row g-3">
            <?php while (have_posts()) : the_post();

              // Campi ACF
              $beneficiario = get_field('beneficiario') ?? '';
              $avanzamento  = get_field('avanzamento') ?? '';
              $cup          = get_field('cup') ?? '';
              $importo      = get_field('importo_intervento') ?? '';
              $descrizione  = get_field('descrizione') ?? '';

              // Importo: se è numerico lo formattiamo, altrimenti lasciamo la stringa
              $importo_fmt = '';
              if ($importo !== '') {
                $importo_fmt = is_numeric($importo)
                  ? number_format_i18n((float)$importo, 2) . ' €'
                  : (string)$importo;
              }

            ?>
              <div class="col-12 col-md-6 col-xl-4">
                <div class="card-wrapper card-space h-100">
                  <div class="card card-bg rounded shadow-sm h-100 d-flex flex-column">
                    <div class="card-body d-flex flex-column flex-grow-1">

                      <h2 class="h5 card-title mb-2">
                        <a class="text-decoration-none" href="<?php the_permalink(); ?>">
                          <?php the_title(); ?>
                        </a>
                      </h2>

                      <?php if (!empty($avanzamento)) : ?>
                        <div class="mb-2">
                          <span class="badge bg-primary"><?php echo esc_html($avanzamento); ?></span>
                        </div>
                      <?php endif; ?>

                      <?php if (!empty($descrizione)) : ?>
                        <p class="mb-3 text-muted">
                          <?php echo esc_html(wp_trim_words(wp_strip_all_tags($descrizione), 22)); ?>
                        </p>
                      <?php endif; ?>

                      <dl class="row mb-3 small">
                        <?php if (!empty($beneficiario)) : ?>
                          <dt class="col-5">Beneficiario</dt><dd class="col-7"><?php echo esc_html($beneficiario); ?></dd>
                        <?php endif; ?>
                        <?php if (!empty($importo_fmt)) : ?>
                          <dt class="col-5">Importo</dt><dd class="col-7"><?php echo esc_html($importo_fmt); ?></dd>
                        <?php endif; ?>
                        <?php if (!empty($cup)) : ?>
                          <dt class="col-5">CUP</dt><dd class="col-7"><?php echo esc_html($cup); ?></dd>
                        <?php endif; ?>
                      </dl>

                      <div class="mt-auto">
                        <a class="btn btn-outline-primary btn-sm" href="<?php the_permalink(); ?>">
                          Vai alla scheda
                        </a>
                      </div>

                    </div>
                  </div>
                </div>
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
