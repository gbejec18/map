<div class="container-fluid">
    <form action="" id="update-form">
        <input type="hidden" name="id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">
        <input type="hidden" name="schedule" value="<?= isset($schedule) ? $schedule : '' ?>">
        <div class="form-group">
            <small class="text-muted">Status</small>
            <select name="status" id="status" class="form-control form-control-sm form-control-border" required>
                <option value="" disabled selected>Select a status here</option>
                <option value="0" <?= isset($status) && $status == 0 ? "selected" : "" ?>>Pending</option>
                <option value="1" <?= isset($status) && $status == 1 ? "selected" : "" ?>>Confirmed</option>
                <option value="2" <?= isset($status) && $status == 2 ? "selected" : "" ?>>Denied</option>
                <option value="3" <?= isset($status) && $status == 3 ? "selected" : "" ?>>For Reschedule</option>
            </select>
        </div>
        <div class="form-group">
            <small class="text-muted">Confirmed Time</small>
            <select name="confirmed_time" id="confirmed_time" class="form-control form-control-sm form-control-border">
                <option value="" disabled selected>Select a confirmed time</option>
                <option value="9:00 AM" <?= isset($confirmed_time) && $confirmed_time == '9:00 AM' ? "selected" : "" ?>>9:00 AM</option>
                <option value="10:00 AM" <?= isset($confirmed_time) && $confirmed_time == '10:00 AM' ? "selected" : "" ?>>10:00 AM</option>
                <option value="11:00 AM" <?= isset($confirmed_time) && $confirmed_time == '11:00 AM' ? "selected" : "" ?>>11:00 AM</option>
                <option value="13:00 PM" <?= isset($confirmed_time) && $confirmed_time == '13:00 PM' ? "selected" : "" ?>>13:00 PM</option>
                <option value="14:00 PM" <?= isset($confirmed_time) && $confirmed_time == '14:00 PM' ? "selected" : "" ?>>14:00 PM</option>
                <option value="15:00 PM" <?= isset($confirmed_time) && $confirmed_time == '15:00 PM' ? "selected" : "" ?>>15:00 PM</option>
                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group">
            <small class="text-muted">Venue</small>
            <select name="venue" id="venue" class="form-control form-control-sm form-control-border" required>
                <option value="" disabled selected>Select a venue here</option>
                <option value="Mayor's Office" <?= isset($venue) && $venue == "Mayor's Office" ? "selected" : "" ?>>Mayor's Office</option>
                <option value="Conference Room" <?= isset($venue) && $venue == "Conference Room" ? "selected" : "" ?>>Conference Room</option>
                <option value="Session Hall" <?= isset($venue) && $venue == "Session Hall" ? "selected" : "" ?>>Session Hall</option>
            </select>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        // Fetch confirmed times when the modal is shown
        $('#uni_modal').on('shown.bs.modal', function() {
            var schedule = $('input[name="schedule"]').val();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=get_confirmed_times",
                method: 'POST',
                data: { schedule: schedule },
                dataType: 'json',
                success: function(resp) {
                    if (resp.confirmed_times) {
                        var confirmedTimes = resp.confirmed_times;
                        $('#confirmed_time option').each(function() {
                            if (confirmedTimes.includes($(this).val())) {
                                $(this).prop('disabled', true);
                            }
                        });
                    }
                }
            });
        });

        $('#update-form').submit(function(e) {
            e.preventDefault();
            var _this = $(this);
            $('.pop-msg').remove();
            var el = $('<div>');
            el.addClass("pop-msg alert");
            el.hide();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=update_appointment_status",
                data: new FormData($(_this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: function(err) {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        location.reload();
                    } else if (resp.status == 'conflict') {
                        el.addClass("alert-danger");
                        el.text(resp.msg);
                        _this.prepend(el);
                        el.show('slow');
                        $('html, body, .modal').animate({scrollTop: 0}, 'fast');
                    } else if (resp.msg) {
                        el.addClass("alert-danger");
                        el.text(resp.msg);
                        _this.prepend(el);
                        el.show('slow');
                        $('html, body, .modal').animate({scrollTop: 0}, 'fast');
                    } else {
                        el.addClass("alert-danger");
                        el.text("An error occurred due to an unknown reason.");
                        _this.prepend(el);
                        el.show('slow');
                        $('html, body, .modal').animate({scrollTop: 0}, 'fast');
                    }
                    end_loader();
                }
            });
        });
    });
</script>
