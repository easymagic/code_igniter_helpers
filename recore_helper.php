<?php 

 function start_buffer(){
  ob_start();
 }

 function get_buffer(){
  $r = ob_get_contents();
  ob_end_clean();
  return $r;
 }


 function response($k,$v=''){
   
   static $resp = array();
   
   if (is_array($v) || is_numeric($v) || !empty($v)){
     $resp[$k] = $v;
   }

   if ($v == '__unset__'){
    unset($resp[$k]);
   }

   if (isset($resp[$k])){
    return $resp[$k];
   }else{
    return '';
   } 

 }

 function request($k,$v=''){
   
   static $resp;

   if (!isset($resp)){
    $resp = &$_REQUEST;
   }
   
   if (is_array($v) || is_numeric($v) || !empty($v)){
     $resp[$k] = $v;
   }

   if ($v == '__unset__'){
    unset($resp[$k]);
   }

   if (isset($resp[$k])){
    return $resp[$k];
   }else{
    return '';
   } 

 }

 function post($k,$v=''){
   static $resp;

   if (!isset($resp)){
    $resp = &$_POST;
   }

   
   if (is_array($v) || is_numeric($v) || !empty($v)){
     $resp[$k] = $v;
   }


   if ($v == '__unset__'){
    unset($resp[$k]);
   }


   if (isset($resp[$k])){
    return $resp[$k];
   }else{
    return '';
   } 
 }

 function session($k,$v=''){
   static $resp;

   if (!isset($resp)){
    $resp = &$_SESSION;
   }
   
   if (is_array($v) || is_numeric($v) || !empty($v)){
     $resp[$k] = $v;
   }

   if ($v == '__unset__'){
    unset($resp[$k]);
   }

   if (isset($resp[$k])){
    return $resp[$k];
   }else{
    return '';
   }

 }
 

 function controller($v=null){
   
   static $resp;

   if (!isset($resp) && $v != null){
    $resp = $v;
   }

  
   return $resp;   

 }


 //base_url() . 'cdn/site2/assets/';

 function env($k,$v=''){
   static $resp = array();

   // if (!isset($resp)){
   //  $resp = &$_SESSION;
   // }
   
   if (is_array($v) || is_numeric($v) || !empty($v)){
     $resp[$k] = $v;
   }

   if ($v == '__unset__'){
    unset($resp[$k]);
   }

   if (isset($resp[$k])){
    return $resp[$k];
   }else{
    return '';
   }

 }


 function db_($db=null){
  static $instance = null;
  if ($db != null){
    $instance = $db;
  }
  return $instance;
 }


 interface iuse_case{

    function get_input($input);
    function exec();
    function get_output();
    function audit();

 }




 function call_usecase($path,$input,&$output){

 	try {

     $salt = 'mdl_' . uniqid();
     $model = controller()->load->model('use_cases/' . $path,$salt);
     
     if (method_exists(controller()->$salt, 'get_input')){
       controller()->$salt->get_input($input);       
     }

     if (method_exists(controller()->$salt, 'exec')){
       controller()->$salt->exec();
     }

     if (method_exists(controller()->$salt, 'get_output')){
      $output = controller()->$salt->get_output();
     }
     

 	 $output['error'] = false;

 	} catch (Exception $e) {

 		$output['error'] = true;
 		$output['message'] = $e->getMessage();
 		
 	}

 }





 //template utils
 function template_start(){
  start_buffer();
 }

 function template_get($key,$val=''){
   static $templs = array();
   if (!empty($val)){
      $templs[$key] = $val;
   }
   return $templs[$key];
 }

 function template_stop($template_name){
   template_get($template_name,get_buffer());
 }


 function action_watch(){

 	if (isset($_REQUEST['ccmd'])){

 		$ccmd = $_REQUEST['ccmd'];

 		unset($_REQUEST['ccmd']);

 		$input = $_REQUEST;
 		$output = array();
 		call_usecase($ccmd,$input,$output);

 		foreach ($output as $k=>$v){
          $_SESSION[$k] = $v;
 		}

 	}

 	// print_r(db_());

 }

 function get_toast_message(){
 	$msg = '';
 	if (isset($_SESSION['message'])){
     $msg = $_SESSION['message'];
     unset($_SESSION['message']);
 	}
 	return $msg;
 }


 function get_toast_error(){
 	$err = false;
 	if (isset($_SESSION['error'])){
     $err = $_SESSION['error'];
     unset($_SESSION['error']);
 	}
 	return $err;
 }

