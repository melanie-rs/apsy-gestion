/**
 * assets/js/layout.js
 * Abre y cierra el menú lateral en el celular.
 * El CSS hace el trabajo visual: acá solo se agrega o quita la clase
 * "menu-abierto" en <body>.
 */
(function () {
    'use strict';

    var cuerpo = document.body;
    var botonAbrir = document.querySelector('[data-menu-abrir]');
    var fondo = document.querySelector('[data-menu-cerrar]');

    if (!botonAbrir) { return; }   // páginas sin menú (login)

    function abrirMenu() {
        cuerpo.classList.add('menu-abierto');
        botonAbrir.setAttribute('aria-expanded', 'true');
    }

    function cerrarMenu() {
        cuerpo.classList.remove('menu-abierto');
        botonAbrir.setAttribute('aria-expanded', 'false');
    }

    botonAbrir.addEventListener('click', function () {
        if (cuerpo.classList.contains('menu-abierto')) { cerrarMenu(); } else { abrirMenu(); }
    });

    if (fondo) { fondo.addEventListener('click', cerrarMenu); }

    // Con la tecla Escape también se cierra
    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape') { cerrarMenu(); }
    });
})();
