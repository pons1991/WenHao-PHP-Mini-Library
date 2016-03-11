<?php 
    include "../Base.php";
    
    //This is to test email
    //$emailCtrl = new EmailController;
    //$emailCtrl->SendLeaveApplicationEmail('wenhao.mui@gmail.com', '','test', 'wenhao.mui@gmail.com', 'now', 'later', 'not sure','not sure','as many as possible','all','fuiyoh','not allow')
    
    //SendLeaveApplicationEmail($toEmail, $ccEmail,$subject, $applicantEmail, $fromDate, $toDate, $leaveTypeName,$remarks,$totalLeave,$bringForward,$status,$sRemarks)
    
    $newLeaveStatusId = 1;
    $emailCtrl = new EmailController;
    $leaveCtrl = new LeaveController($dbConn);
    $userCtrl = new UserController($dbConn);
    $newLeaveList = $leaveCtrl->GetLeaveByStatus($newLeaveStatusId);
    
    $subject = 'Reminder - New Leave Request';
    
    if( $newLeaveList != null && count($newLeaveList) > 0 ){
        foreach( $newLeaveList as $pendingLeave ){
            
            $orgRelList = $userCtrl->GetUserOrgRel($pendingLeave->UserId);
            if( $orgRelList != null && count($orgRelList) == 1){
                $orgRel = $orgRelList[0];
                $emailCtrl->SendLeaveApplicationEmail(
                    $orgRel->SuperiorUser->Email,
                    '',
                    $subject,
                    $pendingLeave->AccessUser->Email,
                    datetime::createfromformat('Y-m-d 00:00:00',$pendingLeave->LeaveDateFrom)->format('m/d/Y'),
                    datetime::createfromformat('Y-m-d 00:00:00',$pendingLeave->LeaveDateTo)->format('m/d/Y'),
                    $pendingLeave->LeaveType->LeaveName,
                    $pendingLeave->LeaveType->OffDayRemarks,
                    $pendingLeave->Remarks,
                    $pendingLeave->TotalLeave,
                    $pendingLeave->TotalBringForwardLeave,
                    'New',
                    $pendingLeave->SupervisorRemarks );
            }
        }
    }
    
    echo print_r($newLeaveList);
?>