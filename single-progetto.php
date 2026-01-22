<?php
/**
 * Single template: Progetto (PNRR)
 *
 * @package Design_Comuni_Italia
 */

get_header();

// Campi ACF (già presenti nel tuo file)
$descrizione           = get_field('descrizione') ?? '';
$beneficiario          = get_field('beneficiario') ?? '';
$rif_comune            = get_field('rif_comune') ?? '';
$azione_st             = get_field('azione_st') ?? '';
$azione_pr_fesr        = get_field('azione_pr_fesr') ?? '';
$obiettivo_specifico   = get_field('obiettivo_specifico') ?? '';
$azione_di_riferimento = get_field('azione_di_riferimento') ?? '';
$settore_dintervento   = get_field('settore_dintervento') ?? '';
$importo_intervento    = get_field('importo_intervento') ?? '';
$cup                   = get_field('cup') ?? '';
$avanzamento           = get_field('avanzamento') ?? '';

// Utilità
$fmt_importo = '';
if ($importo_intervento !== '') {
  // accetta sia numerico che stringa già formattata
  if (is_numeric($importo_intervento)) {
    $fmt_importo = number_format_i18n((float)$importo_intervento, 2) . ' €';
  } else {
    $fmt_importo = (string) $importo_intervento;
  }
}
?>

<main id="main">
  <div class="container" id="main-container">

    <div class="row">
      <div class="col-12 px-lg-4">
        <?php get_template_part("template-parts/common/breadcrumb"); ?>
      </div>
    </div>

    <div class="row">
      <!-- Colonna contenuto -->
      <div class="col-lg-8 px-lg-4 py-3">

        <header class="mb-4">
          <h1 class="mb-2"><?php the_title(); ?></h1>

          <?php if (!empty($descrizione)) : ?>
            <p class="lead mb-0"><?php echo wp_kses_post($descrizione); ?></p>
          <?php endif; ?>
        </header>

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <?php if (trim(get_the_content())) : ?>
            <div class="richtext-wrapper lora mb-4">
              <?php the_content(); ?>
            </div>
          <?php endif; ?>
        <?php endwhile; endif; ?>

        <div class="accordion" id="accordionProgetto">

          <?php
          // Helper: stampa una riga "Etichetta: Valore" dentro un elenco
          $print_row = function($label, $value) {
            if (empty($value) && $value !== '0') return;
            echo '<li class="d-flex justify-content-between gap-3 py-2 border-bottom">';
            echo '<span class="text-muted">' . esc_html($label) . '</span>';
            echo '<span class="fw-semibold text-end">' . esc_html($value) . '</span>';
            echo '</li>';
          };
          ?>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingDettagli">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDettagli" aria-expanded="true" aria-controls="collapseDettagli">
                Dettagli progetto
              </button>
            </h2>
            <div id="collapseDettagli" class="accordion-collapse collapse show" aria-labelledby="headingDettagli" data-bs-parent="#accordionProgetto">
              <div class="accordion-body">
                <ul class="list-unstyled mb-0">
                  <?php $print_row('Beneficiario', $beneficiario); ?>
                  <?php $print_row('Rif. Comune', $rif_comune); ?>
                  <?php $print_row('Settore d’intervento', $settore_dintervento); ?>
                  <?php $print_row('Obiettivo specifico', $obiettivo_specifico); ?>
                </ul>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFinanziamento">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFinanziamento" aria-expanded="false" aria-controls="collapseFinanziamento">
                Finanziamento
              </button>
            </h2>
            <div id="collapseFinanziamento" class="accordion-collapse collapse" aria-labelledby="headingFinanziamento" data-bs-parent="#accordionProgetto">
              <div class="accordion-body">
                <ul class="list-unstyled mb-0">
                  <?php $print_row('CUP', $cup); ?>
                  <?php $print_row('Importo intervento', $fmt_importo); ?>
                  <?php $print_row('Azione ST', $azione_st); ?>
                  <?php $print_row('Azione PR FESR', $azione_pr_fesr); ?>
                  <?php $print_row('Azione di riferimento', $azione_di_riferimento); ?>
                </ul>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingAvanzamento">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAvanzamento" aria-expanded="false" aria-controls="collapseAvanzamento">
                Avanzamento
              </button>
            </h2>
            <div id="collapseAvanzamento" class="accordion-collapse collapse" aria-labelledby="headingAvanzamento" data-bs-parent="#accordionProgetto">
              <div class="accordion-body">
                <?php if (!empty($avanzamento)) : ?>
                  <p class="mb-0"><?php echo esc_html($avanzamento); ?></p>
                <?php else : ?>
                  <p class="mb-0 text-muted">Informazione non disponibile.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <?php if (have_rows('documenti')) : ?>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingDocumenti">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDocumenti" aria-expanded="false" aria-controls="collapseDocumenti">
                  Documenti
                </button>
              </h2>
              <div id="collapseDocumenti" class="accordion-collapse collapse" aria-labelledby="headingDocumenti" data-bs-parent="#accordionProgetto">
                <div class="accordion-body">
                  <ul class="link-list">
                    <?php while (have_rows('documenti')) : the_row();
                      $file  = get_sub_field('file');
                      $label = get_sub_field('titolo') ?: ($file['title'] ?? 'Documento');
                      if (!$file || empty($file['url'])) continue;
                    ?>
                      <li>
                        <a class="list-item" href="<?php echo esc_url($file['url']); ?>">
                          <span class="list-item-title"><?php echo esc_html($label); ?></span>
                        </a>
                      </li>
                    <?php endwhile; ?>
                  </ul>
                </div>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>

      <!-- Sidebar dettagli (stile “scheda”) -->
      <aside class="col-lg-4 px-lg-4 py-3">
        <div class="card-wrapper card-space">
          <div class="card card-bg rounded shadow-sm">
            <div class="card-body">
              <h2 class="h5 card-title mb-3">In sintesi</h2>

              <dl class="row mb-0 small">
                <?php if (!empty($cup)) : ?>
                  <dt class="col-5">CUP</dt>
                  <dd class="col-7"><?php echo esc_html($cup); ?></dd>
                <?php endif; ?>

                <?php if (!empty($fmt_importo)) : ?>
                  <dt class="col-5">Importo</dt>
                  <dd class="col-7"><?php echo esc_html($fmt_importo); ?></dd>
                <?php endif; ?>

                <?php if (!empty($beneficiario)) : ?>
                  <dt class="col-5">Beneficiario</dt>
                  <dd class="col-7"><?php echo esc_html($beneficiario); ?></dd>
                <?php endif; ?>

                <?php if (!empty($avanzamento)) : ?>
                  <dt class="col-5">Avanzamento</dt>
                  <dd class="col-7"><?php echo esc_html($avanzamento); ?></dd>
                <?php endif; ?>
              </dl>
            </div>
          </div>
        </div>
      </aside>
    </div>

  </div>
</main>

<?php get_footer(); ?>
