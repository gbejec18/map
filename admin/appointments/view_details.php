<?php 

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT a.*, c.name as request_type, s.name as subject_name 
                         FROM `appointment_list` a 
                         INNER JOIN category_list c ON a.category_id = c.id 
                         LEFT JOIN subjects s ON a.subject_id = s.id 
                         WHERE a.id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
    } else {
        echo "<script>alert('Unknown Appointment Request ID'); location.replace('./?page=appointments');</script>";
    }
} else {
    echo "<script>alert('Appointment Request ID is required'); location.replace('./?page=appointments');</script>";
}

$desired_time = date("g:i A", strtotime($time));
if (!empty($confirmed_time)) {
    $confirmed_time_formatted = date("g:i A", strtotime($confirmed_time));
} else {
    $confirmed_time_formatted = "N/A"; // or any default value you prefer
}

?>
<style>

    img#appointment-banner{
		height: 45vh;
		width: 20vw;
		object-fit: scale-down;
		object-position: center center;
	}
    .table.border-info tr, .table.border-info th, .table.border-info td{
        border-color:var(--dark);
    }
    .mayor-signature-section {
    display: none;
}
      /* Add background image for print preview */
    @media print {
    .print-exclude {
        display: none;
    }
}
/* Show the Mayor's Signature section only when printing */
@media print {
    .print-only {
        display: block !important;
    }
}
  
</style>
<div class="content py-3">
    <div class="card card-outline card-dark rounded-0">
        <div class="card-header rounded-0">
            <h5 class="card-title text-primary">Appointment Request Details</h5>
            <div class="float-right">                                           
            </div>
            
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div id="outprint">
                    <fieldset>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered border-info">
                                    <colgroup>
                                        <col width="30%">
                                        <col width="70%">
                                    </colgroup>
                                    <tr>
                                        <th class="text-muted text-white bg-gradient-dark px-2 py-1">Appointment Request Number</th>
                                        <td><?= ($code) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="content py-3">
                        <div class="card card-outline card-dark rounded-0">
                            <div class="card-header rounded-0">
                            </div>
                            <div class="card-body">
                                <div id="printableContent">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <fieldset>
                                                <legend class="text-muted border-bottom">Requester Information</legend>
                                                <table class="table table-stripped table-bordered" data-placeholder='true'>
                                                    <colgroup>
                                                        <col width="40%">
                                                        <col width="30%">
                                                    </colgroup>
                                                    <tbody>

                                                        <tr class="border-info">
                                                            <th class="py-1 px-1 text-light bg-gradient-info">Name</th>
                                                            <td class="py-1 px-2 text-right"><?= ucwords($owner_name) ?></td>
                                                        </tr>

                                                        <tr class="border-info">
                                                            <th class="py-1 px-2 text-light bg-gradient-info">Contact </th>
                                                            <td class="py-1 px-2 text-right"><?= ($contact) ?></td>
                                                        </tr>

                                                        <tr class="border-info">
                                                            <th class="py-1 px-2 text-light bg-gradient-info">Email</th>
                                                            <td class="py-1 px-2 text-right"><?= ($email) ?></td>
                                                        </tr>
                                                        
                                                        <tr class="border-info">
                                                            <th class="py-1 px-2 text-light bg-gradient-info">Address</th>
                                                            <td class="py-1 px-2 text-right"><?= ($address) ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset>
                                                <legend class="text-muted border-bottom">Request Details</legend>
                                                <table class="table table-stripped table-bordered" data-placeholder='true'>
                                                    <colgroup>
                                                        <col width="40%">
                                                        <col width="50%">
                                                    </colgroup>
                                                    <tbody>

                                                    <tr class="border-info">
                                                        <th class="py-1 px-2 text-light bg-gradient-info">Subject</th>
                                                        <td class="py-1 px-2 text-right"><?= ($subject_name) ?></td>
                                                    </tr>

                                                        <tr class="border-info">
                                                            <th class="py-1 px-2 text-light bg-gradient-info">Desired Appointment Date</th>
                                                            <td class="py-1 px-2 text-right"><?= ($schedule) ?></td>
                                                        </tr>

                                                        <tr class="border-info">
                                                            <th class="py-1 px-2 text-light bg-gradient-info">Desired Appointment Time</th>
                                                            <td class="py-1 px-2 text-right"><?= ($desired_time) ?></td>
                                                        </tr>

                                                        <tr class="border-info">
                                                            <th class="py-1 px-2 text-light bg-gradient-info">Other Time</th>
                                                            <td class="py-1 px-2 text-right"><?= ($other_time) ?></td>
                                                        </tr>

                                                        
                                                         

                                                    </tbody>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                                            <fieldset>
                                                <legend class="text-muted border-bottom">Other Information</legend>
                                                <table class="table table-stripped table-bordered" data-placeholder='true'>
                                                    <colgroup>
                                                        <col width="40%">
                                                        <col width="30%">
                                                    </colgroup>
                                                    <tbody>

                                                    <tr class="border-info">
                                                            <th class="py-1 px-2 text-light bg-gradient-info">Confirmed Time</th>
                                                            <td class="py-1 px-2 text-right"><?= ($confirmed_time_formatted) ?></td>
                                                        </tr> 

                                                        <tr class="border-info">
                                                            <th class="py-1 px-2 text-light bg-gradient-info">Appointment Venue</th>
                                                            <td class="py-1 px-2 text-right"><?= ($venue) ?></td>
                                                        </tr>   
                                                       
                                                        <tr class="border-info">
                                                            <th class="py-1 px-2 text-light bg-gradient-info">Status</th>
                                                            <td class="py-1 px-2 text-right">
                                                                <?php 
                                                                    switch ($status){
                                                                case 0:
                                                                    echo '<span class="ml-6 rounded-pill badge badge-primary">Pending</span>';
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
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </fieldset>
                                        </div>                
                
                <hr>
                <div class="rounded-0 text-center mt-3">
                       
                <button class="btn btn-sm btn-danger btn-flat print-exclude" type="button" id="delete_data"><i class="fa fa-trash"></i> Delete</button>
                <a class="btn btn-light border btn-flat btn-sm print-exclude" href="./?page=appointments"><i class="fa fa-angle-left"></i> Back to List</a>
                <a class="btn btn-sm btn-primary btn-flat print-exclude" href="javascript:void(0)" id="update_status"><i class="fa fa-edit"></i> Update Status and Venue</a>

                <button class="btn btn-sm btn-secondary btn-flat print-exclude" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row mayor-signature-section print-only">
    <div class="col-md-6 offset-md-3 mt-5">
        <div class="form-group">
            <div class="text-right"> <!-- Align content to the right -->
                <!-- Short line above the signature -->
                   <label for="mayor_signature" class="font-weight-bold">Ronald Allan G. Cesante, CPA</label>
                <br>    
                <label for="mayor_signature" class="font-weight-bold">Mayor's Signature</label>
                <!-- Short line for the signature -->
            </div>
        </div>
    </div>
</div>


<script>
    $(function(){
        $('#delete_data').click(function(){
			_conf("Are you sure to delete <b><?= $code ?>\'s</b> from appointment permanently?","delete_appointment",['<?= $id ?>'])
		})
        $('#update_status').click(function(){
            uni_modal("Update Status","appointments/update_status.php?id=<?= $id ?>&status=<?= $status ?>")
        })
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
					location.replace('./?page=appointments');
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>