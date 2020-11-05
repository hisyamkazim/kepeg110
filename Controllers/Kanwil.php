<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PegawaiModel;
use App\Models\KanwilModel;

class Kanwil extends BaseController
{
	public function __construct() {
 
		// Mendeklarasikan model
		try {
			$db = db_connect();
			$this->pegawai = new PegawaiModel();
			$this->kanwil = new KanwilModel($db);
			/* Catatan:
			Apa yang ada di dalam function construct ini nantinya bisa digunakan
			pada function di dalam class Kanwil
			*/
		}
		catch (\Exception $e)
		{
			throw new \CodeIgniter\Database\Exceptions\DatabaseException();
		}
	}

	public function index()
	{	
		$data['unit'] = $this->kanwil->getUnit();
		$data['jenjang'] = $this->kanwil->getJenjang();
		$data['pendidikan_kanwil'] = $this->kanwil->getPendidikan_kanwil();
		$data['pegawai'] = $this->kanwil->getJumlahpegawai();
		$data['umur'] = $this->kanwil->getUmurpegawai();
		$data['jenis_kanwil'] = $this->kanwil->getJenis_kanwil();
		
		foreach($data['jenjang'] as $jenjang){
			$param = $jenjang['KD_SEKOLAH'];
			$data[$jenjang['NM_JENJANG']] = $this->kanwil->getPendidikan_kpp($param);
		}

		/*for ($i=1;$i<6;$i++){
			$param = to_str($i);
			echo $param;
			//$data['UNIT_KLP_'.$param] = $this->kanwil->getMasa_unit($param);
			//$data['KANWIL_KLP_'.$param] = $this->kanwil->getMasa_kanwil($param);
		}*/

		foreach(range(1,5) as $i) {
			$param = strval($i);
			$data['UNIT_KLP_'.$param] = $this->kanwil->getMasa_unit($param);
			$data['KANWIL_KLP_'.$param] = $this->kanwil->getMasa_kanwil($param);

		}

		foreach(range(1,2) as $i) {
			$jenis = strval($i);
			$data['JENIS_'.$jenis] = $this->kanwil->getJenis_kpp($jenis);
		}
		
		echo view('header');
		echo view('kanwil/body',$data);
		echo view('footer');

	}

	//--------------------------------------------------------------------

}
