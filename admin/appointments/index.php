<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Appointments</h3>
		<button class="btn btn-default float-right" id="printBtn"><i class="fa fa-print"></i> Print</button>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="20%">
					<col width="25%">
					<col width="20%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Requester ID</th>
						<th>Requester</th>
						<th>Desired Time</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT * from `appointment_list` order by unix_timestamp(`date_created`) desc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("Y-m-d",strtotime($row['date_created'])) ?></td>
							<td><?php echo ($row['code']) ?></td>
							<td class=""><p class="truncate-1"><?php echo ucwords($row['owner_name']) ?></p></td>
							<td class=""><p class="truncate-1"><?php echo ucwords($row['time']) ?></p></td>
							<td class="text-center">
								<?php 
									switch ($row['status']){
										case 0:
											echo '<span class=" ml-6 rounded-pill badge badge-primary">Pending</span>';
											break;
										case 1:
											echo '<span class="ml-6 rounded-pill badge badge-success">Confirmed</span>';
											break;
										case 2:
											echo '<span class="ml-6 rounded-pill badge badge-danger">Denied</span>';
											break;
										case 3:
											echo '<span class="ml-6 rounded-pill badge badge-primary">For Reschedule</span>';
											break;
									}
								?>
							</td>
							
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" href="./?page=appointments/view_details&id=<?php echo $row['id'] ?>" data-id=""><span class="fa fa-window-restore text-gray"></span> View</a>
									<div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<style>
    #printBtn {
        float: right;
        margin-top: 3px;
        margin-right: 10px;
	}
	
    /* CSS to hide buttons when printing */
    @media print {
        #printBtn {
            display: none;
        }
    }
        
       
</style>

<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this appointment permanently?","delete_appointment",[$(this).attr('data-id')])
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
  // JavaScript for printing functionality
  	$('#printBtn').on('click', function() {
            printContent();
        });
		   // JavaScript for printing functionality
		   $('#printBtn').on('click', function() {
            printContent();
        });

		function printContent() {
            var title = $('.card-title').text(); // Get the title
            var content = $('.card-body .table').clone(); // Clone the table
            content.find('th:last-child, td:last-child').remove(); // Remove the last column (Action)
            var printWindow = window.open('', '_blank');
            if (printWindow) {
                printWindow.document.open();
                printWindow.document.write('<html><head><title>List of Appointments</title>');
                printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
                printWindow.document.write('</head><body>');
                printWindow.document.write('<h3>' + title + '</h3>'); // Add the title
                printWindow.document.write(content[0].outerHTML);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            } else {
                console.error('Failed to open print window');
            }
        }
    })
     

		
	function delete_appointment($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_appointment",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>