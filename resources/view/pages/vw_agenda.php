<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

$lang = $_SESSION['lang'] ?? 'pt';

?>

<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                </div>
                <h4 class="page-title"><?= \App\Core\Language::get('agenda'); ?></h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    
    <div class="row">
        <div class="col-12">

<form class="needs-validation" id="form" name="form" role="form" method="POST" enctype="multipart/form-data" novalidate>
    <!-- Seção: Anamnese Médica -->
    <div class="card">
        <div class="card-body">
            <h4 class="header-title"><?= \App\Core\Language::get('consultas_agendads'); ?></h4> 
            <p class="text-muted font-14">  
                <?= \App\Core\Language::get('agenda_desc'); ?>
            </p>

            <div class="tab-content">
                <div class="tab-pane show active" id="input-types-preview">

                    <div class="row">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div class="mt-4 mt-lg-0">
                                                <div id="calendar"></div>
                                            </div>
                                        </div> <!-- end col -->

                                    </div> <!-- end row -->
                                </div> <!-- end card body-->
                            </div> <!-- end card -->

                            <!-- Add New Event MODAL -->
                            <div class="modal fade" id="event-modal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                            <div class="modal-header py-3 px-4 border-bottom-0">
                                                <h5 class="modal-title" id="modal-title">Event</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body px-4 pb-4 pt-0">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label class="control-label form-label">Event Name</label>
                                                            <input class="form-control" placeholder="Insert Event Name" type="text" name="title" id="event-title" required />
                                                            <div class="invalid-feedback">Please provide a valid event name</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label class="control-label form-label">Category</label>
                                                            <select class="form-select" name="category" id="event-category" required>
                                                                <option value="bg-danger" selected>Danger</option>
                                                                <option value="bg-success">Success</option>
                                                                <option value="bg-primary">Primary</option>
                                                                <option value="bg-info">Info</option>
                                                                <option value="bg-dark">Dark</option>
                                                                <option value="bg-warning">Warning</option>
                                                            </select>
                                                            <div class="invalid-feedback">Please select a valid event category</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-danger" id="btn-delete-event">Delete</button>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success" id="btn-save-event">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div> <!-- end modal-content-->
                                </div> <!-- end modal dialog-->
                            </div>
                            <!-- end modal-->
                        </div>
                        <!-- end col-12 -->
                    </div> <!-- end row -->

               
                    <!-- end row-->
                </div> <!-- end preview-->
            </div> <!-- end tab-content-->
        </div> <!-- end card-body -->
    </div> <!-- end card -->

      
</form>
                                       
        </div><!-- end col -->
    </div><!-- end row -->
</div>
<br>


<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: '<?= $lang; ?>',
        initialView: 'timeGridWeek', // semana como padrão
        nowIndicator: true, // linha vermelha mostrando hora atual
        slotDuration: '00:30:00', // intervalos de 30 minutos
        slotMinTime: "07:00:00", // horário inicial visível
        slotMaxTime: "22:00:00", // horário final visível
        allDaySlot: false, // remove "dia inteiro" no topo
        editable: true,
        selectable: true,
        selectMirror: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: '<?= \App\Core\Language::get('hoje'); ?>',
            month: '<?= \App\Core\Language::get('mes'); ?>',
            week: '<?= \App\Core\Language::get('semana'); ?>',
            day: '<?= \App\Core\Language::get('dia'); ?>',
            list: '<?= \App\Core\Language::get('agenda'); ?>'
        },
        height: 2000,      // altura total do calendário
        expandRows: true,  // distribui os slots igualmente
        select: function(arg) {
            var modal = new bootstrap.Modal(document.getElementById("event-modal"));
            modal.show();
        },
        eventClick: function(info) {
            var modal = new bootstrap.Modal(document.getElementById("event-modal"));
            modal.show();
        },
        events: [
            {
                title: 'Consulta João',
                start: '2025-09-07T19:00:00',
                end: '2025-09-07T19:30:00',
                className: 'bg-success',
                observacoes: 'Paciente novo, trazer exames de sangue'
            },
            {
                title: 'Consulta João',
                start: '2025-09-08T16:00:00',
                end: '2025-09-08T16:30:00',
                className: 'bg-success',
                observacoes: 'Consulta de retorno'
            },
            {
                title: 'Consulta João',
                start: '2025-09-09T17:00:00',
                end: '2025-09-09T17:30:00',
                className: 'bg-success',
                observacoes: 'Confirmar convênio na recepção'
            },
            {
                title: 'Retorno Maria',
                start: '2025-09-09T14:00:00',
                end: '2025-09-09T15:30:00',
                className: 'bg-info',
                observacoes: 'Paciente pediu avaliação de exames anteriores'
            }
        ]
    });

    calendar.render();
});
</script>



<style>
/* Aumenta a altura de cada slot de 30 minutos */
.fc-timegrid-slot {
    height: 200px; /* ajuste para caber mais informação */
}

/* Aumenta a altura mínima de cada evento */
.fc-event-main {
    min-height: 60px;
    padding: 6px;
    font-size: 14px; /* deixa o texto legível */
}

/* Opcional: aumenta fonte do horário lateral */
.fc-col-header-cell-cushion {
    font-size: 14px;
}
</style>




