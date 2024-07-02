<div id="calendar"></div>
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= e(trans('swordbros.event::plugin.new_calender'))?> </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form data-request="onGetEventTypeForm"  class="row" data-request-success="successHandler()">
                    <div class="col-md-6">

                        <h6>Etkinlik türü Seçiniz</h6>
                        <ul class="list-unstyled">
                            <?php $checked = 'checked'; ?>
                            <?php foreach($services as $service){ ?>
                                <li><label><input type="radio" name="event_type" <?=$checked?> value="<?=$service->code?>"> <?=$service->name?></label></li>
                                <?php $checked = ''; ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Etkinlik yeri Seçiniz</h6>
                        <ul class="list-unstyled">
                            <?php $checked = 'checked'; ?>
                            <?php foreach($places as $place){ ?>
                                <li><label><input type="radio" name="event_place" <?=$checked?> value="<?=$place->id?>"> <?=$place->name?></label></li>
                                <?php $checked = ''; ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary" type="submit"><?= e(trans('swordbros.event::plugin.continue'))?></button>
                    </div>
                    <input type="hidden" name="date" value="" id="date">
                </form>
                <div class="row">
                    <div class="col-md-12 mt-2" id="listBulkActions"></div>
                </div>
            </div>
            <div class="modal-footer text-end">

            </div>
        </div>
    </div>
</div>
<style>
    .fc-daygrid-event-dot {
        border: calc(var(--fc-daygrid-event-dot-width) / 1) solid var(--fc-event-border-color);

    }
</style>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialDate: '2024-06-12',
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            selectMirror: true,
            select: function(arg) {
                /*var title = prompt('Event Title:');
                if (title) {
                    calendar.addEvent({
                        title: title,
                        start: arg.start,
                        end: arg.end,
                        allDay: arg.allDay
                    })
                }
                calendar.unselect();*/

                $('#date').val(arg.startStr);
                var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                    keyboard: false
                });
                $('#listBulkActions').html('');
                myModal.show();
            },
            eventClick: function(arg) {
                window.location.href = arg.event.extendedProps.event_view_url;
            },

            editable: true,
            dayMaxEvents: true, // allow "more" link when too many events
            events: <?=$events?>,
            eventDidMount: function(info) {

            },
            /*eventContent: function(arg) {
                let event = arg.event;
                let description = event.extendedProps.description;
                let color = event.extendedProps.color;

                return {
                    html: `<div class="fc-event-title" style="color:${event.borderColor}">${event.title}</div>`

                }
            }*/
        });

        calendar.render();
    });
    function successHandler(data) {
        $(document).ready(function () {
            $('[data-richeditor-textarea]').each(function () {
                console.log(this);
                $(this).richEditor();
            });

        });
    }

</script>
