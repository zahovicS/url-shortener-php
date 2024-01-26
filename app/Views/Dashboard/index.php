<?php view("Layouts.Header") ?>
<section class="container" id="container-app">
    <header class="app-header">
        <h3>Dashboard</h3>
        <a href="<?= route("dashboard/create") ?>" class="button is-transparent"><i class="fa-solid fa-plus mr-2"></i> Crear nuevo enlace</a>
    </header>
    <article>
        <form action="" class="slot-form-wide w-100">
            <div class="slot-input-form">
                <input type="text" name="username" id="search-url" class="input-short" placeholder="Buscar enlaces">
            </div>
        </form>
        <div class="container-results" id="container-results">
            <?php if ($hasLinks > 0) : ?>
                <div class="loading-container">
                    <p>Cargando tus URL...</p>
                    <progress class="progress is-large is-info mt-2" max="100">0%</progress>
                </div>
            <?php else : ?>
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <p>¡Crea tu primer enlace!</p>
                <a class="button is-dark-transparent" href="<?= route("dashboard/create") ?>">
                    Crear enlace
                </a>
            <?php endif ?>
        </div>
    </article>
</section>
<script src="<?= assets("js/urls.js")  ?>"></script>
<?php view("Layouts.Footer") ?>