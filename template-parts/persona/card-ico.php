<?php
  global $persona_id;

  $persona = get_post($persona_id);
  $prefix = '_dci_persona_pubblica_';
  $descrizione_breve = dci_get_meta('descrizione_breve', $prefix, $persona_id);
  if($descrizione_breve && preg_match('/^sindaco di[ ]*(.*)$/i', $descrizione_breve, $trovati)) {
      $comune = trim($trovati[1]);
  }
  $immagine = dci_get_meta('immagine', $prefix, $persona_id);
  $incarichi_id = dci_get_meta('incarichi', $prefix, $persona_id);

  // se questo sindaco non ha una foto, usare l'immagine del comune
  if (empty($immagine) && isset($comune)) {
      $args = array(
          'name'        => sanitize_title($comune),
          'post_type'   => 'luogo',
          'posts_per_page' => 1,
      );

      $luoghi = get_posts($args);
      foreach($luoghi as $luogo) { // uno solo
          setup_postdata($luogo);
      }
      $immagine = dci_get_meta('immagine', '_dci_luogo_', $luogo->ID);
  }
?>

<div class="card-wrapper shadow-sm rounded border border-light">
  <div class="card no-after rounded">
    <a href="<?= get_permalink($persona->ID) ?>" class="" data-focus-mouse="false">
      <div class="card-body" style="padding: 6px 18px">
        <?php if ($immagine): ?>
        <div class="avatar size-xl" style="margin: 0 auto; display: block;">
          <img src="<?= esc_url($immagine)?>" class="aligncenter size-medium" alt="Description">
        </div>
        <?php endif; ?>
        <p style="text-align: center" class="card-title"><strong><?php echo $persona->post_title; ?></strong></p>
        <?php if(isset($comune)) { ?>
        <p style="text-align: center">(<?= $comune ?>)</p>
        <?php } else { ?>
          <p style="text-align: center"><?= $descrizione_breve ?></p>
        <?php } ?>
        <p class="titillium text-paragraph mb-0">
          <?php
            if ($incarichi_id) {
                foreach ($incarichi_id as $inc_id) {
                    echo ' - ' . get_the_title($inc_id);
                }
            }
          ?>
        </p>
      </div>
    </a>
  </div>
</div>
