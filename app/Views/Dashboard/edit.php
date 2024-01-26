<?php view("Layouts.Header") ?>
<section class="container" id="container-app">
    <header class="app-header">
        <h3>Edita tu enlace: /url/<?= $url->slug ?></h3>
    </header>
    <article>
        <form action="<?= route("dashboard/update/{$url->id}") ?>" method="POST" class="slot-form-wide w-100">
            <?php if (getFlash("status")) : ?>
                <article class="message <?= getFlash("color") ?>">
                    <div class="message-header">
                        <p><?= getFlash("status") ?></p>
                    </div>
                    <div class="message-body">
                        <?= getFlash("message") ?>
                    </div>
                </article>
            <?php endif; ?>
            <?php
            // OLD VALUES
            $url_link = $url->url;
            $url_descripcion = $url->description;
            ?>
            <div class="slot-input-form">
                <label class="input-label">Ingresa tu url:</label>
                <input type="text" name="url_link" value="<?= $url_link ?>" class="input-short" placeholder="Https://">
            </div>
            <div class="slot-input-form">
                <label class="input-label">Descripcion (opcional):</label>
                <textarea class="input-short" name="url_descripcion" rows="4"><?= $url_descripcion ?></textarea>
            </div>
            <div class="notification is-warning">
                Recuerda que esta acción es irreversible.
            </div>
            <div class="slot-button-form">
                <a class="button is-dark-transparent mr-2" href="<?= route("dashboard") ?>">
                    <i class="fa-solid fa-rotate-left mr-2"></i> Regresar
                </a>
                <button type="submit" class="button is-light-transparent">
                    <i class="fa-solid fa-pen mr-2"></i> Editar mi enlace
                </button>
            </div>
        </form>
    </article>
</section>
<?php view("Layouts.Footer") ?>