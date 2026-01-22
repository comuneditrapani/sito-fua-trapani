<?php
/**
 * template per la pagina di un progetto singolo
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

get_header();
?>

<main>
    <div class="container" id="main-container">
     <div class="row">
     <div class="col px-lg-4">
<?php get_template_part("template-parts/common/breadcrumb"); ?>
     </div>
     </div>
     <div class="row">
     <div class="col-lg-8 px-lg-4 py-lg-2">
        <h1><?php the_title(); ?></h1>
            <!-- Visualizza i campi personalizzati -->
<?php
            $descrizione = get_field('descrizione') ?? "";
            $beneficiario = get_field('beneficiario') ?? "";
            $rif_comune = get_field('rif_comune') ?? "";
            $azione_st = get_field('azione_st') ?? "";
            $azione_pr_fesr = get_field('azione_pr_fesr') ?? "";
            $obiettivo_specifico = get_field('obiettivo_specifico') ?? "";
            $azione_di_riferimento = get_field('azione_di_riferimento') ?? "";
            $settore_dintervento = get_field('settore_dintervento') ?? "";
            $importo_intervento = get_field('importo_intervento') ?? "";
            $cup = get_field('cup') ?? "";
            $avanzamento = get_field('avanzamento') ?? "";
            ?>
                <div><span>Descrizione: </span><span> <?= $descrizione ?> </span></div>
                <div><span>Beneficiario: </span><span> <?= $beneficiario ?> </span></div>
                <div><span>Rif. Comune: </span><span> <?= $rif_comune ?> </span></div>
                <div><span>Azione ST: </span><span> <?= $azione_st ?> </span></div>
                <div><span>Azione PR FESR: </span><span> <?= $azione_pr_fesr ?> </span></div>
                <div><span>Obiettivo specifico: </span><span> <?= $obiettivo_specifico ?> </span></div>
                <div><span>Azione di riferimento: </span><span> <?= $azione_di_riferimento ?> </span></div>
                <div><span>Settore d&quot;intervento: </span><span> <?= $settore_dintervento ?> </span></div>
                <div><span>Importointervento: </span><span> <?= $importo_intervento ?> </span></div>
                <div><span>CUP: </span><span> <?= $cup ?> </span></div>
                <div><span>Avanzamento: </span><span> <?= $avanzamento ?> </span></div>

     </div>
     </div>
 </div>
</main>

<?php
get_footer();
