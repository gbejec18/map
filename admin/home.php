<style>
    #cover-img{
        object-fit:cover;
        object-position:center center;
        width: 100%;
        height: 100%;
    }
    .fc-event-title-container{
        text-align:center;
    }
    .fc-event-title.fc-sticky{
        font-size:2em;
    }
    #lineChartContainer {
        margin-top: 20px;
        padding: 40px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

</style>
<?php 
$appointments = $conn->query("SELECT * FROM `appointment_list` where `status` in (0,1) and date(schedule) >= '".date("Y-m-d")."' ");
$appointment_arr = [];
while($row = $appointments->fetch_assoc()){
    if(!isset($appointment_arr[$row['schedule']])) $appointment_arr[$row['schedule']] = 0;
    $appointment_arr[$row['schedule']] += 1;
}
?>
<h1>Welcome to <?php echo $_settings->info('name') ?> - Admin Panel</h1>
<hr class="border-info">
<div class="row">
  
    <div class="col-12 col-sm-12 col-md-10 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-calendar-day"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Need Attention</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `appointment_list` where `status` = 0 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-calendar-day"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Confirmed Request</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `appointment_list` where `status` = 1 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-danger elevation-1"><i class="fas fa-calendar-day"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Denied Request</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `appointment_list` where `status` = 2 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-gray elevation-1"><i class="fas fa-calendar-day"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">For Reschedule</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `appointment_list` where `status` = 3 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

</div>
</div>
<hr>
<div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header rounded-0">
            <h4 class="card-title">Report Analytics</h4>
        </div>
        <div class="card-body">
            <canvas id="analyticsChart" width="1000" height="300"></canvas>
        </div>
    </div>

<div class="card card-outline card-primary rounded-0 shadow">
    <div class="card-header rounded-0">
            <h4 class="card-title">Appointment Requests</h4>
    </div>
    <div class="card-body">
        <div id="appointmentCalendar"></div>
    </div>
</div>



<script>
    var calendar;
    var appointment = $.parseJSON('<?= json_encode($appointment_arr) ?>') || {};
    start_loader();
    $(function(){
        var date = new Date()
        var d    = date.getDate(),
            m    = date.getMonth(),
            y    = date.getFullYear()
        var Calendar = FullCalendar.Calendar;

        calendar = new Calendar(document.getElementById('appointmentCalendar'), {
            headerToolbar: {
                left  : false,
                center: 'title',
            },
            selectable: true,
            themeSystem: 'bootstrap',
            //Random default events
            events: [
                {
                    daysOfWeek: [1,2,3,4,5], // these recurrent events move separately
                    title:0,
                    allDay: true,
                    }
            ],
            validRange:{
                start: moment(date).format("YYYY-MM-DD"),
            },
            eventDidMount:function(info){
                // console.log(appointment)
                if(!!appointment[info.event.startStr]){
                    var available = parseInt(info.event.title) + parseInt(appointment[info.event.startStr]);
                     $(info.el).find('.fc-event-title.fc-sticky').text(available)
                }
                end_loader()
            },
            editable  : false
        });

    calendar.render();

    loadAnalyticsChart();

    })
    function loadAnalyticsChart() {
    var ctx = document.getElementById('analyticsChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Presentation', 'Meeting', 'Interview','Subject 4','Subject 5','Subject 6','Subject 7'],
            datasets: [{
                label: 'Number of Requests',
                data: [
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '1'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '2'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '3'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '4'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '5'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '6'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '7'")->num_rows; ?>
                ],
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: false // Set to false to disable filling the area under the line
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

  

    
        
</script>