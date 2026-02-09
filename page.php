<?php
/*
 * Generic Page Template
 *
 * @package Design_Comuni_Italia
 */
global $post;
get_header();

?>
<main>
  <?php
    while ( have_posts() ) :
        the_post();
    $description = dci_get_meta('descrizione','_dci_page_',$post->ID);
  ?>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <?php get_template_part("template-parts/common/breadcrumb"); ?>
        <div class="cmp-hero">
          <section class="it-hero-wrapper bg-white align-items-start">
            <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
              <h1 class="text-black title-xxxlarge mb-2" data-element="page-name">
                <?php the_title()?>
              </h1>
              <p class="text-black titillium text-paragraph">
                <?php echo $description; ?>
              </p>
            </div>
          </section>
        </div>
      </div>
    </div>
    <div class="container ">
      <article class="article-wrapper">

        <div class="row variable-gutters">
          <div class="col-lg-12">
            <?php
              the_content();
            ?>
          </div>
        </div>

        <?php
              global $wpdb;

          $sub_query = "
          SELECT post_id, COALESCE(MAX(data), '1970-01-01') AS max_date
          FROM {$wpdb->prefix}postmeta
          WHERE meta_key = 'data' AND post_type='eventi_progetto'
          GROUP BY post_id
          ";

          // Recupera gli ID dei progetti che hanno eventi e la data
          $results = $wpdb->get_results($sub_query);

          // Crea un array di progetti con le rispettive date
          $ordered_projects = array();
          foreach ($results as $result) {
              $ordered_projects[$result->post_id] = $result->max_date;
          }

          // Aggiungi tutti i progetti senza eventi anche a questo array
          $all_projects_query = new WP_Query(array(
              'post_type' => 'progetto',
              'posts_per_page' => -1,
              'fields' => 'ids',
          ));

          // Includi i progetti senza eventi nell'array
          foreach ($all_projects_query->posts as $project_id) {
              if (!isset($ordered_projects[$project_id])) {
                  $ordered_projects[$project_id] = '1970-01-01'; // Data predefinita
              }
          }

          // Ordina i progetti in base alla data
          asort($ordered_projects);

          // Creiamo un array di IDs ordinati
          $sorted_ids = array_keys($ordered_projects);

          // Aggiorniamo args per includere i progetti ordinati
          $args['post__in'] = $sorted_ids;
          $args['orderby'] = 'post__in'; // Ordina in base all'array di IDs ordinati

          $query = new WP_Query($args);

          if ($query->have_posts()) {
              while ($query->have_posts()) {
                  $query->the_post();
                  // Visualizza i tuoi progetti
                  the_title('<h2>', '</h2>');
                  // Mostra altri dettagli
              }
              wp_reset_postdata();
          } else {
              echo 'Nessun progetto trovato.';
          }
        ?>

        <div class="row variable-gutters">
          <div class="col-lg-12">
            <?php
              if ( comments_open() || get_comments_number() ) :
                  comments_template();
              endif;
            ?>
          </div>
        </div>
        <div class="row variable-gutters">
          <div class="col-lg-12">
            <?php get_template_part( "template-parts/single/bottom" ); ?>
          </div><!-- /col-lg-9 -->
        </div><!-- /row -->

      </article>
    </div>

  </div>
  <?php get_template_part("template-parts/common/valuta-servizio"); ?>

  <?php
              endwhile; // End of the loop.
  ?>
</main>
<?php
    get_footer();
