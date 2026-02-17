<?php
  global $persona_id;

  $persona = get_the_title($persona_id);
  $prefix = '_dci_persona_pubblica_';
  $descrizione_breve = dci_get_meta('descrizione_breve', $prefix, $persona_id);
  $immagine = dci_get_meta('immagine', $prefix, $persona_id);
  $is_sindaco = false;
  $incarichi = dci_get_meta('incarichi', $prefix, $persona_id);
  if (is_array($incarichi)) foreach ($incarichi as $inc_id) {
      $is_sindaco |= preg_match('/^sindaco/i', get_the_title($inc_id));
  }

  $comune_id = dci_get_meta('luogo_riferimento', $prefix, $persona_id);
  $comune = get_the_title($comune_id);
  // se è un sindaco e non ha una foto, usare l'immagine del comune
  if($is_sindaco && empty($immagine)) {
      $immagine = dci_get_meta('immagine', '_dci_luogo_', $comune_id);
  }
?>

<div class="card-wrapper shadow-sm rounded border border-light">
  <div class="card no-after rounded">
    <a href="<?= get_permalink($persona_id) ?>" class="" data-focus-mouse="false">
      <div class="card-body" style="padding: 6px 18px">
        <?php if ($immagine): ?>
        <div class="avatar size-xl" style="margin: 0 auto; display: block;">
          <img src="<?= esc_url($immagine)?>" class="aligncenter size-medium" alt="Description">
        </div>
        <?php endif; ?>
        <p style="text-align: center" class="card-title"><strong><?php echo $persona; ?></strong></p>
        <?php if($is_sindaco) { ?>
        <p style="text-align: center">(<?= $comune ?>)</p>
        <?php } else { ?>
        <p style="text-align: center"><?= $descrizione_breve ?></p>
        <p style="text-align: center">(<?= $comune ?>)</p>
        <?php } ?>
      </div>
    </a>
  </div>
</div>
