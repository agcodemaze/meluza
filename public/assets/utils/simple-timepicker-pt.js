    document.addEventListener('DOMContentLoaded', function() {
        var datasBloqueadas = [
            "2025-09-15",
            "2025-09-20",
            "2025-09-22"
        ];

        flatpickr("#basic-datepicker", {
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: datasBloqueadas, 
            locale: {
                weekdays: {
                    shorthand: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
                    longhand: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado']
                },
                months: {
                    shorthand: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
                    longhand: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']
                },
                firstDayOfWeek: 1,
                rangeSeparator: ' até ',
                weekAbbreviation: 'Sem',
                scrollTitle: 'Scroll para aumentar',
                toggleTitle: 'Clique para alternar',
                time_24hr: true,
                "firstDayOfWeek": 1
            }, 
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                var data = dayElem.dateObj.toISOString().slice(0, 10);
                if (datasBloqueadas.includes(data)) {
                    dayElem.classList.add("data-bloqueada");
                    dayElem.style.backgroundColor = "#ffcccc"; // vermelho
                }
            }
        });
    });