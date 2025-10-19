// Define o idioma global
flatpickr.localize(flatpickr.l10ns.pt);
// Define datas padrão
const hoje = new Date();
const tresMesesDepois = new Date();
tresMesesDepois.setMonth(hoje.getMonth() + 3);

// Datepickers
$("#basic-datepicker").flatpickr({
    locale: "pt",
    dateFormat: "d/m/Y"
});

$("#datetime-datepicker").flatpickr({
    enableTime: true,
    dateFormat: "d/m/Y H:i",
    locale: "pt"
});

$("#humanfd-datepicker").flatpickr({
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    locale: "pt"
});

$("#minmax-datepicker").flatpickr({
    minDate: "2020-01",
    maxDate: "2020-03",
    dateFormat: "d/m/Y",
    locale: "pt"
});

$("#disable-datepicker").flatpickr({
    onReady: function() {
        this.jumpToDate("2025-01");
    },
    disable: ["2025-01-10", "2025-01-21", "2025-01-30", new Date(2025, 4, 9)],
    dateFormat: "d/m/Y",
    locale: "pt"
});

$("#multiple-datepicker").flatpickr({
    mode: "multiple",
    dateFormat: "d/m/Y",
    locale: "pt"
});

$("#conjunction-datepicker").flatpickr({
    mode: "multiple",
    dateFormat: "d/m/Y",
    conjunction: " :: ",
    locale: "pt"
});

$("#range-datepicker").flatpickr({
    mode: "range",
    locale: "pt",
    dateFormat: "d/m/Y", // formato que o usuário vê
    onClose: function(selectedDates, dateStr, instance) {
        if (selectedDates.length === 2) {
            // selectedDates são objetos Date
            const start = selectedDates[0];
            const end = selectedDates[1];

            // Função para formatar DD/MM/YYYY -> YYYY-MM-DD
            function formatToUS(date) {
                const y = date.getFullYear();
                const m = ('0' + (date.getMonth() + 1)).slice(-2);
                const d = ('0' + date.getDate()).slice(-2);
                return `${y}-${m}-${d}`;
            }

            const rangeUS = formatToUS(start) + ' ' + formatToUS(end);
            
            // Envia para URL
            window.location.href = window.location.pathname + "?range=" + encodeURIComponent(rangeUS);
        }
    }
});

$("#inline-datepicker").flatpickr({
    inline: true,
    dateFormat: "d/m/Y",
    locale: "pt"
});

// Timepickers
$("#basic-timepicker").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    locale: "pt"
});

$("#24hours-timepicker").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    locale: "pt"
});

$("#minmax-timepicker").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    minDate: "16:00",
    maxDate: "22:30",
    locale: "pt"
});

$("#preloading-timepicker").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    defaultDate: "01:45",
    locale: "pt"
});
