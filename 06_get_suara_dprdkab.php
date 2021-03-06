<?php

ini_set('display_errors','On');
ini_set('error_reporting',E_ALL&~E_NOTICE);
include_once 'libs/db.php';

function vesc($k){
  if (($k)||($k===0)||($k==='0')){
    return "'".esc($k)."'";
  }
  else{
    return 'NULL';
  }
}
function start_suara($uris){
  //prov/kota-kab/kec/kel
  //42385/42386/42387/42388
    

  global $mysqli;
  $kdkels=array();
  $all_uris=array();
  
  for ($i=0;$i<count($uris);$i++){
    $uri=$uris[$i];
    $uuri=explode("/",$uri);
    $kdkels[]=$kdkel=$uuri[3];
    $all_uris[]="https://pemilu2019.kpu.go.id/static/json/wilayah/{$uri}.json"; /* KPU WILAYAH */
    $all_uris[]="https://pemilu2019.kpu.go.id/static/json/hhcw/dprdkab/{$uri}.json"; /* KPU SUARA */
    // $all_uris[]="https://kawal-c1.appspot.com/api/c/{$kdkel}"; /* kawalpemilu.org */
  }
  
  echo "* Kel(".($kdkels[0])."-".($kdkels[count($kdkels)-1]).") ";
  /*echo "\n $all_uris[0]";
  echo "\n $all_uris[1]";
  echo "\n $all_uris[2]";
  echo "\n $all_uris[3]";*/
  $suara=wgets($all_uris);
  if(is_null($suara)) {
	  return null;
  }
  echo "\n  ## TPS ";
  $n=0;
  $out = array();
  $iq=array();
  $uq=array();
  for ($i=0;$i<count($suara);$i+=2){
    $tpsurls=array();
    $tpsurlid=array();
    $tpql=array();
    $mm=0;
    foreach($suara[$i] as $kode=>$tps){
      $urlupdate="https://pemilu2019.kpu.go.id/static/json/hhcw/dprdkab/{$uris[$n]}/{$kode}.json";

      //echo "\n $urlupdate ";

      $k = (int) substr($tps['nama'],4);
      $iq[] = "(".
        "'".esc($kode)."',".
        "'".esc($kdkels[$n])."',".
        "'".esc($tps['nama'])."',".
        "'".esc($k."")."'".
      ")";
      $q="UPDATE `t_tps_dprdkab` SET";
        $q.=" `kpu1`=".vesc($suara[$i+1]['table'][$kode][1]);
        $q.=",`kpu2`=".vesc($suara[$i+1]['table'][$kode][2]);
        $q.=",`kpu3`=".vesc($suara[$i+1]['table'][$kode][3]);
        $q.=",`kpu4`=".vesc($suara[$i+1]['table'][$kode][4]);
        $q.=",`kpu5`=".vesc($suara[$i+1]['table'][$kode][5]);
        $q.=",`kpu6`=".vesc($suara[$i+1]['table'][$kode][6]);
        $q.=",`kpu7`=".vesc($suara[$i+1]['table'][$kode][7]);
        $q.=",`kpu8`=".vesc($suara[$i+1]['table'][$kode][8]);
        $q.=",`kpu9`=".vesc($suara[$i+1]['table'][$kode][9]);
        $q.=",`kpu10`=".vesc($suara[$i+1]['table'][$kode][10]);
        $q.=",`kpu11`=".vesc($suara[$i+1]['table'][$kode][11]);
        $q.=",`kpu12`=".vesc($suara[$i+1]['table'][$kode][12]);
        $q.=",`kpu13`=".vesc($suara[$i+1]['table'][$kode][13]);
        $q.=",`kpu14`=".vesc($suara[$i+1]['table'][$kode][14]);
        $q.=",`kpu15`=".vesc($suara[$i+1]['table'][$kode][15]);
        $q.=",`kpu16`=".vesc($suara[$i+1]['table'][$kode][16]);
        $q.=",`kpu17`=".vesc($suara[$i+1]['table'][$kode][17]);
        $q.=",`kpu18`=".vesc($suara[$i+1]['table'][$kode][18]);
        $q.=",`kpu19`=".vesc($suara[$i+1]['table'][$kode][19]);
        $q.=",`kpu20`=".vesc($suara[$i+1]['table'][$kode][20]);
        
      $tpql[$mm]=array(
        $q,
        "",
        " WHERE `kdtps`='{$kode}'"
      );
      
      if (
          (!is_null($suara[$i+1]['table'][$kode][1]))||
          (!is_null($suara[$i+1]['table'][$kode][2]))||
          (!is_null($suara[$i+1]['table'][$kode][3]))||
          (!is_null($suara[$i+1]['table'][$kode][4]))||
          (!is_null($suara[$i+1]['table'][$kode][5]))||
          (!is_null($suara[$i+1]['table'][$kode][6]))||
          (!is_null($suara[$i+1]['table'][$kode][7]))||
          (!is_null($suara[$i+1]['table'][$kode][8]))||
          (!is_null($suara[$i+1]['table'][$kode][9]))||
          (!is_null($suara[$i+1]['table'][$kode][10]))||
          (!is_null($suara[$i+1]['table'][$kode][11]))||
          (!is_null($suara[$i+1]['table'][$kode][12]))||
          (!is_null($suara[$i+1]['table'][$kode][13]))||
          (!is_null($suara[$i+1]['table'][$kode][14]))||
          (!is_null($suara[$i+1]['table'][$kode][15]))||
          (!is_null($suara[$i+1]['table'][$kode][16]))||
          (!is_null($suara[$i+1]['table'][$kode][17]))||
          (!is_null($suara[$i+1]['table'][$kode][18]))||
          (!is_null($suara[$i+1]['table'][$kode][19]))||
          (!is_null($suara[$i+1]['table'][$kode][20]))
         ){
        $tpsurls[]=$urlupdate;
        $tpsurlid[]=$mm;
        echo "+";
      }
      else{
        $uq[]=implode("",$tpql[$mm]);
        echo "x";
      }
      $mm++;
    }
    
    
    if (count($tpsurls)>0){
      $tpss=wgets($tpsurls);
	  if(is_null($tpss)) {
		  return null;
	  }
      for ($j=0;$j<count($tpss);$j++){
        $pss=$tpss[$j];
        $q="";
        $q.=",`kput`=".vesc($pss['suara_tidak_sah']);
        $q.=",`kpus`=".vesc($pss['suara_sah']);
        $q.=",`kpuj`=".vesc($pss['suara_total']);
        if ($pss['images']){
          $q.=",`kpuimg`=".vesc(implode(";",$pss['images']));
        }
        $tpql[$tpsurlid[$j]][1]=$q;
        $uq[]=implode("",$tpql[$j]);
      }
    }
    
    $uq[]="UPDATE `t_kel` SET `last_dprdkab`=NOW() WHERE `kdkel`='{$kdkels[$n]}'";
    $n++;
  }
  echo "\n";
  
  if (count($iq)>0){
    query("INSERT INTO `t_tps_dprdkab` (`kdtps`,`kdkel`,`nama`,`no`) VALUES ".implode(",",$iq));
    //echo "\n INSERT INTO `t_tps_dprdkab` (`kdtps`,`kdkel`,`nama`,`no`) VALUES ".implode(",",$iq);
  }

  if (count($uq)>0){
    $sqls=implode(";\n",$uq);
    //echo "\n $sqls";
    
    $mysqli->multi_query($sqls);
    while($mysqli->more_results()){
       $mysqli->next_result();
    }
  }
  
  return 1;
}

