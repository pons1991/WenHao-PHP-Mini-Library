<?php
    $leaveTypeList = $leaveCtrl->GetLeaveTypes();
    
    if (isset($_POST["submit"])){
        $userId = $_POST["userId"];
        $proratedYear = $_POST["proratedYear"];
        
        $proratedLeaveArray = array();
        foreach( $leaveTypeList as $lv ){
            $proratedLeaveTB = $_POST["leaveType".$lv->Id];
            $proratedLeaveTB = trim($proratedLeaveTB);
            if( !empty($proratedLeaveTB) ){
                $proratedLeaveArray[$lv->Id] = $proratedLeaveTB;
            }
        }
        
        $jsonEncodedArray = json_encode($proratedLeaveArray);
        $dbOptResp = $leaveCtrl->AddNewProRatedLeave($userId,$proratedYear,$jsonEncodedArray, $loginCtrl->GetUserName());
        echo print_r($dbOptResp);
    }
?>

<form method="post">
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="alert alert-danger hide" role="alert" id="prorateErrorMessage">
                <strong>Error: </strong><span></span>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="user">User</label></div>
            <div class="col-sm-5">
                <select id="userId" name="userId" class="form-control">
                    <option value="-1"> -- Please select -- </option>
                    <?php
                        foreach( $userCtrl->GetUsers() as $usr ){
                            echo '<option value="'.$usr->Id.'">'.$usr->Email.'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="proratedYear">Pro Rated Year </label></div>
            <div class="col-sm-5">
                <select id="proratedYear" name="proratedYear" class="form-control">
                        <option value="-1"> -- Please select -- </option>
                        <?php
                            for( $i = 0 ; $i < 5 ; $i++ ){
                                $tempYear = date("Y", strtotime('+'.$i.' years'));
                                echo '<option value="'.$tempYear.'">'.$tempYear.'</option>';
                            }
                        ?>
                    </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveType">Leave Type </label></div>
            <div class="col-sm-5">
                <?php
                        foreach( $leaveTypeList as $lv ){
                            echo '<div class="row">';
                            echo '<div class="col-sm-12 form-group">';
                            echo '<div class="col-sm-6"><label for="">'.$lv->LeaveName.'</label></div>';
                            echo '<div class="col-sm-6"><input type="text" class="form-control leaveTypeTB" id="leaveType'.$lv->Id.'" name="leaveType'.$lv->Id.'" /></div>';
                            echo '</div>';
                            echo '</div>';
                        }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit" onclick="return ValidateProRateForm();">Apply</button>
                <button class="btn btn-danger btn-sm" type="button">Cancel</button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    function ValidateProRateForm(){
        var isValidated = false;
        var integerReg = /^\d+$/;
        var userId = $('#userId').val();
        var proratedYear = $('#proratedYear').val();
        
        if( userId == -1 ){
            ShowErrorMessage('Please select a user');
            return isValidated;
        }
        
        if( proratedYear == -1 ){
            ShowErrorMessage('Please select pro rated year');
            return isValidated;
        }
        
        var hasValue = false;
        var isValueValid = true;
        $('input.leaveTypeTB').each(function(i,e){
            var tbValue = $(this).val();
            tbValue = tbValue.trim();
            
            //check for value
            if( tbValue !== "" ){
                hasValue = true;
                
                //check for integer value
                if( !integerReg.test(tbValue) ){
                    isValueValid = false;
                }
            }
        });
        
        if( !hasValue ){
            ShowErrorMessage("Please ensure pro rated leave is assigned to at least one leave type");
            return isValidated;
        }
        
        if( !isValueValid ){
            ShowErrorMessage("Please ensure pro rated leave is only integer");
            return isValidated;
        }
        
        isValidated = true;
        return isValidated;
    }
    
    function ShowErrorMessage(errorMessage){
        $('#prorateErrorMessage').removeClass('hide');
        $('#prorateErrorMessage > span').html(errorMessage);
    }
</script>