<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item navbar-logo-text" href="<?= route("home") ?>">
            <i class="fa-solid fa-link mr-2"></i> TINY-URL
        </a>
    </div>
    <div class="navbar-end">
        <?php if (!existsUserSession()) : ?>
            <a class="navbar-item" href="<?= route("auth") ?>">
                <i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Iniciar Sesión
            </a>
        <?php else : ?>
            <?php $usuario = getSession("user"); ?>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" href="#!">
                    <i class="fa-brands fa-hashnode mr-2"></i> <?= $usuario->username ?>
                </a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="<?= route("dashboard/create") ?>">
                        <i class="fa-solid fa-plus mr-2"></i> Crear nuevo enlace
                    </a>
                    <a class="navbar-item" href="<?= route("dashboard") ?>">
                        <i class="fa-solid fa-list-ul mr-2"></i> Dashboard
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item" href="<?= route("auth/logout") ?>">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Cerrar sesión
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>