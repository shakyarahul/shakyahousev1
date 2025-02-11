<?php
class AttendanceCalendar {  
     
    /**
     * Constructor
     */
    public function __construct(){     
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }
     
    /********************* PROPERTY ********************/  
    private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
     
    private $currentYear=0;
     
    private $currentMonth=0;
     
    private $currentDay=0;
     
    private $currentDate=null;
     
    private $daysInMonth=0;
     
    private $naviHref= null;
     
    /********************* PUBLIC **********************/  
        
    /**
    * print out the calendar
    */
    public function show() {
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y', time());
        $month = isset($_GET['month']) ? $_GET['month'] : date('m', time());
            $userData = json_decode(file_get_contents("http://kusumshakya.com.np/project/shakyahousev1/api/client/"),true);
            $staffs=array();
            foreach ($userData as $key => $value) {
				if($value['roles'] == "STAFF"){
					$staffs[$value['userId']] = $value['name'];
				}
            }
        $staffs = array_unique($staffs);
        $attendance = json_decode(file_get_contents("http://kusumshakya.com.np/project/shakyahousev1/api/staff/"),true);
                                                                                      

        if(null==$year&&isset($_GET['year'])){
 
            $year = $_GET['year'];
         
        }else if(null==$year){
 
            $year = date("Y",time());  
         
        }          
         
        if(null==$month&&isset($_GET['month'])){
 
            $month = $_GET['month'];
         
        }else if(null==$month){
 
            $month = date("m",time());
         
        }                  
         
        $this->currentYear=$year;
         
        $this->currentMonth=$month;
         
        $this->daysInMonth=$this->_daysInMonth($month,$year);  
         
        $content=''.
                '<table class="table table-sm">'.
'<thead>';      
                                 
                                $weeksInMonth = $this->_weeksInMonth($month,$year);
                                
                            
                               $content.="<tr>";
                               $content.="<td>"."</td>";
                            
                                // Create weeks in a month
                                for( $i=0; $i<$weeksInMonth; $i++ ){
                                
                                $booked = array(); 
                                    //Create days in a week
                                    for($j=1;$j<=7;$j++){
///////
                                        $day = $this->_showDay($i*7+$j);
                                        if(!empty($day)){ 
                                            
                                            $content.="<th title='".$year." ".$month." ".$day."' id='data-".$day."' style='background-color:#fff' >".$day."</td>";               
                                            
                                        }
                                    }
                                
                                }
                                $this->currentDay = 0;
                                $content.="</tr>"; 
                            
                                $content.='</thead>';



                '<tbody>';      
                                 
                                $weeksInMonth = $this->_weeksInMonth($month,$year);
                                
                            foreach ($staffs as $staff => $staffname) {

                               $content.="<tr>";
                               $content.="<td>".$staffname."</td>";
                            
                                // Create weeks in a month
                                for( $i=0; $i<$weeksInMonth; $i++ ){
                                
                                $booked = array(); 
                                    //Create days in a week
                                    for($j=1;$j<=7;$j++){
///////
                                        $day = $this->_showDay($i*7+$j);
                                        if(!empty($day)){ 
                                            $calendartimestamp = strtotime($year."-".$month."-".$day);
                                            $isBooked = array();
											
                                            foreach ($attendance as $key => $value) {
                                               // echo (strtotime($value["checkIn"])<=$calendartimestamp)." ".(strtotime($value["checkOut"])>= $calendartimestamp)."<br/>";
                                                if($staff == $value['user'] && $calendartimestamp == strtotime(date('Y-m-d',strtotime($value['datetime'])))){
                                                    array_push($isBooked, true);
                                                }
                                            }
                                               if(in_array(true,$isBooked)){
                                                    $content.="<td><i class='fa fa-check'></i></td>";
                                               }else {
                                                   $content.="<td>"."</td>";
                                               }
                                               $isBooked=array();
                                            
                                        }
                                    }
                                
                                }
                                $this->currentDay = 0;
                                $content.="</tr>"; 
                            }
                                $content.='</tbody><tfoot>';

                                    $content.=$this->_createNavi();
                                    $content.='</tfoot>';

                                //header finishes
                
                                 
                                $content.=' </table>';     
             
        return $content;   
    }
    /********************* PRIVATE **********************/ 
    /**
    * create the li element for ul
    */
    private function _showDay($cellNumber){
         
        if($this->currentDay==0){
             
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
                     
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                 
                $this->currentDay=1;
                 
            }
        }
         
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
             
            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
             
            $cellContent = $this->currentDay;
             
            $this->currentDay++;   
             
        }else{
             
            $this->currentDate =null;
 
            $cellContent=null;
        }
             
         
       // return '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')). ($cellContent==null?'mask':'').'">'.$cellContent.'</li>';

        return $cellContent;
    }
     
    /**
    * create navigation
    */
    private function _createNavi(){
         
        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
         
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
         
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
         
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
         
        return
            '<tr><td colspan="2">'.
                '<button type="button" id="prev" onclick=\'loadmore("prev")\' value="'.sprintf('%02d',$preMonth).'='.$preYear.'"><<</a></td>
                                            <td colspan="5">'.date('Y M',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</td>
                                            <td colspan="2">'.
                '<button type="button" id="next" onclick=\'loadmore("next")\' value="'.sprintf("%02d", $nextMonth).'='.$nextYear.'">>></a>'.'</td>
                                        </tr>';
    }
         
    /**
    * create calendar week labels
    */
    private function _createLabels(){  
                 
        $content=' <thead>
                                        <tr>
                                            ';
         
        foreach($this->dayLabels as $index=>$label){
             
            $content.='<td>'.$label.'</td>';
 
        }
          $content.=' </tr>
                                        </thead>
                                            ';
        return $content;
    }
     
     
     
    /**
    * calculate number of weeks in a particular month
    */
    private function _weeksInMonth($month=null,$year=null){
         
        if( null==($year) ) {
            $year =  date("Y",time()); 
        }
         
        if(null==($month)) {
            $month = date("m",time());
        }
         
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);
         
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
         
        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
         
        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
         
        if($monthEndingDay<$monthStartDay){
             
            $numOfweeks++;
         
        }
         
        return $numOfweeks;
    }
 
    /**
    * calculate number of days in a particular month
    */
    private function _daysInMonth($month=null,$year=null){
         
        if(null==($year))
            $year =  date("Y",time()); 
 
        if(null==($month))
            $month = date("m",time());
             
        return date('t',strtotime($year.'-'.$month.'-01'));
    }
     
}
