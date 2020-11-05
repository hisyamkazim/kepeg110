<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DemografiModel;

class Demografi extends BaseController
{
    public function __construct() {
 
		// Mendeklarasikan model
        try {
            $db = db_connect();
            $this->demo = new DemografiModel($db);
        }
        catch (\Exception $e)
        {
            throw new \CodeIgniter\Database\Exceptions\DatabaseException();
        }
                
        /* Catatan:
        Apa yang ada di dalam function construct ini nantinya bisa digunakan
        pada function di dalam class Demografi
		*/
    }
    
    public function masakerja() {
        
        $request = \Config\Services::request();
        $jenis = strtolower($request->uri->getSegment(3));
        $kategori = strtolower($request->uri->getSegment(4));
        switch ($kategori){
            case '':
                return redirect()->to(base_url().'/demografi/masakerja/'.$jenis.'/pelaksana'); 
            break;
        }
        $data['jenis'] = $jenis;
        $data['kategori'] = $kategori;
        $data['unit'] = $this->demo->getUnit();
        if (strtolower($request->uri->getSegment(3)) == 'unit'){
            foreach(range(1,5) as $i) {
                $param['kel'] = strval($i);
                $param['jab'] = strtoupper($kategori);
                $data['TOTAL_UNIT_KLP_'.strval($i)] = $this->demo->getMasa_unit($param);
                $data['UNIT_KLP_'.strval($i)] = $this->demo->getMasa_unit_detail($param);
            }
            
            //print_r ($data['TOTAL_UNIT_KLP_1']);
            echo view ('header',$data);
            echo view ('demografi/masa',$data);
            echo view ('footer');
        }
        elseif (strtolower($request->uri->getSegment(3)) == 'kanwil') {
            foreach(range(1,5) as $i) {
                $param['kel'] = strval($i);
                $param['jab'] = strtoupper($kategori);
                $data['TOTAL_KANWIL_KLP_'.strval($i)] = $this->demo->getMasa_kanwil($param);
                $data['KANWIL_KLP_'.strval($i)] = $this->demo->getMasa_kanwil_detail($param);
            }
            echo view ('header',$data);
            echo view ('demografi/masa',$data);
            echo view ('footer');
        }
        else{
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
    }

    public function pendidikan() {
        
        $request = \Config\Services::request();
        $kategori = strtolower($request->uri->getSegment(3));
        switch ($kategori){
            case '':
                return redirect()->to(base_url().'/demografi/pendidikan/pelaksana'); 
            break;
        }
        $data['kategori'] = $kategori;
        $data['unit'] = $this->demo->getUnit();
        $data['jenjang'] = $this->demo->getJenjang();
        $jab = strtoupper($kategori);
        if($jab == 'KASI') {
            $jab2 = "('KASI','KASUBBAG')";
        }
        else{
            $jab2 = "('".$jab."')";
        }
        $data['pendidikan_kanwil'] = $this->demo->getPendidikan_kanwil($jab2);

        foreach($data['jenjang'] as $jenjang){
            $param['kd_sekolah'] = $jenjang['KD_SEKOLAH'];
            $param['jab'] = $jab2;
			$data[$jenjang['NM_JENJANG']] = $this->demo->getPendidikan_kpp($param);
		}
        
        //print_r($data['SMA']);
        echo view ('header',$data);
        echo view ('demografi/pendidikan',$data);
        echo view ('footer');        
        
    }

    public function usia() {
        
        $request = \Config\Services::request();
        $kategori = strtolower($request->uri->getSegment(3));
        switch ($kategori){
            case '':
                return redirect()->to(base_url().'/demografi/usia/pelaksana'); 
            break;
        }
        $data['kategori'] = $kategori;
        $data['unit'] = $this->demo->getUnit();
        $jab = strtoupper($kategori);
        if($jab == 'KASI') {
            $jab2 = "('KASI','KASUBBAG')";
        }
        else{
            $jab2 = "('".$jab."')";
        }
        $data['usia_kanwil'] = $this->demo->getUsia_kanwil($jab2);

        foreach(range(1,5) as $i) {
            $param['kel'] = strval($i);
            $param['jab'] = $jab2;
            $data['KEL_'.strval($i)] = $this->demo->getUsia_kpp($param);
        }
        
        //print_r($data['usia_kanwil']);
        echo view ('header',$data);
        echo view ('demografi/usia',$data);
        echo view ('footer');        
        
    }

    public function jeniskelamin() {
        
        $request = \Config\Services::request();
        $kategori = strtolower($request->uri->getSegment(3));
        switch ($kategori){
            case '':
                return redirect()->to(base_url().'/demografi/jeniskelamin/pelaksana'); 
            break;
        }
        $data['kategori'] = $kategori;
        $data['unit'] = $this->demo->getUnit();
        $jab = strtoupper($kategori);
        if($jab == 'KASI') {
            $jab2 = "('KASI','KASUBBAG')";
        }
        else{
            $jab2 = "('".$jab."')";
        }
        $data['jeniskelamin_kanwil'] = $this->demo->getJeniskelamin_kanwil($jab2);

        foreach(range(1,2) as $i) {
            $param['kel'] = strval($i);
            $param['jab'] = $jab2;
            $data['KEL_'.strval($i)] = $this->demo->getJeniskelamin_kpp($param);
        }
        
        //print_r($data['SMA']);
        echo view ('header',$data);
        echo view ('demografi/jeniskelamin',$data);
        echo view ('footer');        
        
    }

    public function formasi(){
        $data['unit'] = $this->demo->getUnit();
        $jabatan = $this->demo->formasi_getLabel();
        foreach($jabatan as $label){
            $jab = $label['JABATAN'];
            if($jab == 'ES_2_3'){
                $jab2 = "('KAKAP','KABID','KABAG','KAKANWIL')" ;
            }
            else if($jab == 'ES_4') {
                $jab2 = "('KASI','KASUBBAG')";
            }
            else{
                $jab2 = "('".$jab."')";
            }
            $data['FORMASI_'.$jab] = $this->demo->getFormasi($jab);
            $data['CURRENT_'.$jab] = $this->demo->getJumlahpegawai($jab2);
        } 
        //print_r($data['CURRENT_PELAKSANA']);
        echo view ('header');
        echo view ('demografi/formasi',$data);
        echo view ('footer'); 
    }
}
