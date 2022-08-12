<?php 
session_start();
$usuario = $_SESSION["usuario"];
$password = $_SESSION["password"];
if(!$_SESSION["usuario"] == NULL){
    require_once("conexion.php");
    require_once("header.php");
    $sucursal = "select Cve_Sucursal,Nombre from GN_Sucursales where Cve_Sucursal < 80";
    $params = array();
    $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $stmtT = sqlsrv_query($conn, $sucursal, $params, $options);
    
?>


<div class="container w-80 bg-primary mt-3 rounded shadow">
  <div class="row align-items-stretch">
    <div class="col bg-white p-5 rounded-end">
        <h2 class="fw-bold text-center py-5">Reporte Por Sucursal</h2>
          <form method="POST" action="#">
            <div class="mb-4">
            <label for = "fecha" class="form-label">Fecha 1 </label>
            <input type="date" name="fecha1" class="form-control"  required>
            <label for = "fecha" class="form-label">Fecha 2 </label>
            <input type="date" name="fecha2" class="form-control w-15"  required>
            <label for = "selector" class="form-label">Sucursal: </label>
            <select name ="sucursal" class="form-select" aria-label="Default select example">
              <option selected>Selecciona la Sucursal</option>
              <? WHILE($suc= sqlsrv_fetch_array($stmtT)){ ?> <!-- Probar con un Break a ver si funciona -->
              <option value=<?echo "$suc[0]";?>><?echo "$suc[1]";?></option>
              <? } ?>
            </select>
           <div class="d-grid">
            <button type="submit" class="btn btn-primary">Generar</button>
            </div>
            </div>
            </form>
            <?
            $sucursal = $_POST["sucursal"];
            $fecha1 =new DateTime($_POST["fecha1"]);
            $fecha1 = $fecha1->format("d/m/Y");
            $fecha2 =new DateTime($_POST["fecha2"]);
            $fecha2 = $fecha2->format("d/m/Y");
            $SQL="select * from VW_Reporte_Utilidades where Cve_Sucursal = '$sucursal' and  Fecha_Documento between'$fecha1' and '$fecha2' order by Fecha_documento ASC";
            $stmt = sqlsrv_query($conn, $SQL, $params, $options);
            $row_count = sqlsrv_num_rows($stmt);
            if($row_count > 0){
            ?>
            <span>Reporte de Venta de <? echo "$fecha1";?> a <? echo "$fecha2";?> sucursal <? echo "$sucursal";?>  </span>
            <table class="table table-dark table-hover table-responsive mb-4 w-80 mt-5 rounded shadow">
              <thead>
                <tr>
                <th scope="col">Sucursal</th>
                <th scope="col">Nombre</th>
                <th scope="col">Importe</th>
                <th scope="col">Devolucion</th>
                <th scope="col">Venta Total</th>
                <th scope="col">Utilidad</th>
                <th scope="col">% utilidad</th>
                <th scope="col">Fecha</th>
              
                </tr>
              </thead>
            <? WHILE($row = sqlsrv_fetch_array($stmt)){ 
              $fe = $row[7];
              $fe = $fe->format("d/m/Y");
              
              ?>
              <tbody>
                <tr>
                  <th scope="row"><?=$row[0]?></th>
                  <td style="text-align:left;"><?=$row[1]?></td>
                  <td style="text-align:right;">$<?=(float)round($row[2],2)?></td>
                  <td style="text-align:right;">$<?=round($row[3],2)?></td>
                  <td style="text-align:right;">$<?=round($row[4],2)?></td>
                  <td style="text-align:right;">$<?=round($row[5],2)?></td>
                  <td style="text-align:right;"><?=$row[6]?>%</td>  
                  <td style="text-align:center;"><?=$fe?></td> 
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