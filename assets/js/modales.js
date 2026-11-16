/**
 * assets/js/modales.js
 * Abre y cierra las ventanas modales (elemento <dialog>) y las rellena con
 * los datos del botón que las abrió.
 *
 * Convención de atributos HTML:
 *   Botón que abre:   data-abrir-modal="id-del-modal"  +  data-<clave>="valor"
 *   Dentro del modal: data-rellenar="<clave>"           (recibe el valor)
 *   Cerrar:           data-cerrar-modal
 *   Abrir al cargar:  data-abrir-al-cargar              (lo pone PHP cuando hay errores)
 *
 * Ejemplo: el botón "editar" lleva data-nombre="Lucía"; el input del modal con
 * data-rellenar="nombre" recibe ese valor.
 */
(function () {
    'use strict';

    /** Borra los mensajes de error que haya quedado de una apertura anterior. */
    function limpiarErrores(modal) {
        modal.querySelectorAll('.campo--error').forEach(function (campo) {
            campo.classList.remove('campo--error');
        });
        modal.querySelectorAll('.campo__error').forEach(function (mensaje) {
            mensaje.remove();
        });
    }

    /** Pone en cada elemento [data-rellenar] el valor que trae el botón. */
    function rellenar(modal, boton) {
        modal.querySelectorAll('[data-rellenar]').forEach(function (elemento) {
            var clave = elemento.getAttribute('data-rellenar');
            var esCampo = (elemento.tagName === 'INPUT' || elemento.tagName === 'TEXTAREA');
            var valor = boton.dataset[clave];            // data-nombre → dataset.nombre

            if (valor !== undefined) {
                if (esCampo) { elemento.value = valor; } else { elemento.textContent = valor; }
            } else if (esCampo) {
                elemento.value = '';                     // sin dato → campo vacío
            }
        });
    }

    function abrirModal(boton) {
        var modal = document.getElementById(boton.getAttribute('data-abrir-modal'));
        if (!modal) { return; }

        limpiarErrores(modal);
        rellenar(modal, boton);
        modal.showModal();                               // método nativo de <dialog>
    }

    // Un solo "escuchador" para toda la página (delegación de eventos)
    var presionoElFondo = false;

    document.addEventListener('mousedown', function (evento) {
        presionoElFondo = (evento.target.tagName === 'DIALOG');   // el fondo oscuro cuenta como el <dialog>
    });

    document.addEventListener('click', function (evento) {
        var abrir = evento.target.closest('[data-abrir-modal]');
        if (abrir) { abrirModal(abrir); return; }

        var cerrar = evento.target.closest('[data-cerrar-modal]');
        if (cerrar) { cerrar.closest('dialog').close(); return; }

        // Clic en el fondo oscuro (y no un arrastre que empezó dentro del modal)
        if (evento.target.tagName === 'DIALOG' && presionoElFondo) {
            evento.target.close();
        }
    });

    // Si PHP encontró errores en el formulario, el modal se abre solo
    document.querySelectorAll('[data-abrir-al-cargar]').forEach(function (modal) {
        modal.showModal();
    });
})();
