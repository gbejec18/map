<div class="card card-outline card-primary">
	<div class="card-header">
	<h1 class="print-only" style="position: relative; z-index: 1; text-align: center; color: #333;">Municipality of Dalaguete</h1>
		<h3 class="card-title">List of Appointments</h3>
		<button class="btn btn-sm btn-primary btn-flat print-exclude float-right" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
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
						<th class="print-exclude">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT * from `appointment_list` order by unix_timestamp(`date_created`) desc ");
						while($row = $qry->fetch_assoc()):
						$desired_time= date("g:i A", strtotime($row['time']));
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("Y-m-d",strtotime($row['date_created'])) ?></td>
							<td><?php echo ($row['code']) ?></td>
							<td class=""><p class="truncate-1"><?php echo ucwords($row['owner_name']) ?></p></td>
							<td class=""><p class="truncate-1"><?php echo $desired_time ?></p></td>

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
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon print-exclude" data-toggle="dropdown">
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
	  /* Define styles for print */
	  @media print {
        .print-exclude,
        .dataTables_filter,
        .dataTables_paginate,
        .dataTables_length,
        .dataTables_info {
            display: none !important;
        }
        /* Style for logo and text */
        .print-logo {
            position: relative;
            /* Optional: ensure the logo starts on a new page */
        }
    }

	/* Hide the text in normal view */
    .print-only {
        display: none;
    }

    /* Display the text only in print preview */
    @media print {
        .print-only {
            display: block !important;
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