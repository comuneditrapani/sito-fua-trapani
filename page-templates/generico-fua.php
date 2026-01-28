<?php
/*
Template Name: Generico FUA
*/

get_header();

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
      <div class="col-lg-10 px-lg-4 py-3">

        <header class="mb-4">
          <h1 class="mb-2"><?php the_title(); ?></h1>
        </header>

        <?php
              while (have_posts()) {
                  the_post();
                  the_content();
              }
            ?>
          <section id="head-section">
            <?php
              if (is_front_page()) {
                  get_template_part("template-parts/luogo/tutti-luoghi");
              }
              get_template_part("template-parts/home/notizie");
            ?>
        </section>
      </div>

    </div>
  </div>
</main>

<?php get_footer(); ?>
