<?php
class validations {
    
    public function checkemail($email){    
    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
      //$emailErr = "Invalid email format";
    return true;
  }
    return false;
  
    }
    
    public function checkphone($phone){
        if(!preg_match("/^[56789]\d{9}$/",$phone)){
            return true;
        }
         return false;
    }
    
    public function checknoonly($input){
        
    }
    
    public function checkchar($input){
        
    }
    
    
     public function checkdate_self($date){
        $month_array=array(
                     "01"=>"Jan",
                     "02"=>"Feb",
                     "03"=>"Mar",
                     "04"=>"Apr",
                     "05"=>"May",
                     "06"=>"Jun",
                     "07"=>"Jul",
                     "08"=>"Aug",
                     "09"=>"Sep",
                     "10"=>"Oct",
                     "11"=>"Nov",
                     "12"=>"Dec"
        );
        
        if(!empty($date) && $date!="0.00" && $date!="0000-00-00" && $date!="0" ){
            $date_ex=explode("-",$date);
            $day=$date_ex[0];
            $month=array_search($date_ex[1],$month_array);
            $year=$date_ex[2];
            
            if(checkdate ($month,$day,$year) && $year>=1949 && $year<=2020){
               return false; 
            }
        }
        
        
        return true;
     }
     
     
     
     public function checkyear_self($year){
        
        if(!empty($year) && $year!="0.00" && $year!="0000" && $year!="0" ){
            
            if(substr($year, 0, 4)>=1950 && substr($year, 0, 4)<=2020){
              return false;  
            }
        }
        
        return true;
     }
     
    
    public function isblank($input){
     // echo "hello";die; 
        $input=trim($input);
        
        if(empty($input) || $input=="0.00" || $input=="0000-00-00" || $input=="0" ){
         
          return true;   
        }
         return false;
    }
    
    
    public function checkfloat($input){
     
     if(!is_numeric($input)){
        
        return true; 
        }
        return false;
    }
    
    public function checkint($input){
     
     if(!is_numeric($input)){
        return true; 
        }
        return false;
    }
    
    public function checkcharc($input){
     
     if(!preg_match("#^[a-zA-Z\s]+$#",$input)){
        return true; 
        }
        return false;
    }
    
    
    public function isblank_exp($input){
        
        $input=trim($input);
        
        if($input==""){
         
          return true;   
        }
        
         return false;
    }
    
    public function pincheck($pincode){
        
        if(!preg_match("/^([1-9])([0-9]){5}$/", $pincode)){
        return true;
        }
        
        return false;
        
    }
    
    public function filterinput($input){
        
    }
    
    public function datesformat($input){
        
    }
    
    public function datescheck($from,$to){
        
    }
    
    public function yearcheck($from,$to){
        
    }
    
}