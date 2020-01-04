<?php

    include 'koneksi.php';
    $query  = mysqli_query($koneksi,"SELECT * FROM gejala")

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Naive Bayes - Penyakit Mata</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap4/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap3/bootstrap.min.css" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">(o_o) Identifikasi Penyakit Mata dengan Algoritma Naive Bayes (o_o)</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container">

    <div class="row">

      <div class="col-lg-3">
        <h3 class="my-4"></h3>
        <div class="list-group">
          <a href="index.php" class="list-group-item active">Konsultasi</a>
          <a href="penyakit.php" class="list-group-item">Data Penyakit</a>
          <a href="gejala.php" class="list-group-item">Data Gejala</a>
          <a href="dataset.php" class="list-group-item">Dataset</a>
        </div>
      </div>
      <!-- /.col-lg-3 -->

      <div class="col-lg-9">
        <div class="card mt-4">
        <center><h3>HASIL ANALISA NAIVE BAYES</h3></center>
          <?php
            $query_penyakit     = mysqli_query($koneksi,"SELECT COUNT(Kode_Penyakit) AS jumlah_penyakit FROM penyakit");
            $query_gejala       = mysqli_query($koneksi,"SELECT COUNT(Kode_Gejala) AS jumlah_gejala FROM gejala");
            $result_penyakit    = mysqli_fetch_assoc($query_penyakit);
            $result_gejala      = mysqli_fetch_assoc($query_gejala);
            $JmlPenyakit        = $result_penyakit['jumlah_penyakit'];
            $JmlGejala          = $result_gejala['jumlah_gejala'];
            $PeluangPenyakit    = 1/$JmlPenyakit;
        
            //gejala
            $Gejala=array();
            $i=0;
            for($a=0;$a<$JmlGejala;$a++)
            {
                if ( (isset($_POST['gejala'][$a])>0) && ($_POST['gejala'][$a]) )
                {
                $Gejala[$i]=$_POST['gejala'][$a];
                $i++;
                }
            }
            if ($i<1)
            {
                echo '<div class="alert alert-warning">Tidak ada gejala yang diisi!</div>';
            }
            else
            {
                echo '<table class="table table-striped table-bordered">';
                echo '<tr><td>Jumlah Penyakit: '.$JmlPenyakit.'</td>';
                echo '<td>Jumlah Total Gejala : '.$JmlGejala.'</td></tr>';
                echo '<tr><td>Peluang Penyakit : '.$PeluangPenyakit.'</td>';
                echo '<td>Jumlah Gejala yang teralami : '.$i.'</td></tr></table>';
        
                $x=0;
                $query_data=mysqli_query($koneksi,"SELECT Kode_Penyakit, Nama_Penyakit,Kode_Gejala FROM penyakit ORDER BY Kode_Penyakit");
                $result_query=mysqli_num_rows($query_data);
                if ($result_query==0)
                {
                    $Penyakit=array('No Data','No Data');
                    echo '<div class="alert alert-warning"> Tidak ada penyakit!</div>';
                }
                else
                {
                    echo '<div class="row">';
                    $PeluangPenyakit=1/$JmlPenyakit;
        
            //hitung pada tiap gejala
                    $Total=array();
                    for ($a=0; $a<$result_query;$a++)
                    {
                        $DataPenyakit=mysqli_fetch_assoc($query_data);
                        
                        
        
                        echo '<div class="col-sm-4">
                        <div class="thumbnail">';
                        echo '<p style="margin-top:0px;"><b>'.($a+1).' | '.$DataPenyakit['Nama_Penyakit'].'</b></p>';
                        
        
                        $P=1;
                        foreach($Gejala as $g)
                        {
                            $cek=stripos($DataPenyakit['Kode_Gejala'],$g);
                            $nc=1;
                            
                            if($cek===false)
                            {
                                $nc=0;
                            }
        
                           
                            echo '<p>NC ('.$g.') : '.$nc.'<br/>';                   
        
                            $Prob=($nc+$JmlGejala * $PeluangPenyakit)/(1+$JmlGejala);
                            
                            echo 'P ('.$g.') : '.$Prob;
                            
                            $P=$P*$Prob;
                            
                        }
        
                        $Total[$a]['Probabilitas']=$P*$PeluangPenyakit;
                        $Total[$a]['Kode_Penyakit']=$DataPenyakit['Kode_Penyakit'];
                        $Total[$a]['Nama_Penyakit']=$DataPenyakit['Nama_Penyakit'];
                        
                        echo '<p><b>P (' .$Total[$a]['Kode_Penyakit'].') : </b>'.$Total[$a]['Probabilitas'].'</p>';
                        echo '<hr>';
                        echo '</div></div>';
                    }
        
            //sort hasil
                    $prob=array();
                    $idpenyakit=array();
                    $penyakit=array();
                    
                    foreach ($Total as $key => $row) 
                    {
                        $idpenyakit[$key] = $row['Kode_Penyakit'];
                        $penyakit[$key] = $row['Nama_Penyakit'];
                        $prob[$key] = $row['Probabilitas'];
                    }
                    array_multisort($prob, SORT_DESC, $Total);
                    
                    $n=1;
                    $NBPenyakit=array($Total[0]['Kode_Penyakit'],$Total[0]['Nama_Penyakit']);
            ?>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Probabilitas</h5>
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th>PENYAKIT</th>
                                        <th class="text-right">PROBABILITAS</th>
                                    </tr>
                                    <?php
                                    $cnt=0;
                        
                                    foreach($Total as $t)
                                    {
                                        echo "<tr>
                                                <td>".$t['Nama_Penyakit'].'</td>';
                                        echo '<td class="text-right"><span class="label ';
                        
                                        if ($cnt==0)
                                        {
                                            echo 'label-danger';
                                        }
                                        else if ($cnt==1)
                                        {
                                            echo 'label-warning';
                                        }
                                        else
                                        {
                                            echo 'label-default';
                                        }
                                        echo '">'.$t['Probabilitas'].'</span></td>
                                        </tr>';
                                        $cnt++;
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h5>KESIMPULAN</h5>
                            </div>
                            <div class="panel-body">
                                <p class="text-center">
                                    <b>Penyakit : <?php echo $NBPenyakit[1];?></b>
                                </p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
        ?>
        </div>
        <!-- /.card -->

      </div>
      <!-- /.col-lg-9 -->

    </div>

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; AI 2019</p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
