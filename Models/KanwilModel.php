<?php namespace App\Models;
 
use CodeIgniter\Database\ConnectionInterface;

class KanwilModel
{
    protected $db; 

    public function __construct(ConnectionInterface &$db) {
        $this->db = &$db;
    }

    public function getUnit()
    {
        $builder = $this->db->table('ref_unit');
        $result = $builder->get()->getResultArray();
        return $result;  
    }

    public function getJenjang()
    {
        $builder = $this->db->table('ref_sekolah');
        $builder->select('*');
        $builder->orderBy('KD_SEKOLAH');
        $result = $builder->get()->getResultArray();
        return $result;  
    }

    public function getJumlahpegawai()
    {
        $builder = $this->db->table('v_jumlahpegawai');
        $result = $builder->get()->getResultArray();
        return $result;  
    } 
    
    public function getPendidikan_kanwil()
    {
        $builder = $this->db->table('v_pendidikankanwil');
        $result = $builder->get()->getResultArray();
        return $result;  
    }

    public function getPendidikan_kpp($param)
    {
        $builder = $this->db->table('tb_pegawai');
        $builder->selectCount('ID_PEG');
        $builder->where('KD_SEKOLAH',$param);
        $builder->groupBy('KD_UNIT');
        $builder->orderBy('KD_UNIT');
        $result = $builder->get()->getResultArray();
        return $result;  
    }
    
    public function getUmurpegawai()
    {
        $builder = $this->db->table('v_umurpegawai');
        $builder->selectCount('ID_PEG');
        $builder->groupBy('KLP_UMUR');
        $builder->orderBy('KLP_UMUR');
        $result = $builder->get()->getResultArray();
        return $result;  
    }

    public function getMasa_unit($param)
    {
        $builder = $this->db->table('v_masaunit');
        $builder->selectSum('PELAKSANA', 'PELAKSANA');
        $builder->selectSum('AR', 'AR');
        $builder->selectSum('FPP', 'FPP');
        $builder->selectSum('KASI', 'KASI');
        $builder->where('KLP_UNIT',$param);
        $builder->groupBy('KLP_UNIT');
        $result = $builder->get()->getResultArray();
        return $result;  
    }

    public function getMasa_kanwil($param)
    {
        $builder = $this->db->table('v_masakanwil');
        $builder->selectSum('PELAKSANA', 'PELAKSANA');
        $builder->selectSum('AR', 'AR');
        $builder->selectSum('FPP', 'FPP');
        $builder->selectSum('KASI', 'KASI');
        $builder->where('KLP_KANWIL',$param);
        $builder->groupBy('KLP_KANWIL');
        $result = $builder->get()->getResultArray();
        return $result;  
    }

    public function getJenis_kanwil(){
        $query = $this->db->query("SELECT ID_JENIS_KELAMIN, COUNT(ID_PEG) AS JUMLAH
        FROM tb_pegawai
        GROUP BY ID_JENIS_KELAMIN");
        $result = $query->getResultArray();
        return $result;
    }

    public function getJenis_kpp($jenis){
        $query = $this->db->query("SELECT KD_UNIT, COUNT(ID_PEG) AS JUMLAH
        FROM tb_pegawai
        WHERE ID_JENIS_KELAMIN = '$jenis'
        GROUP BY KD_UNIT");
        $result = $query->getResultArray();
        return $result;
    }
}