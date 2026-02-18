<?php
/**
 * Persona pubblica template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Design_Comuni_Italia
 */


get_header();
?>
    <main>
        <?php
        while ( have_posts() ) :
            the_post();
            $user_can_view_post = dci_members_can_user_view_post(get_current_user_id(), $post->ID);
            $descrizione_breve = dci_get_meta("descrizione_breve", $prefix, $post->ID);
			$incarico_id = dci_get_meta('incarichi', $prefix, $persona_id);
			$organizzazioni = dci_get_meta("organizzazioni", $prefix, $post->ID);
			$responsabile_di = dci_get_meta("responsabile_di", $prefix, $post->ID);
			$data_conclusione_incarico = dci_get_meta("data_conclusione_incarico", $prefix, $post->ID);
			$competenze = dci_get_meta("competenze", $prefix, $post->ID);
			$deleghe = dci_get_meta("deleghe", $prefix, $post->ID);
			$biografia = dci_get_meta("biografia", $prefix, $post->ID);
			$gallery = dci_get_meta("gallery", $prefix, $post->ID);
			$punti_contatto = dci_get_meta("punti_contatto", $prefix, $post->ID);
			$curriculum_vitae = dci_get_meta("curriculum_vitae", $prefix, $post->ID);
			$situazione_patrimoniale = dci_get_meta("situazione_patrimoniale", $prefix, $post->ID);
			$dichiarazione_redditi = dci_get_meta("dichiarazione_redditi", $prefix, $post->ID);
			$spese_elettorali = dci_get_meta("spese_elettorali", $prefix, $post->ID);
			$variazione_situazione_patrimoniale = dci_get_meta("variazione_situazione_patrimoniale", $prefix, $post->ID);
			$altre_cariche = dci_get_meta("altre_cariche", $prefix, $post->ID);			
			$more_info = dci_get_wysiwyg_field("ulteriori_informazioni", $prefix, $post->ID);
            
            
            /**
 * ============================================================
 * Trova la Persona Pubblica con incarico "Sindaco" per il Luogo corrente
 * ============================================================

 * - L'incarico è salvato come array serializzato o stringa con gli ID, per questo usiamo LIKE su '"ID"'.
 */

// ID del Comune/Luogo corrente
$luogo_id = get_the_ID();
//ID dell'incarico "Sindaco"
$ID_INCARICO_SINDACO = 370;

