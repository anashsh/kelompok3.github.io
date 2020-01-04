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
      <a class="navbar-brand" href="index.php">(o_o) Identifikasi Penyakit Mata dengan Algoritma Naive Bayes (o_o)</a>
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
          <form method="post" action="proses_analisis.php">
              <h2>Pilih Gejala - Gejala yang Dialami</h2>
              <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="th-sm">Kode Gejala
                        </th>
                        <th class="th-sm">Pilih
                        </th>
                        <th class="th-sm">Nama Gejala
                        </th>
                    </tr>
                </thead>
                <tbody>
              <?php
                  $a=0;
                  while($db_row = mysqli_fetch_array($query)) {
              ?>
                <tr>
                    <td>
                        <?php echo $db_row["Kode_Gejala"]; ?>
                    </td>
                    <td>
                        <input type="checkbox" name="gejala[]" value="<?php echo $db_row["Kode_Gejala"]; ?>">
                    </td>
                    <td>
                        <?php echo $db_row["Nama_Gejala"]; ?>
                    </td>
                </tr>
              <?php
                  $a++;
              }
              ?>
                </tbody>
            </table>
          <input type="submit" class="btn btn-success" name="submit" value="Analisis">
          </form>
          </div>
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
