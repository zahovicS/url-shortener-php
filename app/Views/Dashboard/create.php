<?php view("Layouts.Header") ?>
<section class="container" id="container-app">
    <header class="app-header">
        <h3>Crea tu enlace</h3>
    </header>
    <article>
        <form action="<?= route("dashboard/save") ?>" method="POST" class="slot-form-wide w-100">
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
            $url_link = "";
            $url_slug = "";
            $url_descripcion = "";
            if (getFlash("status") == "Error" && getFlash("sendData")) {
                $url_link  = getFlash("sendData")["url_link"];
                $url_slug  = getFlash("sendData")["url_slug"];
                $url_descripcion  = getFlash("sendData")["url_descripcion"];
            }
            ?>
            <div class="slot-input-form">
                <label class="input-label">Ingresa tu url:</label>
                <input type="text" name="url_link" value="<?= $url_link ?>" class="input-short" placeholder="Https://">
            </div>
            <div class="slot-input-form">
                <label class="input-label">Slug customizado (opcional):</label>
                <label class="input-label-link"><?= route("url/[custom-slug]") ?></label>
                <input type="text" maxlength="6" name="url_slug" value="<?= $url_slug ?>" class="input-short" placeholder="Slug customizado">
            </div>
            <div class="slot-input-form">
                <label class="input-label">Descripcion (opcional):</label>
                <textarea class="input-short" name="url_descripcion" rows="4"><?= $url_descripcion ?></textarea>
            </div>
            <div class="slot-button-form">
                <a class="button is-dark-transparent mr-2" href="<?= route("dashboard") ?>">
                    <i class="fa-solid fa-rotate-left mr-2"></i> Regresar
                </a>
                <button type="submit" class="button is-light-transparent">
                    <i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Crear mi enlace
                </button>
            </div>
        </form>
    </article>
</section>
<?php view("Layouts.Footer") ?>