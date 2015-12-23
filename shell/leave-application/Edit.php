<?php
    if (isset($_POST["submit"])){
        ;
    }
?>


<form method="post" >
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="alert alert-danger hide" role="alert" id="leaveApplicationErrorMessage">
                <strong>Error: </strong><span></span>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveDate">Leave Date </label></div>
            <div class="col-sm-5"><input type="text" class="form-control" id="datepickerFrom" name="datepickerFrom" placeholder="From" /></div>
            <div class="col-sm-5"><input type="text" class="form-control" id="datepickerTo" name="datepickerTo" placeholder="to" /></div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveType">Leave Type </label></div>
            <div class="col-sm-5">
                <select id="leaveType" name="leaveType" class="form-control">
                    <option value="-1"> -- Please select -- </option>
                    <?php
                        foreach( $leaveCtrl->GetLeaves() as $lv ){
                            echo '<option value="'.$lv->Id.'">'.$lv->LeaveName.'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveDate">Half Day</label></div>
            <div class="col-sm-5">
                <input type="checkbox" id="halfDay" name="halfDay" disabled=disabled />
                <small> *Applicable when applying same date leave</small>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="remarks">Remarks</label></div>
            <div class="col-sm-5">
                <textarea id="remarks" name="remarks" class="form-control"></textarea>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <button class="btn btn-primary btn-sm" type="submit" onclick="return ValidateLeaveApplicationForm();">Apply</button>
                <button class="btn btn-danger btn-sm" type="button">Cancel</button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">

    function IsSameDate(){
        var fromDate = $('#datepickerFrom').val();
        var toDate = $('#datepickerTo').val();
        
        if( fromDate == '' && fromDate == undefined ){
            return;
        }
        
        if( toDate == '' && toDate == undefined ){
            return;
        }
        
        //check whether both are same date, if yes, then enabled the half day checkbox
        if( fromDate == toDate ){
            $('#halfDay').prop('disabled', false);
        }else{
            $('#halfDay').attr('checked', false).prop('disabled', true);
        }
    }
    
    function ValidateLeaveApplicationForm(){
        var isValidated = false;
        
        var fromDate = $('#datepickerFrom').val();
        var toDate = $('#datepickerTo').val();
        var leaveType = $('#leaveType').val();
        
        if( fromDate == '' || fromDate == undefined ){
            ShowErrorMessage('Please select start date');
            return isValidated;
        }
        
        if( toDate == '' || toDate == undefined ){
            ShowErrorMessage('Please select end date');
            return isValidated;
        }
        
        if( leaveType == -1 ){
            ShowErrorMessage('Please select leave type');
            return isValidated;
        }
        
        isValidated = true; //Set to true if pass all the required validation
        
        return isValidated; 
    }

    function ShowErrorMessage(errorMessage){
        $('#leaveApplicationErrorMessage').removeClass('hide');
        $('#leaveApplicationErrorMessage > span').html(errorMessage);
    }

    $(document).ready(function(){
        $('#datepickerFrom').datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 2,
            onClose: function( selectedDate ) {
                $( "#datepickerTo" ).datepicker( "option", "minDate", selectedDate );
                IsSameDate();
            }
        });
        $('#datepickerTo').datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 2,
            onClose: function( selectedDate ) {
                $( "#datepickerFrom" ).datepicker( "option", "maxDate", selectedDate );
                IsSameDate();
            }
        });
    });
</script>