$limit_off = isset($argv[1]) ? $argv[1] : 0;
$break_loop = false;
while(!$break_loop) {
	$rows_kec = all("SELECT `kdkec` FROM `v04_kel_uri` WHERE `kdprov`='42385' AND `kdkota`='51358' AND `last_dprdkab` IS NULL GROUP BY `kdkec` ORDER BY `kdkec` ASC LIMIT $limit_off,5");

	foreach($rows_kec as $kec) {
		$kd_kec =  $kec['kdkec'];
		$rows = all("SELECT `kpu_uri` FROM `v04_kel_uri` WHERE `kdprov`='42385' AND `kdkota`='51358' AND `kdkec`='$kd_kec' ORDER BY `kdkel` ASC");
		
		if(count($rows)>0) {
		$urls=array();
		for ($i=0;$i<count($rows);$i++){
			  $urls[$i]=$rows[$i]['kpu_uri'];
			  echo "\n $urls[$i]";
			}
			//prov/kota-kab/kec/kel
			//42385/42386/42387/42388
			$retval = start_suara($urls);
			
			if(is_null($retval)) {
				continue;
				echo " cont \n";
			}
			else {
				echo " done \n";
			}
		}
		else {
			$break_loop = true;
			echo " LOOP done \n";
			break;
		}
	}
}

//https://pemilu2019.kpu.go.id/static/json/hhcw/dprdkab/1/1492/1493/1501.json
//https://pemilu2019.kpu.go.id/static/json/hhcw/dprdkab/1/1492/1493/1501/900003213.json TPS
//https://pemilu2019.kpu.go.id/static/json/hhcw/dprdkab/1/1492/1493/1501/900003213.json TPS
//https://pemilu2019.kpu.go.id/static/json/hhcw/dprdkab/1/1492/1493/1501/900003213.json TPS

//jatim/malang/
//42385/43993

// https://kawal-c1.appspot.com/api/c/931268 (KODE KELURAHAN)

// https://pemilu2019.kpu.go.id/static/json/hhcw/dprdkab.json >> data
// https://pemilu2019.kpu.go.id/static/json/ppwp.json >> Calon
// https://pemilu2019.kpu.go.id/static/json/wilayah/0.json >> dapil

// https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/1/1492/1493/1501.json
// https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/1/1492/1493/1501/900003213.json

// hitung suara ->  provinsi -> kab/kota -> kecamatan -> 

// https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/1/1492/1654/1656.json >> data

// https://pemilu2019.kpu.go.id/static/json/wilayah/1/2/9/10.json >> wilayah

// https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/1/1492/1672/1697.json

// https://pemilu2019.kpu.go.id/static/json/wilayah/1/1492/1654.json

// https://pemilu2019.kpu.go.id/static/json/wilayah/12920/13608/83415/87532.json
// https://pemilu2019.kpu.go.id/static/json/wilayah/12920/13608/83415/87532.json
?>
