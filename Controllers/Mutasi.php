<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PeringkatModel;

class Mutasi extends BaseController
{
    public function __construct() {
 
		// Mendeklarasikan model
        try {
            $db = db_connect();
            $this->mutasi = new PeringkatModel($db);
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


    public function tabel(){
        $request = \Config\Services::request();
        $kategori = strtolower($request->uri->getSegment(3));
        $jab = strtoupper($kategori);
            if($jab == 'KASI') {
                $jab2 = "('KASI','KASUBBAG')";
            }
            else{
                $jab2 = "('".$jab."')";
            }
        $data['kategori'] = $kategori;
        $data['mutasi'] = $this->mutasi->getMutasi($jab2);
        
        //print_r($data['bakat']);
        echo view ('header');
        echo view ('mutasi',$data);
        echo view ('footer'); 
    }
}
