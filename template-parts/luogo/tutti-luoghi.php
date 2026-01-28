<?php
global $the_query, $load_posts, $load_card_type;

    $load_posts = 12;
    $max_posts = isset($_GET['max_posts']) ? $_GET['max_posts'] : $load_posts;
    $query = isset($_GET['search']) ? dci_removeslashes($_GET['search']) : null;
    $args = array(
        's' => $query,
        'posts_per_page' => $max_posts,
        'post_type'      => 'luogo',
		'post_status'    => 'publish',
        'orderby'        => 'post_title',
        'order'          => 'ASC'
    );
    $the_query = new WP_Query( $args );

    $posts = $the_query->posts;

?>


<div class="bg-grey-card py-5">
    <form role="search" id="search-form" method="get" class="search-form">
        <button type="submit" class="d-none"></button>
        <div class="container">
            <h2 class="title-xxlarge mb-4">
                Gli 11 Comuni
            </h2>
            <div class="row g-4" id="load-more">
                <?php
                foreach ( $posts as $post ) {
                    $load_card_type = 'luogo';
                    get_template_part('template-parts/luogo/card-ico');
                }
                ?>
            </div>
        </div>
    </form>
</div>
<?php wp_reset_query(); ?>
