{{--
    Latido de sesión para los paneles.

    Mantiene viva la sesión mientras la pestaña siga abierta (aunque esté en
    segundo plano) y, si la sesión ya no es válida, lleva al login en lugar de
    dejar al usuario en una pantalla de error.
--}}
<div id="sesion-activa"
     data-url="{{ route('sesion.activa') }}"
     data-login="{{ route('login') }}"
     hidden></div>

<script>
    (function () {
        var config = document.getElementById('sesion-activa');
        if (!config) {
            return;
        }

        var urlLatido = config.dataset.url;
        var urlLogin = config.dataset.login;
        var INTERVALO = 5 * 60 * 1000; // 5 minutos
        var MINIMO_ENTRE_ENVIOS = 60 * 1000;
        var ultimoEnvio = 0;
        var redirigiendo = false;

        function irAlLogin() {
            if (redirigiendo) {
                return;
            }
            redirigiendo = true;

            var destino = window.location.pathname + window.location.search;
            var separador = urlLogin.indexOf('?') === -1 ? '?' : '&';

            // Se usa replace para que el botón "atrás" no vuelva a la página
            // que ya no puede mostrarse por falta de sesión.
            window.location.replace(
                urlLogin + separador + 'expirada=1&redirect=' + encodeURIComponent(destino)
            );
        }

        function latir(forzar) {
            if (redirigiendo) {
                return;
            }

            var ahora = Date.now();

            if (!forzar && ahora - ultimoEnvio < MINIMO_ENTRE_ENVIOS) {
                return;
            }

            ultimoEnvio = ahora;

            fetch(urlLatido, {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(function (respuesta) {
                if (respuesta.status === 401) {
                    irAlLogin();
                }
            }).catch(function () {
                // Sin conexión: se reintenta en el siguiente ciclo.
                ultimoEnvio = 0;
            });
        }

        // Al volver a la pestaña se comprueba de inmediato (si pasó más de
        // un minuto desde el último latido).
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                latir(false);
            }
        });

        window.addEventListener('focus', function () {
            latir(false);
        });

        window.setInterval(function () {
            latir(false);
        }, INTERVALO);
    })();
</script>
