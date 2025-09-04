$(document).ready(function() {
    "use strict";

    // -----------------------------
    // Tabela básica
    // -----------------------------
    $("#basic-datatable").DataTable({
        keys: true,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    // -----------------------------
    // Tabela com botões
    // -----------------------------
    var dtButtons = $("#datatable-buttons").DataTable({
        lengthChange: false,
        buttons: ["copy", "print"],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });
    dtButtons.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");

    // -----------------------------
    // Tabela com seleção múltipla
    // -----------------------------
    $("#selection-datatable").DataTable({
        select: { style: "multi" },
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    // -----------------------------
    // Página alternativa
    // -----------------------------
    $("#alternative-page-datatable").DataTable({
        pagingType: "full_numbers",
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    // -----------------------------
    // Scroll vertical
    // -----------------------------
    $("#scroll-vertical-datatable").DataTable({
        scrollY: "350px",
        scrollCollapse: true,
        paging: false,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    // -----------------------------
    // Scroll horizontal
    // -----------------------------
    $("#scroll-horizontal-datatable").DataTable({
        scrollX: true,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    // -----------------------------
    // Cabeçalho complexo
    // -----------------------------
    $("#complex-header-datatable").DataTable({
        columnDefs: [{ visible: false, targets: -1 }],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    // -----------------------------
    // Row callback
    // -----------------------------
    $("#row-callback-datatable").DataTable({
        createdRow: function(row, data, index) {
            if (parseFloat(data[5].replace(/[\$,]/g, "")) > 150000) {
                $("td", row).eq(5).addClass("text-danger");
            }
        },
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    // -----------------------------
    // State saving
    // -----------------------------
    $("#state-saving-datatable").DataTable({
        stateSave: true,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    // -----------------------------
    // Fixed Columns
    // -----------------------------
    $("#fixed-columns-datatable").DataTable({
        scrollY: 300,
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        fixedColumns: true
    });

    // -----------------------------
    // Fixed Header
    // -----------------------------
    $("#fixed-header-datatable").DataTable({
        responsive: true,
        fixedHeader: true,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    // -----------------------------
    // Ajustes de formatação
    // -----------------------------
    $(".dataTables_length select").addClass("form-select form-select-sm");
    $(".dataTables_length label").addClass("form-label");
});
