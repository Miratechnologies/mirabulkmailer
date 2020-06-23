<?php

   $inputs = $_POST;

   // Get Inputs
   $id = $inputs['id'];
   $firstname = $inputs['firstname'];
   $lastname = $inputs['lastname'];
   $email = $inputs['email'];
   $telephone = $inputs['telephone'];
   $status = $inputs['status'];

   include 'dbmodel.php';
   $model = new DBModel();
   // add the template to the draft
   $add = $model->updateAudience($id,$firstname,$lastname,$email, $telephone, $status);

   if ($add === true) {
      exit(json_encode([
         "flag"=>true
      ]));
   } else {
      exit(json_encode([
         "flag"=>false
      ]));
   }

?>