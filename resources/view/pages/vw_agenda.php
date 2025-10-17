<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

$profissionalId = $_SESSION['PROFISSIONAL_ID'] ?? 'all';
$lang = $_SESSION['lang'] ?? 'pt';


$consultasCalendario = \App\Controller\Pages\Home::getConsultasToCalendar($profissionalId);

$events = [];

foreach ($consultasCalendario as $consulta) {

    $start = $consulta['CON_DTCONSULTA'] . 'T' . $consulta['CON_HORACONSULTA'];

    $horaFim = date('H:i:s', strtotime($consulta['CON_HORACONSULTA'] . ' + ' . $consulta['CON_NUMDURACAO'] . ' minutes'));
    $end = $consulta['CON_DTCONSULTA'] . 'T' . $horaFim;

    $status = strtoupper($consulta['CON_ENSTATUS']);
    switch ($status) {
        case 'CONFIRMADA':
            $className = 'bg-success';
            break;
        case 'AGENDADA':
            $className = 'bg-info';
            break;
        case 'CANCELADA':
            $className = 'bg-warning';
            break;
        case 'FALTA':
            $className = 'bg-danger';
            break;
        default:
            $className = 'bg-secondary';
    }

    $events[] = [
        'title' => 'Consulta ' . $consulta['PAC_DCNOME'],
        'start' => $start,
        'end' => $end,
        'className' => $className
    ];
}

?>
<!-- Start Content-->
<div class="container-fluid" style="max-width:100% !important; padding-left:0px; padding-right:0px;">
    <div class="row">
        <div class="col-12" style="max-width:100% !important; padding-left:0px; padding-right:0px;">
            <!-- Seção: Anamnese Médica -->
            <div class="card" style="max-width:100% !important; padding-left:0px; padding-right:0px;">
                <div class="card-body">
                    <h4 class="header-title">
                        <?= \App\Core\Language::get('agenda'); ?>
                    </h4> 
                    
                    <p class="text-muted font-14">
                        <?= \App\Core\Language::get('agenda_desc'); ?>
                    </p>

                    <div class="tab-content" >
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

                                </div> <!-- end col-12 -->
                            </div> <!-- end row -->
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Inicializa FullCalendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: '<?= $lang; ?>',
        initialView: 'timeGridWeek',
        nowIndicator: true,
        slotDuration: '00:30:00',
        slotMinTime: "07:00:00",
        slotMaxTime: "22:00:00",
        allDaySlot: false,
        editable: true,
        selectable: true,
        expandRows: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: '<?= \App\Core\Language::get("hoje"); ?>',
            month: '<?= \App\Core\Language::get("mes"); ?>',
            week: '<?= \App\Core\Language::get("semana"); ?>',
            day: '<?= \App\Core\Language::get("dia"); ?>',
            list: '<?= \App\Core\Language::get("agenda"); ?>'
        },
        height: 1800,
        select: function(arg) {
            var modal = new bootstrap.Modal(document.getElementById("event-modal"));
            modal.show();
        },
        eventClick: function(info) {
            var modal = new bootstrap.Modal(document.getElementById("event-modal"));
            modal.show();
        },
        events: <?= json_encode($events) ?>
    });

    calendar.render();
});
</script>

<style>
/* Mantém altura dos slots */
.fc-timegrid-slot {
    height: 100px; /* ou o valor que você já usa */
}

/* Linha lateral esquerda (coluna de horários) */
.fc-timegrid-axis {
    border-right: 3px solid #80b5eeff; /* cor azul semelhante ao Google */
    padding-right: 4px;
    font-weight: 500;
    font-size: 14px;
}

/* Aumenta a altura mínima de cada evento */
.fc-event-main {
    min-height: 30px;
    padding: 2px 4px;
    font-size: 12px;
    display: flex;
    align-items: center;
}

/* Alinha o texto do evento à esquerda */
.fc-event-title, 
.fc-event-time {
    text-align: left !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Reduz a distância entre hora e título */
.fc-event-time {
    margin-right: 2px;
}

/* Opcional: aumenta fonte do cabeçalho */
.fc-col-header-cell-cushion {
    font-size: 16px;
}

</style>
