<?php view("Layouts.Header") ?>
<section class="container is-flex is-justify-content-center is-align-items-center" id="container-app">
    <article class="contanier-hello">
        <h1 class="title-hello">👋 ¡¡Bienvenido a <strong>TINY URL</strong>!!</h1>
        <div class="buttons has-text-centered">
            <a class="button is-dark-transparent" href="<?= route("dashboard") ?>">
                <i class="fa-solid fa-scissors mr-2"></i> Recortar URL
            </a>
        </div>
    </article>
</section>
<?php view("Layouts.Footer") ?>