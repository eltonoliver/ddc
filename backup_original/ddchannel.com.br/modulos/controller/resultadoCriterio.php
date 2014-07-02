<?php
if (isset($_POST['acao'])) {
    if (@$_POST['criterio_1'] != NULL && $_POST['criterio_2'] != NULL && $_POST['criterio_3'] != NULL && $_POST['criterio_4'] != NULL &&
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
        $somaform1 = $ct1 + $ct2 + $ct3 + $ct4 + $ct5 + $ct6 + $ct7 + $ct8 + $ct9 + $ct10 + $ct11 + $ct12 + $ct13 + $ct14 + $ct15;
        if ($somaform1 >= 43 && $somaform1 <= 100) {
            echo "<div style='padding:15px; width:750px; height:153px; margin:0 auto; text-align:center;'><span style='text-align: justify; display:block;'>O"
            . " critério atribui pontos em função de cada característica domiciliar e realiza a soma destes pontos. É feita então uma correspondência  entre faixas "
                    . "de pontuação do critério  e estratos de classificação econômica definidos por:  A1, A2, B1, B2, C1, C2, D, E .</span><br />"
                    . "<span style='font-weight:bold; font-size:20px;'>Sua classe é:</span><br><br><br> "
                    . "<h1 style='color:red; font-size:60px;'>A</h1><br><br> De acordo com essa somatária: " . $somaform1 ."</div>";
        } else if ($somaform1 >= 37 && $somaform1 <= 42) {
           echo "<div style='padding:15px; width:750px; height:153px; margin:0 auto; text-align:center;'><span style='text-align: justify; display:block;'>O"
            . " critério atribui pontos em função de cada característica domiciliar e realiza a soma destes pontos. É feita então uma correspondência  entre faixas "
                    . "de pontuação do critério  e estratos de classificação econômica definidos por:  A1, A2, B1, B2, C1, C2, D, E .</span><br />"
                    . "<span style='font-weight:bold; font-size:20px;'>Sua classe é:</span><br><br><br> "
                    . "<h1 style='color:red; font-size:60px;'>B1</h1><br><br> De acordo com essa somatária: " . $somaform1 ."</div>";
        } else if ($somaform1 >= 26 && $somaform1 <= 36) {
            echo "<div style='padding:15px; width:750px; height:153px; margin:0 auto; text-align:center;'><span style='text-align: justify; display:block;'>O"
            . " critério atribui pontos em função de cada característica domiciliar e realiza a soma destes pontos. É feita então uma correspondência  entre faixas "
                    . "de pontuação do critério  e estratos de classificação econômica definidos por:  A1, A2, B1, B2, C1, C2, D, E .</span><br />"
                    . "<span style='font-weight:bold; font-size:20px;'>Sua classe é:</span><br><br><br> "
                    . "<h1 style='color:red; font-size:60px;'>B2</h1><br><br> De acordo com essa somatária: " . $somaform1 ."</div>";
        } else if ($somaform1 >= 19 && $somaform1 <= 25) {
           echo "<div style='padding:15px; width:750px; height:153px; margin:0 auto; text-align:center;'><span style='text-align: justify; display:block;'>O"
            . " critério atribui pontos em função de cada característica domiciliar e realiza a soma destes pontos. É feita então uma correspondência  entre faixas "
                    . "de pontuação do critério  e estratos de classificação econômica definidos por:  A1, A2, B1, B2, C1, C2, D, E .</span><br />"
                    . "<span style='font-weight:bold; font-size:20px;'>Sua classe é:</span><br><br><br> "
                    . "<h1 style='color:red; font-size:60px;'>C1</h1><br><br> De acordo com essa somatária: " . $somaform1 ."</div>";
        } else if ($somaform1 >= 15 && $somaform1 <= 18) {
           echo "<div style='padding:15px; width:750px; height:153px; margin:0 auto; text-align:center;'><span style='text-align: justify; display:block;'>O"
            . " critério atribui pontos em função de cada característica domiciliar e realiza a soma destes pontos. É feita então uma correspondência  entre faixas "
                    . "de pontuação do critério  e estratos de classificação econômica definidos por:  A1, A2, B1, B2, C1, C2, D, E .</span><br />"
                    . "<span style='font-weight:bold; font-size:20px;'>Sua classe é:</span><br><br><br> "
                    . "<h1 style='color:red; font-size:60px;'>C2</h1><br><br> De acordo com essa somatária: " . $somaform1 ."</div>";
        } else if ($somaform1 >= 11 && $somaform1 <= 14) {
           echo "<div style='padding:15px; width:750px; height:153px; margin:0 auto; text-align:center;'><span style='text-align: justify; display:block;'>O"
            . " critério atribui pontos em função de cada característica domiciliar e realiza a soma destes pontos. É feita então uma correspondência  entre faixas "
                    . "de pontuação do critério  e estratos de classificação econômica definidos por:  A1, A2, B1, B2, C1, C2, D, E .</span><br />"
                    . "<span style='font-weight:bold; font-size:20px;'>Sua classe é:</span><br><br><br> "
                    . "<h1 style='color:red; font-size:60px;'>D</h1><br><br> De acordo com essa somatária: " . $somaform1 ."</div>";
        } else {
         echo "<div style='padding:15px; width:750px; height:153px; margin:0 auto; text-align:center;'><span style='text-align: justify; display:block;'>O"
            . " critério atribui pontos em função de cada característica domiciliar e realiza a soma destes pontos. É feita então uma correspondência  entre faixas "
                    . "de pontuação do critério  e estratos de classificação econômica definidos por:  A1, A2, B1, B2, C1, C2, D, E .</span><br />"
                    . "<span style='font-weight:bold; font-size:20px;'>Sua classe é:</span><br><br><br> "
                    . "<h1 style='color:red; font-size:60px;'>E</h1><br><br> De acordo com essa somatária: " . $somaform1 ."</div>";
        }
    } else {
        echo "campoVazio";
    }
}
?>