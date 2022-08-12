<?php
session_start();
$usuario = $_SESSION["usuario"];
$password = $_SESSION["password"];
if(!$_SESSION["usuario"] == NULL){
  require_once("conexion2.php");
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Abastemex</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>

        body{
            background: #d5dbe8;
            background: linear-gradient(to right, #31537a,#d5dbe8);
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
            <img src="../img/abmx.png" alt="" width="40" height="34">
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
                <a class="nav-link" href="#">Por Sucursal</a>
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
    <div class="container w-80 bg-primary mt-5 rounded shadow">
        <div class="row align-items-stretch">
            <div class="col bg-white p-5 rounded-end">
                <h2 class="fw-bold text-center py-5">Reporte General Supermercado</h2>
                <div class="text-start">
                    <form method="POST" action="#">
                    <div class="mb-4">
                   <label for = "fecha" class="form-label">Introduce la fecha </label>
                   <input type="date" name="fecha" class="form-control"  required>
                   <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Generar</button>
                </div>
                </div>
                    </form>
                    <?
                    $fecha =new DateTime($_POST["fecha"]);
                    $fecha = $fecha->format("d/m/Y");
                    $SQL="select U.Cve_Sucursal,Nombre,SUM(VentaTotal) AS Venta,SUM(CostoTotal) as CostoTotal,SUM(Utilidad) as Utilidad,
                    Abono,Cargo 
                    from VW_rpt_CostosVentaUtilidad as U inner join VW_rpt_Ventas_Rem_Mon as V
                    ON V.Cve_Sucursal = U.Cve_Sucursal and V.Fecha_Documento = U.Fecha
                     where Fecha = '$fecha' 
                     GROUP BY U.Cve_Sucursal,Nombre, Abono,Cargo";
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
                    <table class="table table-dark table-hover table-responsive mb-4">
                      <thead>
                        <tr>
                        <th scope="col">Sucursal</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Venta</th>
                        <th scope="col">Costo Total</th>
                        <th scope="col">Utilidad</th>
                        <th scope="col">Abono</th>
                        <th scope="col">Cargo a Monedero</th>
                        </tr>
                      </thead>
                    <? WHILE($row = sqlsrv_fetch_array($stmt)){ ?>
                      <tbody>
                        <tr>
                          <th scope="row"><?=$row[0]?></th>
                          <td style="text-align:left;"><?=$row[1]?></td>
                          <td style="text-align:center;">$<?=(float)round($row[2],2)?></td>
                          <td style="text-align:center;">$<?=round($row[3],2)?></td>
                          <td style="text-align:center;">$<?=round($row[4],2)?></td> 
                          <td style="text-align:center;">$<?=round($row[5],2)?></td>  
                          <td style="text-align:center;">$<?=round($row[6],2)?></td>    
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


echo "";