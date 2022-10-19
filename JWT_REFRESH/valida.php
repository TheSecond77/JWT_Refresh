<?php

    require 'jwtclass.php';

    $user ="adminprogweb";
    $pass ="ProgWeb3";
    $db ="progweb3";
    $server ="127.0.0.1";
    $conn = mysqli_connect($server, $user, $pass, $db);

    if ($conn->connect_errno){

        die("Erro de conexão" . $conn->connect_error);

    }else {
        $token = $_POST['token'];

        $JWT = new myJWT;

        echo "<br>";
        echo "Validade do token: ";
        //verifica a assinatura do token
        if($JWT->validaToken($token)){
            //verifica a data de expiração do token
            if($JWT->expiraToken($token)){
                //verifica se o token já foi usado
                if($JWT->blacklist($conn, $token) == 0){
                    echo "válido";
                    $novosTokens = $JWT->utilizaRefresh($conn, $token);
                        if($novosTokens != 0){
                            echo "<br>";
                            echo "refresh token utilizado";
        
                            $accessToken = $novosTokens[0];
                            $refreshToken = $novosTokens[1];
                            
                            echo "<br>";
                            echo "Novo access token: ".$accessToken;
                            echo "<br>";
                            echo "Novo refresh token: ".$refreshToken;
                        }
                }else{
                    echo "Refresh token já utilizado";
                }
            }else{
                echo "expirado";
            }
        }else{
            echo "inválido";
        }

        echo "<br>";
        echo "<a href='index.html'><button>Voltar</button><a>";
    }
?>