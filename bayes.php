<?php

    include 'koneksi.php';

    //jumlah penyakit & jumlah gejala
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
        echo '<div>Tidak ada gejala yang diisi!</div>';
    }
    else
    {
        echo '<table border="1">';
        echo '<tr><td>Jumlah Penyakit: '.$JmlPenyakit.'</td>';
        echo '<td>Jumlah Total Gejala : '.$JmlGejala.'</td></tr>';
        echo '<tr><td>Peluang Penyakit : '.$PeluangPenyakit.'</td>';
        echo '<td>Jumlah Gejala yang teralami: '.$i.'</td></tr></table>';

        $x=0;
        $query_data=mysqli_query($koneksi,"SELECT Kode_Penyakit, Nama_Penyakit,Kode_Gejala FROM penyakit ORDER BY Kode_Penyakit");
        $result_query=mysqli_num_rows($query_data);
        if ($result_query==0)
        {
            $Penyakit=array('No Data','No Data');
            echo '<div>Tidak ada penyakit!</div>';
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
                
                

                echo '<div>
                <div>';
                echo '<h2>'.($a+1).' | '.$DataPenyakit['Nama_Penyakit'].'</h2>';
                

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
                
                echo '<p>Score : '.$Total[$a]['Probabilitas'].'</p>';
                
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

    <div>
        <div>
            <h5>Probalilitas</h5>
        </div>
    <div>
        <table border="1">
            <tr>
                <th>PENYAKIT</th>
                <th>PROBABILITAS</th>
            </tr>
            <?php
            $cnt=0;

            foreach($Total as $t)
            {
                echo "<tr>
                        <td>".$t['Nama_Penyakit'].'</td>';
                
                /* bootstap
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

                */
                echo '<td>'.$t['Probabilitas'].'</td>
                
                </tr>';
                $cnt++;
            }
            ?>
            </table>
        </div>
    </div>

    <div>
        <div>
            <h5>KESIMPULAN</h5>
        </div>
        <div>
            <p>
                <b>
                    Penyakit :
                    <?php
                    echo $NBPenyakit[1];
                    ?>
                </b>
            </p>
        </div>
    </div>
    <?php
        }
    }
?>