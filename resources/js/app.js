import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import flatpickr from 'flatpickr';
import { Spanish } from 'flatpickr/dist/l10n/es.js';

import 'sweetalert2/dist/sweetalert2.min.css';
import 'flatpickr/dist/flatpickr.min.css';

window.Alpine = Alpine;
window.Swal = Swal;

// Configuración global de Flatpickr
flatpickr.localize(Spanish);

// Inicializar los selectores de fecha y hora
function inicializarSelectores() {
    // Campos de fecha
    document.querySelectorAll('input[type="date"]:not([data-native-picker])').forEach(input => {
        if (input._flatpickr) return;

        const valorInicial = input.value;

        const configuracion = {
            locale: Spanish,
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: false,
            disableMobile: true,
            defaultDate: valorInicial || null,
            altInputClass: `${input.className} barberking-picker`,

            onReady: function (fechas, fecha, instancia) {
                instancia.altInput.placeholder =
                    input.getAttribute('placeholder') || 'Selecciona una fecha';
            },

            onChange: function (fechas, fecha, instancia) {
                sincronizarAlpine(instancia.input);
            }
        };

        if (input.min) {
            configuracion.minDate = input.min;
        }

        if (input.max) {
            configuracion.maxDate = input.max;
        }

        flatpickr(input, configuracion);
    });

    // Campos de hora
    document.querySelectorAll('input[type="time"]:not([data-native-picker])').forEach(input => {
        if (input._flatpickr) return;

        flatpickr(input, {
            locale: {
                ...Spanish,
                amPM: ['a. m.', 'p. m.']
            },
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            altInput: true,
            altFormat: 'h:i K',
            altInputClass: `${input.className} barberking-picker`,
            time_24hr: false,
            minuteIncrement: 5,
            allowInput: false,
            disableMobile: true,
            defaultDate: input.value || null,

            onReady: function (fechas, hora, instancia) {
                instancia.altInput.placeholder =
                    input.getAttribute('placeholder') || 'hh:mm';
            },

            onChange: function (fechas, hora, instancia) {
                sincronizarAlpine(instancia.input);
            }
        });
    });
}

// Notificar los cambios a Alpine y a otros listeners
function sincronizarAlpine(input) {
    input.dispatchEvent(new Event('input', {
        bubbles: true
    }));

    input.dispatchEvent(new Event('change', {
        bubbles: true
    }));
}

// Inicializar Alpine
Alpine.start();

// Esperar a que Alpine termine de inicializar los formularios
requestAnimationFrame(() => {
    inicializarSelectores();
});