<style>
    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        object-position: center center;
        border-radius: 100%;
    }

    #pieChartContainer {
        margin-top: 20px;
        padding: 40px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card {
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #analyticsChart {
        background-color: #f9f9f9;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);   
    }

    #analyticsChartContainer{
        margin-top: 20px;
        padding: 40px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .presentation-legend {
        background-color: rgba(75, 192, 192, 1);
        border-radius: 4px;
        display: inline-block;
        width: 12px;
        height: 12px;
        margin-right: 5px;
    }

    .meeting-legend {
        background-color: rgba(255, 99, 132, 1);
        border-radius: 4px;
        display: inline-block;
        width: 12px;
        height: 12px;
        margin-right: 5px;
    }

    .interview-legend {
        background-color: rgba(255, 206, 86, 1);
        border-radius: 4px;
        display: inline-block;
        width: 12px;
        height: 12px;
        margin-right: 5px;
    }

    .export-btn {
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }

    .export-btn:hover {
        background-color: #0056b3;
    }
</style>

<div class="card card-outline card-info rounded-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="text-center mb-2">Report Analytics</h5>
        <button class="export-btn ml-auto" onclick="exportToExcel()">Export to Excel</button>
    </div>
    <div id="analyticsChartContainer">
        <h5 class="text-center mb-2">Number of Requests</h5>
        <canvas id="analyticsChart" width="1000" height="490"></canvas>
    </div>

    <div id="pieChartContainer">
        <h5 class="text-center mb-2">Number of Subject Per Requests</h5>
        <canvas id="pieChart" width="1000" height="400"></canvas>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
        function loadAnalyticsChart() {
    var doughnutCtx = document.getElementById('analyticsChart').getContext('2d');
    var doughnutChart = new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Need Attention', 'Confirmed Request', 'Denied Requests', 'For Reschedule'],
            datasets: [{
                label: 'Number of Requests',
                data: [
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `status` = '0'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `status` = '1'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `status` = '2'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `status` = '3'")->num_rows; ?>
                ],
                backgroundColor: [
                    'rgba(0, 0, 255, 0.5)', // Need Attention - Blue with 50% opacity
                    'rgba(0, 255, 0, 0.5)', // Confirmed Request - Green with 50% opacity
                    'rgba(255, 0, 0, 0.5)', // Denied Requests - Red with 50% opacity
                    'rgba(128, 128, 128, 0.5)'
                ],
                borderColor: [
                    'rgba(0, 0, 255, 1)',
                    'rgba(0, 255, 0, 1)',
                    'rgba(255, 0, 0, 1)',
                    'rgba(128, 128, 128, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        usePointStyle: true
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }


        });

    var ctx = document.getElementById('pieChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Presentation', 'Meeting', 'Interview','Subject 4','Subject 5','Subject 6','Subject 7'],
            datasets: [{
                label: 'Number of Subject Per Request',
                data: [
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '1'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '2'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '3'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '4'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '5'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '6'")->num_rows; ?>,
                    <?php echo $conn->query("SELECT * FROM `appointment_list` where `subject_id` = '7'")->num_rows; ?>
                ],
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
function exportToExcel() {
    // Fetch data from fetch_appointment_data.php
    fetch('fetch_appointment_data.php')
        .then(response => response.json())
        .then(data => {
            // Create a new workbook
            var workbook = XLSX.utils.book_new();

            // Convert appointment data to worksheet
            var appointmentWorksheet = XLSX.utils.json_to_sheet(data);

            // Customize worksheet formatting
            appointmentWorksheet['!cols'] = [
                { wpx: 150 }, // Set width of first column (adjust as needed)
                { wpx: 150 }, 
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                { wpx: 150 },
                // Set width of second column (adjust as needed)
                // Add more column widths as needed
            ];
            appointmentWorksheet['!autofilter'] = { ref: 'A1:Z1' }; // Apply autofilter to the first row
            appointmentWorksheet['!freeze'] = 'A2'; // Freeze the first row

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(workbook, appointmentWorksheet, 'Appointment List');

            // Save the workbook as Excel file
            XLSX.writeFile(workbook, 'appointment_list.xlsx');
        })
        .catch(error => {
            console.error('Error fetching appointment data:', error);
        });
}

    loadAnalyticsChart();
</script>
