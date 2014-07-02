<?php
  if(isset($_POST['acao'])){
    if( @$_POST['criterio_1'] != NULL && $_POST['criterio_2'] != NULL && $_POST['criterio_3'] != NULL && $_POST['criterio_4'] != NULL && 
            $_POST['criterio_5'] != NULL && $_POST['criterio_6'] != NULL && $_POST['criterio_7'] != NULL && $_POST['criterio_8'] != NULL && 
            $_POST['criterio_9'] != NULL && $_POST['criterio_10'] != NULL && $_POST['criterio_11'] != NULL && $_POST['criterio_12'] != NULL &&
            $_POST['criterio_13'] != NULL && $_POST['criterio_14'] != NULL && $_POST['criterio_15'] != NULL) { 
        
     $ct1 = $_POST['criterio_1'];
     $ct2 = $_POST['criterio_2']; 
     $ct3 = $_POST['criterio_3']; 
     $ct4 = $_POST['criterio_4']; 
     $ct5 = $_POST['criterio_5']; 
     $ct6 = $_POST['criterio_6']; 
     $ct7 = $_POST['criterio_7']; 
     $ct8 = $_POST['criterio_8']; 
     $ct9 = $_POST['criterio_9']; 
     $ct10 = $_POST['criterio_10']; 
     $ct11 = $_POST['criterio_11'];
     $ct12 = $_POST['criterio_12']; 
     $ct13 = $_POST['criterio_13'];
     $ct14 = $_POST['criterio_14'];
     $ct15 = $_POST['criterio_15'];
     
     /*echo $ct1."<br/>";
     echo $ct2."<br/>";
     echo $ct3."<br/>";
     echo $ct4."<br/>";
     echo $ct5."<br/>";
     echo $ct6."<br/>";
     echo $ct7."<br/>";
     echo $ct8."<br/>";
     echo $ct9."<br/>";
     echo $ct10."<br/>";
     echo $ct11."<br/>";
     echo $ct12."<br/>";*/
     
     
     $somaform1 = $ct1+$ct2+$ct3+$ct4+$ct5+$ct6+$ct7+$ct8+$ct9+$ct10+$ct11+$ct12+$ct13+$ct14+$ct15;
     
     if($somaform1 >= 43 && $somaform1 <= 100){
         echo "CLASSE: A TOTAL: ".$somaform1; 
         
     
     }else if($somaform1 >= 37 && $somaform1 <= 42 ){
          echo "CLASSE: B1 TOTAL: ".$somaform1; 
         
         
     }else if($somaform1 >= 26 && $somaform1 <= 36 ){
         echo "CLASSE: B2 TOTAL: ".$somaform1; 
         
         
     }else if($somaform1 >= 19 && $somaform1 <= 25 ){
          echo "CLASSE: C1 TOTAL: ".$somaform1;
         
         
     }else if($somaform1 >= 15 && $somaform1 <= 18 ){
          echo "CLASSE: C2 TOTAL: ".$somaform1; 
         
         
     }else if($somaform1 >= 11 && $somaform1 <= 14 ){
          echo "CLASSE: D TOTAL: ".$somaform1;
         
     }else{
          echo "CLASSE: E TOTAL: ".$somaform1; 
         
     }
     

    }else{
       echo "<script>alert('Preencha todos os campos!!');</script>"; 
      // echo "<script>history.back();</script>";
        
    }
    
  }
?>

