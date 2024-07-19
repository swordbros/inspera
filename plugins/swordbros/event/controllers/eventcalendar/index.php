<div id="calendar"></div>
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= e(trans('event.plugin.new_calender'))?> </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form data-request="onGetEventTypeForm"  class="row" >
                    <div class="col-md-6">

                        <h6><?= e(trans('event.plugin.select_a_eventtype')) ?></h6>
                        <ul class="list-unstyled">
                            <?php $checked = 'checked'; ?>
                            <?php foreach($event_types as $service){ ?>
                                <li><label><input type="radio" name="event_type_id" <?=$checked?> value="<?=$service->id?>"> <?=$service->name?></label></li>
                                <?php $checked = ''; ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><?= e(trans('event.plugin.select_a_eventzone')) ?></h6>
                        <ul class="list-unstyled">
                            <?php $checked = 'checked'; ?>
                            <?php foreach($event_zones as $place){ ?>
                                <li><label><input type="radio" name="event_zone_id" <?=$checked?> value="<?=$place->id?>"> <?=$place->name?></label></li>
                                <?php $checked = ''; ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary" type="submit"><?= e(trans('event.plugin.continue'))?></button>
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
    var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
        keyboard: false
    });
    var calendar = null;
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialDate: '<?=date("Y-m-d")?>',
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
                $('#listBulkActions').html('');
                myModal.show();
            },
            eventClick: function(arg) {
                window.location.href = arg.event.extendedProps.event_view_url;
            },

            editable: true,
            dayMaxEvents: true, // allow "more" link when too many events
            /*events: <?=$events?>,*/
            events: function(fetchInfo, successAjaxCallback, failureAjaxCallback) {
                $.ajax({
                    url: '<?=$getfilteredevents_url?>',
                    dataType: 'json',
                    data: {
                        start: fetchInfo.startStr,
                        end: fetchInfo.endStr
                    },
                    success: function(data) {
                        console.log("takvim", data);
                        successAjaxCallback(data);
                    },
                    error: function() {
                        failureAjaxCallback();
                    }
                });
            },
            datesSet: function(dateInfo) {
                calendar.refetchEvents();
            }
        });
        calendar.render();
    });


    $('body').on('submit', '[data-request="onGetEventTypeForm"]', function (){
        $('#listBulkActions').html('<?= e(trans('swordbros.event::plugin.please_loading'))?>');
    });
    function onPopupSuccess(data, textStatus, jqXHR) {
        myModal.hide();
        calendar.refetchEvents();
    }
</script>
