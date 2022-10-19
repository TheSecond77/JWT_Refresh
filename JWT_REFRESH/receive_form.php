<html>
<body>
<?php

    require 'jwtclass.php';

    //utilizacao da classe do JWT
    $myjwt = new myJWT();

    //info do servidor
    $user = "adminprogweb";
    $pass = "ProgWeb3";
    $db = "progweb3";
    $server = "127.0.0.1";

    //conectar com servidor
    $conn = mysqli_connect($server, $user, $pass, $db);
    if ($conn->connect_errno){
        die("Erro de conexão" . $conn->connect_error);
    } 
    
    //informacoes do token
    $idUsuario = $_POST["usuario"];
    $senhaUsuario = $_POST["senha"];

    //chamar banco de dados
    $sql = "select * from usuarios where idusuario = '". $idUsuario ."' and senhausuario = '". $senhaUsuario ."'";
    $resultadoQuery = mysqli_query($conn, $sql);

    //verificacao dos dados
    if ($resultadoQuery->num_rows == 0 ){
        die("usuário ou senha inválidos");
    }


    $arrayQuery = $resultadoQuery->fetch_assoc();

    echo "<BR>";
    echo "usuário digitado: " . $arrayQuery["idusuario"];
    echo "<BR>";
    echo "<BR>";
    echo "senha digitada: " . $arrayQuery["senhausuario"];
    
    $expAccess = time() + (60*1);
 
    $payloadAcess = [
        'iss' => 'localhost',
        'nome' => $arrayQuery["nomeusuario"],
        'email' => $arrayQuery["email"],
        'exp' => $expAccess
    ];
    
    $expRefresh = time() + (60*5);

    $payloadRefresh = [
        'usuario' => $arrayQuery['nomeusuario'],
        'email' => $arrayQuery['email'],
        'exp' => $expRefresh
    ];




    $accessToken = $JWT->criaToken($payloadAccess);
    $refreshToken = $JWT->criaToken($payloadRefresh);
    
    //tokens gerados de acesso e refresh
    echo "<br>";
    echo 'Token gerado: '.$accessToken;
    echo "<br>";
    echo 'Refresh token gerado: '.$refreshToken;
    

    //validar acess token
    echo "<br>";
    echo "Validade do access token: ";
    if($JWT->validaToken($accessToken)){
        if($JWT->expiraToken($accessToken)){
            echo "válido";    
        }else{
            echo "expirado";
        }
    }else{
        echo "inválido";
    }

    echo "<br>";

    //Validar refresh Token
    echo "Validade do refresh token: ";
    if($JWT->validaToken($refreshToken)){
        if($JWT->expiraToken($refreshToken)){
            echo "válido";    
        }else{
            echo "expirado";
        }
    }else{
        echo "inválido";
    }

?>
</body>
</html>