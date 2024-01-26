<?php view("Layouts.Header") ?>
<section class="container is-flex is-justify-content-center is-align-items-center" id="container-app">
    <form action="<?= route("auth/login") ?>" method="POST" class="slot-form" autocomplete="off">
        <h1 class="slot-title-form">Iniciar sesión</h1>
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
        <div class="slot-input-form">
            <label class="input-label">Usuario:</label>
            <input type="text" name="username" class="input-short" placeholder="userGood2007" autocomplete="off">
        </div>
        <div class="slot-input-form">
            <label class="input-label">Contraseña:</label>
            <input type="password" name="password" class="input-short" placeholder="" autocomplete="off">
        </div>
        <div class="slot-button-form-wide">
            <button type="submit" class="button is-light-transparent">
                Iniciar sesión
            </button>
        </div>
        <div class="slot-text-other">
            <a href="<?= route("auth/register") ?>">¿No tienes una cuenta? Registrate aquí</a>
        </div>
    </form>
</section>
<?php view("Layouts.Footer") ?>