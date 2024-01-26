const GLOBAL_URL = document.querySelector('meta[name="base_url"]').content;
const route = (to_url = "") => {
    return GLOBAL_URL + to_url;
}
const showSnackbar = (message = "Mensaje: ") => {
    // Crear el elemento div del snackbar
    var snackbar = document.createElement('div');
    snackbar.innerHTML = message;
    snackbar.classList.add('snackbar');
    document.body.appendChild(snackbar);

    // Agregar la clase 'show' para mostrar el snackbar
    snackbar.classList.add('show');

    // Eliminar el snackbar después de 3 segundos (ajusta según tus necesidades)
    setTimeout(function () {
        snackbar.classList.remove('show');
        // Espera a que termine la animación antes de eliminar el elemento
        setTimeout(function () {
            document.body.removeChild(snackbar);
        }, 100); // Ajusta el tiempo según la duración de la animación
    }, 2850);
}
document.addEventListener('DOMContentLoaded', () => {
    function openModal($el) {
        $el.classList.add('is-active');
    }

    function closeModal($el) {
        $el.classList.remove('is-active');
    }

    function closeAllModals() {
        (document.querySelectorAll('.modal') || []).forEach(($modal) => {
            closeModal($modal);
        });
    }

    // Add a click event on buttons to open a specific modal
    // (document.querySelectorAll('.js-modal-trigger') || []).forEach(($trigger) => {
    //     const modal = $trigger.dataset.target;
    //     const $target = document.getElementById(modal);

    //     $trigger.addEventListener('click', () => {
    //         openModal($target);
    //     });
    // });

    // Add a click event on various child elements to close the parent modal
    (document.querySelectorAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button') || []).forEach(($close) => {
        const $target = $close.closest('.modal');

        $close.addEventListener('click', () => {
            closeModal($target);
        });
    });

    // Add a keyboard event to close all modals
    document.addEventListener('keydown', (event) => {
        if (event.code === 'Escape') {
            closeAllModals();
        }
    });
    // Get all "navbar-burger" elements
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    // Add a click event on each of them
    $navbarBurgers.forEach(el => {
        el.addEventListener('click', () => {

            // Get the target from the "data-target" attribute
            const target = el.dataset.target;
            const $target = document.getElementById(target);

            // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
            el.classList.toggle('is-active');
            $target.classList.toggle('is-active');

        });
    });

    document.body.addEventListener('click', async function (evt) {
        //editar
        const $dropdown = evt.target.closest('.dropdown');
        // js-modal-trigger data-target="#modal-js-example"
        const $btnModals = evt.target.closest('.js-modal-trigger');
        // const $btnShowReferencia = evt.target.closest('.verReferencia');
        if ($dropdown) {
            evt.stopPropagation();
            $dropdown.classList.toggle('is-active');
        }
        if ($btnModals) {
            const modal = $btnModals.dataset.target;
            const $target = document.querySelector(modal);
            openModal($target);
        }
    }, false);
});