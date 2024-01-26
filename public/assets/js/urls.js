const $containerUrls = document.querySelector("#container-results")
const $inputSearchUrl = document.querySelector("#search-url")
const buildnoResultContainer = () => {
    const html_no_result = `<i class="fa-solid fa-wand-magic-sparkles"></i>
    <p>¡Crea tu primer enlace!</p>
    <a class="button is-dark-transparent" href="${route("dashboard/create")}">
        Crear enlace
    </a>`;
    $containerUrls.innerHTML = html_no_result;
}
const buildContainerUrl = (url = {}) => {
    return `<div class="column is-full-mobile is-half-tablet is-one-third-desktop">
                    <div class="card w-100">
                    <div class="card-content">
                        <div class="is-flex is-justify-content-space-between">
                            <p class="title mb-2">
                                <span>/url/${url.slug}</span>
                                <button data-copy="${url.full_url}" type="button" class="button is-transparent btnCopy"><i class="fa-solid fa-copy fa-lg"></i></button>
                            </p>
                            <div class="dropdown">
                                <div class="dropdown-trigger">
                                    <button class="button" aria-haspopup="true" aria-controls="dropdown-menu3">
                                    <span>Opciones</span>
                                    <span class="icon is-small">
                                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                                    </span>
                                    </button>
                                </div>
                                <div class="dropdown-menu" id="dropdown-menu3" role="menu">
                                    <div class="dropdown-content">
                                        <a href="#!" data-copy="${url.full_url}" class="dropdown-item btnCopy">
                                            <i class="fa-solid fa-copy mr-2"></i> Copiar
                                        </a>
                                        <a href="${route(`dashboard/edit/${url.id}`)}" class="dropdown-item"> 
                                            <i class="fa-solid fa-pencil mr-2"></i>Editar
                                        </a>
                                        <a href="#!" data-id="${url.id}" data-slug="${url.slug}" class="dropdown-item btnDelete">
                                            <i class="fa-solid fa-trash mr-2"></i> Borrar
                                        </a>
                                    </div>
                                </div>
                                </div>
                        </div>
                        <p class="subtitle mb-3">
                        ${url.og_url}
                        </p>
                        <p class="description">
                            ${url.description ?? "Sin descripción"}
                        </p>
                    </div>
                </div>
            </div>`;
}
const showResultURL = (urls = [], delay = true) => {
    let resultHTML = `<div class="w-100"><div class="columns is-flex-wrap-wrap">`;
    for (const url of urls) {
        resultHTML += buildContainerUrl(url);
    }
    resultHTML += `</div></div>`;
    setTimeout(() => {
        $containerUrls.innerHTML = resultHTML;
    }, delay ? 1000 : 200);
}
const getUrls = async () => {
    try {
        const request = await axios({
            url: route("dashboard/list"),
            method: "POST"
        })
        const response = request.data;
        if (response.status == "success") {
            const urls = response.urls;
            if (urls.length > 0) {
                showResultURL(urls);
            }else{
                buildnoResultContainer();
            }
        }
    } catch (error) {
        Swal.fire({
            title: error,
            icon: "error",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    getUrls();
    document.body.addEventListener('click', async function (evt) {
        //editar
        const $btnCopyUrl = evt.target.closest('.btnCopy');
        const $btnDeleteUrl = evt.target.closest('.btnDelete');
        // const $btnShowReferencia = evt.target.closest('.verReferencia');
        if ($btnCopyUrl) {
            $url = $btnCopyUrl.dataset.copy;
            navigator.clipboard.writeText($url);
            showSnackbar("✍️ Copiado a tu portapapeles")
        }
        if ($btnDeleteUrl) {
            const id_url = $btnDeleteUrl.dataset.id;
            const slug = $btnDeleteUrl.dataset.slug;
            Swal.fire({
                title: `Borrar: ${slug}`,
                html: `<div class="notification is-danger">
                    <i class="fa-solid fa-circle-exclamation fa-lg mr-2"></i>Recuerda que esta acción es irreversible.
                </div>`,
                customClass: {
                    title: "has-text-left",
                    confirmButton: "button is-danger",
                },
                confirmButtonText: '<i class="fa-solid fa-trash"></i> Borrar',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    try {
                        const request = await axios({
                            url: route(`dashboard/delete/${id_url}`),
                            method: "GET"
                        });
                        const response = request.data;
                        if (response.status == "error") {
                            return Swal.showValidationMessage(`Request failed: ${response.message}`);
                        }
                        return response;
                    } catch (error) {
                        return Swal.showValidationMessage(`Request failed: ${error}`);
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value.status == "success") {
                    getUrls();
                    Swal.fire({
                        title: result.value.message,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });
                }
            });
        }
    }, false);
    $inputSearchUrl.addEventListener("keyup", async function () {
        const search = this.value != "" ? encodeURI(this.value) : "all";
        try {
            const request = await axios({
                url: route(`dashboard/search/${search}`),
                method: "GET",
            })
            const response = request.data;
            if (response.status == "success") {
                const urls = response.urls;
                if (urls.length > 0) {
                    showResultURL(urls, false);
                } else {
                    buildnoResultContainer();
                }
            }
        } catch (error) {
            Swal.fire({
                title: error,
                icon: "error",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
            });
        }
    })
}, false);