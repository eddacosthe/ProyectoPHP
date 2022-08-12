<!DOCTYPE html>
<?php
$usuario = $_POST["usuario"];
$password = $_POST["password"];
$abastemex = $_POST["abastemex"];

//Creo una funcion para el permiso
function permiso($usuario,$conn,$Pagina){
    $SQL = "select  * from USRPROCESOS where cve_modulo = 7 and usr_nom_proceso = 'mnugerenciales' and grl_usuario = '$usuario'";
    $params = array();
    $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $stmt = sqlsrv_query($conn, $SQL, $params, $options);
    $row_count = sqlsrv_num_rows($stmt);
    if($row_count > 0){
        echo "<script>
        window.location='$Pagina'
        </script>";
    }else{
      echo  "<script>
                alert('Lo siento, tu usuario no cuenta con los permisos requeridos, verifica con el area de sistemas');
                window.location='../index.html'
            </script>";
    }
}

if ($abastemex == "on"){ //si el checkbox esta marcado entramos a abastemex
    session_start();
    $_SESSION["usuario"] =$usuario;
    $_SESSION["password"] =$password;
    $Pagina = "abastemex.php";
    require("conexion2.php");
    //Verificamos la conexion
    if($conn){
        permiso($usuario,$conn,$Pagina); //ejecuto la funcion
    

    }else{//si no hay conexion entonces retornamos al login eliminando la sesion
    echo "<script>
    alert('no se realizo la conexion, verifique usuario y contraseña');
    window.location='../index.html'
    </script>";
    session_destroy();

}

    
}else{
    session_start();
    
    $_SESSION["usuario"] =$usuario;
    $_SESSION["password"] =$password;
    $Pagina = "Principal.php";
    //

    require("conexion.php");//Verifico la conexion
    if($conn){
        permiso($usuario,$conn,$Pagina); //ejecuto la funcion
    }else{
        echo "<script>
            alert('no se realizo la conexion, verifique usuario y contraseña');
            window.location='../index.html'
            </script>";
    session_destroy();

}
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>

        body{
            background: #ffe259;
            background: linear-gradient(to right, #ffa751,#ffe259);
        }

        .bg{
            background-image: url(img/Ventas2.jpg);
            background-position: center center;
        }
    </style>
</head>
<body>
    
</body>
</html>

<?php
    }
?>