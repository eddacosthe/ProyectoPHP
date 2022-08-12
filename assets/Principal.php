<?php
session_start();
$usuario = $_SESSION["usuario"];
$password = $_SESSION["password"];
if(!$_SESSION["usuario"] == NULL){
  require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Quezada</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
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
    <header>
      <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="../img/Logo.png" alt="" width="40" height="34">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">General</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="sucursal.php">Por Sucursal</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="sessionlog.php">cerrar sesion</a>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>
    <div class="container w-80 bg-primary mt-3 rounded shadow">
        <div class="row align-items-stretch">
            <div class="col bg-white p-5 rounded-end">
                <h2 class="fw-bold text-center py-5">Reporte General Mostrador</h2>
                <div class="text-start">
                    <form method="POST" action="#">
                    <div class="mb-4">
                   <label for = "fecha" class="form-label" >Introduce la fecha </label>
                   <input type="date" name="fecha" class="form-control"  required>
                   <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Generar</button>
                </div>
                </div>
                    </form>
                    <?
                    $fecha =new DateTime($_POST["fecha"]);
                    $fecha = $fecha->format("d/m/Y");
                    $SQL="select * from VW_Reporte_Utilidades where Fecha_Documento ='$fecha'";
                    $totales="select SUM(Venta_Total) from VW_Reporte_Utilidades where Fecha_Documento ='$fecha'";
                    $params = array();
                    $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
                    $stmt = sqlsrv_query($conn, $SQL, $params, $options);
                    $stmtT = sqlsrv_query($conn, $totales, $params, $options);
                    $tot = sqlsrv_fetch_array($stmtT);
                    $row_count = sqlsrv_num_rows($stmt);
                    if($row_count > 0){
                    ?>
                    <span>Reporte de Venta del <? echo "$fecha";?> </span>
                    <table class="table table-dark table-hover table-responsive mb-4 w-70 mt-5 rounded shadow"">
                      <thead>
                        <tr>
                        <th scope="col">Sucursal</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Importe</th>
                        <th scope="col">Devolucion</th>
                        <th scope="col">Venta Total</th>
                        <th scope="col">Utilidad</th>
                        <th scope="col">% utilidad</th>
                      
                        </tr>
                      </thead>
                    <? WHILE($row = sqlsrv_fetch_array($stmt)){ ?>
                      <tbody>
                        <tr>
                          <th scope="row"><?=$row[0]?></th>
                          <td style="text-align:left;"><?=$row[1]?></td>
                          <td style="text-align:right;">$<?=(float)round($row[2],2)?></td>
                          <td style="text-align:right;">$<?=round($row[3],2)?></td>
                          <td style="text-align:right;">$<?=round($row[4],2)?></td>
                          <td style="text-align:right;">$<?=round($row[5],2)?></td>
                          <td style="text-align:right;"><?=$row[6]?>%</td>   
                          <?
                              } //Cierre del While
                          ?>
                        </tr> 
                      </tbody>
                    </table>           
                      <a href="#link" class="btn btn-info" role="button">Descarga el Reporte</a>
                    <?
                  }//cierre del IF
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="../css/bootstrap.bundle.min.js"></script>
  </body>
</html>







<?    
}
else{
    echo  "<script>
                alert('Lo siento, Necesitas una sesion');
                window.location='../index.html'
            </script>";
            session_destroy();
}


