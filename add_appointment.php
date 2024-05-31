<?php
require_once('./config.php');
$schedule = $_GET['schedule'];
?>
<div class="container-fluid">
    <form action="" id="appointment-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="schedule" value="<?php echo isset($schedule) ? $schedule : '' ?>">
        <dl>
            <dt class="text-muted">Appointment Schedule</dt>
            <dd class="pl-3"><b><?= date("F d, Y", strtotime($schedule)) ?></b></dd>
        </dl>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <fieldset>
                    <legend class="text-muted">Requester Information</legend>
                    <div class="form-group">
                        <label for="owner_name" class="control-label">Name</label>
                        <input type="text" name="owner_name" id="owner_name" class="form-control form-control-border" placeholder="Juan Dela Cruz" value ="<?php echo isset($owner_name) ? $owner_name : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="contact" class="control-label">Contact Number</label>
                        <input type="text" name="contact" id="contact" class="form-control form-control-border" placeholder="09xxxxxxxx" value ="<?php echo isset($contact) ? $contact : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control form-control-border" placeholder="juandelacruz@email.com" value ="<?php echo isset($email) ? $email : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address" class="control-label">Address</label>
                        <textarea type="email" name="address" id="address" class="form-control form-control-sm rounded-0" rows="3" placeholder="Poblacion,Dalaguete,Cebu" required><?php echo isset($address) ? $address : '' ?></textarea>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset>
                    <legend class="text-muted">Request Information</legend>
                    <div class="form-group">
                        <label for="subject" class="control-label">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-control form-control-border" required>
                            <option value="" disabled selected>Select Subject</option>
                            <?php 
                            $subjects = $conn->query("SELECT * FROM subjects");
                            while($row = $subjects->fetch_assoc()):
                            ?>
                            <option value="<?= $row['id'] ?>" <?= isset($subject_id) && $subject_id == $row['id'] ? "selected" : "" ?>><?= $row['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category_id" class="control-label">Office</label>
                        <select name="category_id" id="category_id" class="form-control form-control-border" readonly>
                            <option value="1" selected>Mayor's Office</option>
                        </select>
                    </div>
                </fieldset>

                <div class="form-group">
                    <label for="time" class="control-label">Time</label>
                    <select name="time" id="time" class="form-control form-control-border" required>
                        <option value="" disabled selected>Select Time</option>
                        <option value="08:00 AM">08:00 AM</option>
                        <option value="09:30 AM">09:30 AM</option>
                        <option value="10:00 AM">10:00 AM</option>
                        <option value="10:30 AM">10:30 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="11:30 AM">11:30 AM</option>
                        <option value="12:00 PM">12:00 PM</option>
                        <option value="13:00 PM">01:00 PM</option>
                        <option value="13:30 PM">01:30 PM</option>
                        <option value="14:00 PM">02:00 PM</option>
                        <option value="14:30 PM">02:30 PM</option>
                        <option value="15:00 PM">03:00 PM</option>
                        <option value="15:30 PM">03:30 PM</option>
                        <option value="16:00 PM">04:00 PM</option>
                        <option value="16:30 PM">04:30 PM</option>
                        <option value="17:00 PM">05:00 PM</option>
                        <!-- Add more time slots as needed -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="other_time" class="control-label">Other Time</label>
                    <select name="other_time" id="other_time" class="form-control form-control-border" required>
                        <option value="" disabled selected>Select other Time</option>
                        <option value="08:00 AM">08:00 AM</option>
                        <option value="09:30 AM">09:30 AM</option>
                        <option value="10:00 AM">10:00 AM</option>
                        <option value="10:30 AM">10:30 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="11:30 AM">11:30 AM</option>
                        <option value="12:00 PM">12:00 PM</option>
                        <option value="13:00 PM">01:00 PM</option>
                        <option value="13:30 PM">01:30 PM</option>
                        <option value="14:00 PM">02:00 PM</option>
                        <option value="14:30 PM">02:30 PM</option>
                        <option value="15:00 PM">03:00 PM</option>
                        <option value="15:30 PM">03:30 PM</option>
                        <option value="16:00 PM">04:00 PM</option>
                        <option value="16:30 PM">04:30 PM</option>
                        <option value="17:00 PM">05:00 PM</option>
                        <!-- Add more time slots as needed -->
                    </select>
                </div>

            </div>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal', function(){
            $('#category_id').select2({
                placeholder: "Please Select Office here.",
                width: '100%',
                dropdownParent: $('#uni_modal')
            });
            $('#time').select2({
                placeholder: "Please Select Desired Time Here.",
                width: '100%',
                dropdownParent: $('#uni_modal')
            });
            $('#other_time').select2({
                placeholder: "Please Select Other Time Here.",
                width: '100%',
                dropdownParent: $('#uni_modal')
            })
        });

        $('#uni_modal #appointment-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $('.pop-msg').remove();
            var el = $('<div>');
            el.addClass("pop-msg alert");
            el.hide();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_appointment",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp){
                    if(resp.status == 'success'){
                        end_loader();
                        setTimeout(() => {
                            uni_modal("Success", "success_msg.php?code=" + resp.code);
                        }, 750);
                    } else if(!!resp.msg){
                        el.addClass("alert-danger");
                        el.text(resp.msg);
                        _this.prepend(el);
                    } else {
                        el.addClass("alert-danger");
                        el.text("An error occurred due to unknown reason.");
                        _this.prepend(el);
                    }
                    el.show('slow');
                    $('html,body,.modal').animate({scrollTop: 0}, 'fast');
                    end_loader();
                }
            });
        });
    });
</script>