// Query WP per trovare la persona pubblica "Sindaco" collegata a questo luogo
$sindaco_q = new WP_Query([
  'post_type'      => 'persona_pubblica',
  'post_status'    => 'publish',
  'posts_per_page' => 1,
  'fields'         => 'ids',         // prestazioni: prendiamo solo gli ID
  'no_found_rows'  => true,          // prestazioni: niente paginazione
  'meta_query'     => [
    'relation' => 'AND',
    [
      'key'     => '_dci_persona_pubblica_luogo_riferimento',
      'value'   => (string) $luogo_id,
      'compare' => '=',
    ],
    [
      'key'     => '_dci_persona_pubblica_incarichi',
      'value'   => '"' . (int) $ID_INCARICO_SINDACO . '"',
      'compare' => 'LIKE',
    ],
  ],
]);
echo "HABEMUS SINDACO !!  -> ".$sindaco_id;
// Se trovato, prendiamo l'ID del post persona_pubblica (Sindaco)
$sindaco_id = (!empty($sindaco_q->posts)) ? (int) $sindaco_q->posts[0] : 0;

		
            ?>
            <div class="container px-4 my-4" id="main-container">
                <div class="row">
                    <div class="col px-lg-4">
                        <?php get_template_part("template-parts/common/breadcrumb"); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6 py-lg-2">

                        <div class="row g-3 align-items-start">
                        <!-- Testo (titolo + descrizione breve) -->
                        <div class="col-12 <?php echo $sindaco_id ? 'col-lg-7' : 'col-lg-12'; ?>">
                        <h1 data-audio><?php the_title(); ?></h1>

                            <?php if ($nome_alternativo) { ?>
                            <h2 class="h4 py-2" data-audio><?php echo $nome_alternativo; ?></h2>
                            <?php } ?>

                            <p data-audio>
                             <?php echo $descrizione_breve; ?>
                            </p>
                        </div>

                        <!-- Card-ico del Sindaco (solo se esiste) -->
                        <?php if ($sindaco_id) { ?>
                          <div class="col-12 col-lg-5">
                            <?php

                              $persona_id = $sindaco_id;
                              get_template_part("template-parts/persona/card-ico");
                            ?>
                          </div>
                        <?php } ?>
                      </div>

                    </div>
                    <div class="col-lg-3 offset-lg-1">
                        <?php 
                        $inline = true;
                        get_template_part('template-parts/single/actions');
                        ?>
                    </div>
                </div>
            </div>
		    <?php get_template_part('template-parts/single/image-large'); ?>
            <div class="container ">
				<div class="row border-top border-light row-column-border row-column-menu-left">
					<aside class="col-lg-4">
							<div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
								<nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="Indice della pagina" data-bs-navscroll>
									<div class="navbar-custom" id="navbarNavProgress">
										<div class="menu-wrapper">
											<div class="link-list-wrapper">
												<div class="accordion">
													<div class="accordion-item">
														<span class="accordion-header" id="accordion-title-one">
															<button
																class="accordion-button pb-10 px-3 text-uppercase"
																type="button"
																aria-controls="collapse-one"
																aria-expanded="true"
																data-bs-toggle="collapse"
																data-bs-target="#collapse-one"
															>INDICE DELLA PAGINA
																<svg class="icon icon-sm icon-primary align-top">
																	<use xlink:href="#it-expand"></use>
																</svg>
															</button>
														</span>
														<div class="progress">
															<div class="progress-bar it-navscroll-progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
														<div id="collapse-one" class="accordion-collapse collapse show" role="region" aria-labelledby="accordion-title-one">
															<div class="accordion-body">
																<ul class="link-list" data-element="page-index">
																	<li class="nav-item">
																		<a class="nav-link" href="#incarico">
																		<span>Incarico</span>
																		</a>
																	</li>
																	<?php if( $organizzazioni){ ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#organizzazioni">
																		<span>Organizzazioni</span>
																		</a>
																	</li>
																	<?php } ?>
																	<?php if( $competenze ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#competenze">
																		<span>Competenze</span>
																		</a>
																	</li>
																	<?php } ?>
																	<?php if( $deleghe ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#deleghe">
																		<span>Deleghe</span>
																		</a>
																	</li>
																	<?php } ?>
																	<?php if( $biografia ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#biografia">
																		<span>Biografia</span>
																		</a>
																	</li>
																	<?php } ?>
																	<?php if( $punti_contatto ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#contatti">
																		<span>Contatti</span>
																	</a>
																	</li>
																	<?php } ?>
																	<?php if( $curriculum_vitaerimuovere == 2 /*modificare per mostrare CV*/ ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#curriculum-vitae">
																		<span>Curriculum vitae</span>
																	</a>
																	</li>
																	<?php } ?>
																	<?php if( $situazione_patrimoniale ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#situazione-patrimoniale">
																		<span>Situazione patrimoniale</span>
																	</a>
																	</li>
																	<?php } ?>
																	<?php if( $dichiarazione_redditi ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#dichiarazione-redditi">
																		<span>Dichiarazione dei redditi</span>
																	</a>
																	</li>
																	<?php } ?>
																	<?php if( $spese_elettorali ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#spese-elettorali">
																		<span>Spese elettorali</span>
																	</a>
																	</li>
																	<?php } ?>
																	<?php if( $variazione_situazione_patrimoniale ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#variazione-situazione-patrimoniale">
																		<span>Variazione situazione patrimoniale</span>
																	</a>
																	</li>
																	<?php } ?>
																	<?php if( $altre_cariche ) { ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#altre-cariche">
																		<span>Altre cariche</span>
																	</a>
																	</li>
																	<?php } ?>
																	<?php if ( $more_info ) {  ?>
																	<li class="nav-item">
																		<a class="nav-link" href="#ulteriori-informazioni">
																		<span>Ulteriori informazioni</span>
																		</a>
																	</li>
																	<?php } ?>
																</ul>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</nav>
							</div>
						</aside>
						<section class="col-lg-8 it-page-sections-container border-light">
									
						<?php if( $incarico_id ) { ?>
						<article class="it-page-section anchor-offset" data-audio>
							<h4 id="incarico">Incarico</h4>
							<div class="richtext-wrapper lora">
								<?php
									foreach ($incarico_id as $inc_id) {
										$incarico = get_the_title($inc_id);
										echo $incarico = get_the_title($inc_id);
									}
								?>
							</div>
                    	</article>	
						<?php } ?>
						<?php 
							if( $incarico == "Sindaco" )
								{ $tipo_incarico = "Politico";}
							else
								{ $tipo_incarico = "Amministrativo";}
							?>
						<article class="it-page-section anchor-offset mt-5" data-audio>
							<h4 id="tipo-incarico">Tipo di incarico</h4>
							<div class="richtext-wrapper lora">
								<?php
									echo $tipo_incarico;
								?>
							</div>
                    	</article>	
							
						<article class="it-page-section anchor-offset mt-5"  data-audio>
                        <h4 id="organizzazioni">Organizzazioni</h4>
                        <div class="row">
                        <div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal">
                            <?php foreach ($organizzazioni as $uo_id) {
                                $with_border = true;
                                get_template_part("template-parts/unita-organizzativa/card");
                            } ?>
                        </div>
						</div>
						</article>
							
						<?php if( $competenze ) { ?>
						<article class="it-page-section anchor-offset mt-5" data-audio>
							<h4 id="competenze">Competenze</h4>
							<div class="richtext-wrapper lora">
								<?php echo $competenze; ?>
							</div>
                    	</article>	
						<?php } ?>
							
						<?php if( $deleghe ) { ?>
						<article class="it-page-section anchor-offset mt-5" data-audio>
							<h4 id="deleghe">Deleghe</h4>
							<div class="richtext-wrapper lora">
								<?php echo $deleghe; ?>
							</div>
                    	</article>	
						<?php } ?>
							
						<?php if( $biografia ) { ?>
						<article class="it-page-section anchor-offset mt-5" data-audio>
							<h4 id="biografia">Biografia</h4>
							<div class="richtext-wrapper lora">
								<?php echo $biografia; ?>
							</div>
                    	</article>	
						<?php } ?>
							
						<?php if( is_array($punti_contatto) && count($punti_contatto) ) { ?>	
						<article class="it-page-section anchor-offset mt-5" data-audio>
							<h4 id="contatti" class="mb-3">Contatti</h4>
							<?php foreach ($punti_contatto as $pc_id) {
								get_template_part('template-parts/single/punto-contatto');
							} ?>
						</article>
						<?php } ?>
							
						<?php if( $curriculum_vitaerimuovere == 2 /*modificare per mostrare CV*/ ) { ?>
						<article class="it-page-section anchor-offset mt-5">
							<h4 id="curriculum-vitae">Curriculum vitae</h4>
							<div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal">
								<?php foreach ($curriculum_vitae as $all_url) {
									$all_id = attachment_url_to_postid($all_url);
									$allegato = get_post($all_id);
								?>
								<div class="card card-teaser shadow-sm p-4 mt-3 rounded border border-light flex-nowrap">
									<svg class="icon" aria-hidden="true">
									<use
										xlink:href="#it-clip"
									></use>
									</svg>
									<div class="card-body">
									<h5 class="card-title">
										<a class="text-decoration-none" href="<?php echo get_the_guid($allegato); ?>" aria-label="Scarica l'allegato <?php echo $allegato->post_title; ?>" title="Scarica l'allegato <?php echo $allegato->post_title; ?>">
											<?php echo $allegato->post_title; ?>
										</a>
									</h5>
									</div>
								</div>
								<?php } ?>
							</div>
						</article>
						<?php } ?>
						
						<?php if( $situazione_patrimoniale ) { ?>
						<article class="it-page-section anchor-offset mt-5" data-audio>
							<h4 id="situazione-patrimoniale">Situazione patrimoniale</h4>
							<div class="richtext-wrapper lora">
								<?php echo $situazione_patrimoniale; ?>
							</div>
                    	</article>	
						<?php } ?>
						
							
							
						<?php if( is_array($dichiarazione_redditi) && count($dichiarazione_redditi) ) { ?>
						<article class="it-page-section anchor-offset mt-5" data-audio>
						<h4 id="dichiarazione-redditi">Dichiarazione dei redditi</h4>
						<div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal">
							<?php foreach ($dichiarazione_redditi as $all_url) {
								$all_id = attachment_url_to_postid($all_url);
								$dichiarazione = get_post($all_id);
							?>
							<div class="card card-teaser shadow-sm p-4 mt-3 rounded border border-light flex-nowrap">
								<svg class="icon" aria-hidden="true">
								<use
									xlink:href="#it-clip"
								></use>
								</svg>
								<div class="card-body">
								<h5 class="card-title">
									<a class="text-decoration-none" href="<?php echo get_the_guid($dichiarazione); ?>" aria-label="Scarica l'allegato <?php echo $dichiarazione->post_title; ?>" title="Scarica l'allegato <?php echo $dichiarazione->post_title; ?>">
										<?php echo $dichiarazione->post_title; ?>
									</a>
								</h5>
								</div>
							</div>
							<?php } ?>
						</div>
						</article>
						<?php } ?>
						
						<?php if( is_array($spese_elettorali) && count($spese_elettorali) ) { ?>
						<article class="it-page-section anchor-offset mt-5" data-audio>
						<h4 id="spese-elettorali">Spese elettorali</h4>
						<div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal">
							<?php foreach ($spese_elettorali as $all_url) {
								$all_id = attachment_url_to_postid($all_url);
								$spesa_elettorale = get_post($all_id);
							?>
							<div class="card card-teaser shadow-sm p-4 mt-3 rounded border border-light flex-nowrap">
								<svg class="icon" aria-hidden="true">
								<use
									xlink:href="#it-clip"
								></use>
								</svg>
								<div class="card-body">
								<h5 class="card-title">
									<a class="text-decoration-none" href="<?php echo get_the_guid($spesa_elettorale); ?>" aria-label="Scarica l'allegato <?php echo $spesa_elettorale->post_title; ?>" title="Scarica l'allegato <?php echo $spesa_elettorale->post_title; ?>">
										<?php echo $spesa_elettorale->post_title; ?>
									</a>
								</h5>
								</div>
							</div>
							<?php } ?>
						</div>
						</article>
						<?php } ?>
												
						<?php if( is_array($variazione_situazione_patrimoniale) && count($variazione_situazione_patrimoniale) ) { ?>
						<article class="it-page-section anchor-offset mt-5" data-audio>
						<h4 id="variazione-situazione-patrimoniale">Variazione situazione patrimoniale</h4>
						<div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal">
							<?php foreach ($variazione_situazione_patrimoniale as $all_url) {
								$all_id = attachment_url_to_postid($all_url);
								$variazione_sp = get_post($all_id);
							?>
							<div class="card card-teaser shadow-sm p-4 mt-3 rounded border border-light flex-nowrap">
								<svg class="icon" aria-hidden="true">
								<use
									xlink:href="#it-clip"
								></use>
								</svg>
								<div class="card-body">
								<h5 class="card-title">
									<a class="text-decoration-none" href="<?php echo get_the_guid($variazione_sp); ?>" aria-label="Scarica l'allegato <?php echo $variazione_sp->post_title; ?>" title="Scarica l'allegato <?php echo $variazione_sp->post_title; ?>">
										<?php echo $variazione_sp->post_title; ?>
									</a>
								</h5>
								</div>
							</div>
							<?php } ?>
						</div>
						</article>
						<?php } ?>
													
						<?php if( is_array($altre_cariche) && count($altre_cariche) ) { ?>
						<article class="it-page-section anchor-offset mt-5" data-audio>
						<h4 id="altre-cariche">Altre cariche</h4>
						<div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal">
							<?php foreach ($altre_cariche as $all_url) {
								$all_id = attachment_url_to_postid($all_url);
								$altra_carica = get_post($all_id);
							?>
							<div class="card card-teaser shadow-sm p-4 mt-3 rounded border border-light flex-nowrap">
								<svg class="icon" aria-hidden="true">
								<use
									xlink:href="#it-clip"
								></use>
								</svg>
								<div class="card-body">
								<h5 class="card-title">
									<a class="text-decoration-none" href="<?php echo get_the_guid($altra_carica); ?>" aria-label="Scarica l'allegato <?php echo $altra_carica->post_title; ?>" title="Scarica l'allegato <?php echo $altra_carica->post_title; ?>">
										<?php echo $altra_carica->post_title; ?>
									</a>
								</h5>
								</div>
							</div>
							<?php } ?>
						</div>
						</article>
						<?php } ?>
													
						<?php if ($more_info) { ?>	
						<article class="it-page-section anchor-offset mt-5" data-audio>
							<h4 id="ulteriori-informazioni">Ulteriori informazioni</h4>
							<div class="richtext-wrapper lora">
								<?php echo $more_info; ?>
							</div>
                    	</article>
						<?php } ?>
							
						<article class="article-wrapper" data-audio>
							<div class="row">
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
<?php get_footer(); ?>
