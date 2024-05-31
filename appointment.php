<style>
    .fc-event-title-container{
        text-align:center;
    }
    .fc-event-title.fc-sticky{
        font-size: 1em;
    }
    .holiday-event {
    background-color: red;
    border-color: red;
    color: white;
    
}
.not-available .fc-event-main {
    background-color: red;
    border-color: red;
    color: white;
}
</style>
<?php 

$appointments = $conn->query("SELECT * FROM `appointment_list` where `status` in (0,1) and date(schedule) >= '".date("Y-m-d")."' ");
$appoinment_arr = [];
while($row = $appointments->fetch_assoc()){
    if(!isset($appoinment_arr[$row['schedule']])) $appoinment_arr[$row['schedule']] = 0;
    $appoinment_arr[$row['schedule']] += 1;
}

?>

<div class="content py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header rounded-0">
                        <h4 class="card-title">Choose your Appointment Date</h4>
                </div>
                <div class="card-body">
                   <div id="appointmentCalendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var calendar;
    var appointment = $.parseJSON('<?= json_encode($appoinment_arr) ?>') || {};
    start_loader();
    $(function(){
        var date = new Date();
        var d    = date.getDate(),
            m    = date.getMonth(),
            y    = date.getFullYear();
        var Calendar = FullCalendar.Calendar;

        // Fetch holidays data from Calendarific API
        $.ajax({
            url: 'https://calendarific.com/api/v2/holidays',
            type: 'GET',
            dataType: 'json',
            data: {
                api_key: '14IbgKqDnOJb7VNG4hsCH61eNEEtDiCF',
                country: 'PH', // Change to your country code if not US
                year: y
            },
            success: function(response) {
                var holidays = response.response.holidays;

                // Convert holidays data into FullCalendar event format
                var holidayEvents = holidays.map(function(holiday) {
                    return {
                        title: holiday.name,
                        start: holiday.date.iso,
                        allDay: true,
                        classNames: ['holiday-event'] 
                    };
                });

                // Initialize FullCalendar with holiday events
                initializeCalendar(holidayEvents);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch holidays:', error);
                // Initialize FullCalendar without holiday events
                initializeCalendar([]);
            }
        });

        function initializeCalendar(holidayEvents) {
            calendar = new Calendar(document.getElementById('appointmentCalendar'), {
                headerToolbar: {
                    left: false,
                    center: 'title',
                },
                selectable: true,
                themeSystem: 'bootstrap',
                events: [
                    ...holidayEvents,
                    {
                        daysOfWeek: [1,2,3,4,5], // these recurrent events move separately
                        title: '<?= ($_settings->info('max_appointment') == '2') ? '2' : $_settings->info('max_appointment') ?>', // Set title to max_appointment
                        allDay: true,
                        backgroundColor: 'green'
                    }
                ],
                eventClick: function(info) {
                    // Check if the clicked event is a holiday event
                    if ($(info.el).hasClass('holiday-event')) {
                        console.log('This is a holiday event');
                        return; // Do nothing for holiday events
                    }

                       // Proceed with appointment scheduling for non-holiday events
                       console.log(info.el);
                    if ($(info.el).hasClass('not-available')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'No More Slots',
                            text: 'No more slots available for this day.',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    uni_modal("Set an Appointment", "add_appointment.php?schedule=" + info.event.startStr, "mid-large");
                },
                validRange: {
                    start: moment(date).format("YYYY-MM-DD")
                },
                eventDidMount: function(info) {
                    if ($(info.el).hasClass('holiday-event')) {
                        return; // Skip availability calculation for holiday events
                    }

                    if (!!appointment[info.event.startStr]) {
                        var available = parseInt(info.event.title) - parseInt(appointment[info.event.startStr]);
                        if (available > 0) {
                            $(info.el).find('.fc-event-title.fc-sticky').text('Available: ' + available);
                        } else {
                            // Display "FULL" when appointments are fully booked
                            $(info.el).find('.fc-event-title.fc-sticky').text('FULL');
                            $(info.el).addClass('not-available'); // Add class for styling
                        }
                    } else {
                        $(info.el).find('.fc-event-title.fc-sticky').text('Available: ' + info.event.title);
                    }
                    end_loader();
                },
                editable: false
            });

            calendar.render();
        }
    });
</script>