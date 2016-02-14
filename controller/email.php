<?php 
    class EmailController extends BaseController{
        function SendLeaveApplicationEmail($toEmail, $ccEmail,$subject, $applicantEmail, $fromDate, $toDate, $leaveTypeName,$remarks,$totalLeave,$bringForward,$status,$sRemarks){
            $miniEmailManager = new MiniEmailManager;
                
                $to = array();
                array_push($to, $toEmail);
                
                $cc = array();
                if( !empty($ccEmail) ){
                    array_push($cc, $ccEmail);
                }
                
                $body = '<table border="1">';
                
                //Applicant email
                $body .= '<tr>';
                $body .= '<td>Email</td>';
                $body .= '<td>'.$applicantEmail.'</td>';
                $body .= '</tr>';
                
                //leave from - to
                $body .= '<tr>';
                $body .= '<td>Leave Date</td>';
                $body .= '<td>'.$fromDate.' - '.$toDate.'</td>';
                $body .= '</tr>';
                
                //leave type
                $body .= '<tr>';
                $body .= '<td>Leave Type</td>';
                $body .= '<td>'.$leaveTypeName.'</td>';
                $body .= '</tr>';
                
                //remarks
                $body .= '<tr>';
                $body .= '<td>Remarks</td>';
                $body .= '<td>'.$remarks.'</td>';
                $body .= '</tr>';
                
                //leave type
                $body .= '<tr>';
                $body .= '<td>Total Leave</td>';
                $body .= '<td>';
                $body .= '<p>'.date('Y').' Leave: '.$totalLeave.'</p>';
                $body .= '<p>Bringforward: '.$bringForward.'</p>';
                $body .= '</td>';
                $body .= '</tr>';
                
                //Status
                $body .= '<tr>';
                $body .= '<td>Status</td>';
                $body .= '<td>'.$status.'</td>';
                $body .= '</tr>';
                
                //supervisor remarks
                if(!empty($sRemarks)){
                    $body .= '<tr>';
                    $body .= '<td>Supervisor Remarks</td>';
                    $body .= '<td>'.$sRemarks.'</td>';
                    $body .= '</tr>';
                }
                
                $body .= '</table>';
                
                $miniEmailManager->SendEmail($to, $cc, $subject, $body);
        }
        
        function SendRejectLeaveApplicationEmail(){
            
        }
        
        function SendApprovedLeaveApplicationEmail(){
            
        }
    }
?>