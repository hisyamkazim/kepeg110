<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class PegawaiModel extends Model
{
    protected $table = "tb_pegawai";
 
    public function getPegawai($id_peg = false)
    {
        if($id_peg === false){
            return $this->table('tb_pegawai')
                        ->get()
                        ->getResultArray();
        } else {
            return $this->table('tb_pegawai')
                        ->where('id_peg', $id_peg)
                        ->get()
                        ->getRowArray();
        }   
    } 
}