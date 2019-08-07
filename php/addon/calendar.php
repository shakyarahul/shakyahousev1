<?php

class Calendar {  
     
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

        $reserve = isset($_GET['rooms']) ? $_GET['rooms'] : "";
        $jsonArry = json_decode(file_get_contents("http://kusumshakya.com.np/project/shakyahousev1/api/booking/"),true);
        $reserveArry = array();
        
        foreach($jsonArry as $a => $v){
                if($v["roomNo"]== ($reserve) && $v["bookingId"] != $_GET['update_id'] ){
                    $newArry = array(
                            "checkIn" => $v['checkIn'],
                            "checkOut" => $v['checkOut']
                        );
                    array_push($reserveArry, $newArry);
                    
                }else{
                    
                }
        }
                                                                                      

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
                '<table class="table table-sm">'.$this->_createLabels().
                '<tbody>';      
                                 
                                $weeksInMonth = $this->_weeksInMonth($month,$year);
                                // Create weeks in a month
                                for( $i=0; $i<$weeksInMonth; $i++ ){
                                $content.="<tr>";
                                $booked = array(); 
                                    //Create days in a week
                                for($j=1;$j<=7;$j++){
                                    $day = $this->_showDay($i*7+$j);
                                    if(!empty($day)){ 
                                        foreach ($reserveArry as $value) {
                                           // echo strtotime($value['checkIn']) ."<=". strtotime($this->currentYear.'-'.$this->currentMonth.'-'.$day) ."&&". strtotime($value['checkOut']) .">=". strtotime($this->currentYear.'-'.$this->currentMonth.'-'.$day) ."<br>";
                                            //lies with so booked
                                            
                                            if(strtotime($value['checkIn']) <= strtotime($this->currentYear.'-'.$this->currentMonth.'-'.$day) && strtotime($value['checkOut']) >= strtotime($this->currentYear.'-'.$this->currentMonth.'-'.$day) ){
                                                    array_push($booked, true);
                                                    $content.="<td id='data-".$day."' style='background-color:#F00' >".$day."</td>";               
                                            }else{
                                                array_push($booked, false);
                                            }
                                        }
                                            if(!in_array(true, $booked)){
                                                    $content.="<td id='data-".$day."' style='background-color:#0F0' onclick=\"checked('".$day."')\">".$day."</td>";
                                            }
                                                    $booked = array();
                                            
                                        $content.="<input type='checkbox' id='checked-".$day."' name='checkInOut[]' style='display:none' value='".date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.$day))."' />";
                                    }else {
                                        $content.="<td></td>";
                                    }
                                }
                                $content.="</tr>";
                                }
                                 
                                $content.=$this->_createNavi().'</tbody>';
                                 
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
            '<tr><td colspan="1">'.
                '<button type="button" id="prev" onclick=\'loadmore("prev")\' value="'.sprintf('%02d',$preMonth).'='.$preYear.'"><<</a></td>
                                            <td colspan="5">'.date('Y M',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</td>
                                            <td>'.
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
