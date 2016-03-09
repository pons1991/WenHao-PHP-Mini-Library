<?php 
    class EmailController extends BaseController{
        function SendLeaveApplicationEmail($toEmail, $ccEmail,$subject, $applicantEmail, $fromDate, $toDate, $leaveTypeName,$jsonOffDayRemarkString,$remarks,$totalLeave,$bringForward,$status,$sRemarks){
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
                
                //Off Day
                $jsonArr = json_decode($jsonOffDayRemarkString, true);
                if( $jsonArr != null ){
                    $offDayRemark = '';
                    
                    if( $jsonArr['Monday'] == '1' ){
                        if( !empty($offDayRemark)){
                            $offDayRemark = $offDayRemark . ', ';
                        }
                        $offDayRemark = $offDayRemark. 'Monday';
                    }
                    
                    if( $jsonArr['Tuesday'] == '1' ){
                        if( !empty($offDayRemark)){
                            $offDayRemark = $offDayRemark . ', ';
                        }
                        $offDayRemark = $offDayRemark. 'Tuesday';
                    }
                    
                    if( $jsonArr['Wednesday'] == '1' ){
                        if( !empty($offDayRemark)){
                            $offDayRemark = $offDayRemark . ', ';
                        }
                        $offDayRemark = $offDayRemark. 'Wednesday';
                    }
                    
                    if( $jsonArr['Thursday'] == '1' ){
                        if( !empty($offDayRemark)){
                            $offDayRemark = $offDayRemark . ', ';
                        }
                        $offDayRemark = $offDayRemark. 'Thursday';
                    }
                    
                    if( $jsonArr['Friday'] == '1' ){
                        if( !empty($offDayRemark)){
                            $offDayRemark = $offDayRemark . ', ';
                        }
                        $offDayRemark = $offDayRemark. 'Friday';
                    }
                    
                    if( $jsonArr['Saturday'] == '1' ){
                        if( !empty($offDayRemark)){
                            $offDayRemark = $offDayRemark . ', ';
                        }
                        $offDayRemark = $offDayRemark. 'Saturday';
                    }
                    
                    if( $jsonArr['Sunday'] == '1' ){
                        if( !empty($offDayRemark)){
                            $offDayRemark = $offDayRemark . ', ';
                        }
                        $offDayRemark = $offDayRemark. 'Sunday';
                    }
                    
                    $offDayRemark = empty($offDayRemark) ? 'N/A' : $offDayRemark;
                    
                    $body .= '<tr>';
                    $body .= '<td>Off Day</td>';
                    $body .= '<td>'.$offDayRemark.'</td>';
                    $body .= '</tr>';
                }
                
                
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