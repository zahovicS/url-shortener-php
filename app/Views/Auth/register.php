<?php view("Layouts.Header") ?>
<section class="container is-flex is-justify-content-center is-align-items-center" id="container-app">
    <form action="<?= route("auth/save") ?>" method="POST" class="slot-form" autocomplete="off">
        <h1 class="slot-title-form">Registrarse</h1>
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
            $name = "";
            $username = "";
            $email = "";
            if (getFlash("status") == "Error" && getFlash("sendData")) {
                $name = getFlash("sendData")["name"];
                $username = getFlash("sendData")["username"];
                $email = getFlash("sendData")["email"];
            }
            ?>
        <div class="slot-input-form">
            <label class="input-label">Tu nombre (opcional):</label>
            <input type="text" name="name" class="input-short" placeholder="Jhon Doe" value="<?= $name ?>" autocomplete="off">
        </div>
        <div class="slot-input-form">
            <label class="input-label">Usuario:</label>
            <input type="text" name="username" class="input-short" maxlength="10" placeholder="userGood2007" value="<?= $username ?>" autocomplete="off">
        </div>
        <div class="slot-input-form">
            <label class="input-label">Email:</label>
            <input type="text" name="email" class="input-short" placeholder="email@example.com" value="<?= $email ?>" autocomplete="off">
        </div>
        <div class="slot-input-form">
            <label class="input-label">Contraseña:</label>
            <input type="password" name="password" class="input-short" placeholder="" autocomplete="off">
        </div>
        <div class="slot-button-form-wide">
            <button type="submit" class="button is-light-transparent">
                Registrarse
            </button>
        </div>
        <div class="slot-text-other">
            <a href="<?= route("auth") ?>">¿Ya tienes una cuenta? inicia sesión aquí</a>
        </div>
    </form>
</section>
<?php view("Layouts.Footer") ?>