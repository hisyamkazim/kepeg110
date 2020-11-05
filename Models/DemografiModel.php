<?php namespace App\Models;
 
use CodeIgniter\Database\ConnectionInterface;

class DemografiModel
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

    public function getPendidikan_kanwil($jab2){
        $query = $this->db->query("SELECT DISTINCT A.NM_JENJANG, COALESCE(B.JUMLAH,0) AS JUMLAH
        FROM ref_sekolah A
        LEFT JOIN (SELECT a.KD_SEKOLAH, COUNT(a.ID_PEG) AS JUMLAH FROM tb_pegawai a
				LEFT JOIN ref_jabatan b
				ON a.KD_JABATAN = b.KD_JABATAN
        WHERE b.KEL_JABATAN IN $jab2
				GROUP BY a.KD_SEKOLAH
				) AS B
        ON A.KD_SEKOLAH = B.KD_SEKOLAH
        GROUP BY A.NM_JENJANG
        ORDER BY A.KD_SEKOLAH ");
        $result = $query->getResultArray();
        return $result;
    }


    public function getPendidikan_kpp($param)
    {
        $sekolah = $param['kd_sekolah'];
        $jabatan = $param['jab'];
        $query = $this->db->query("SELECT DISTINCT A.KD_UNIT, COALESCE(B.JUMLAH,0) AS JUMLAH
        FROM ref_unit A
        LEFT JOIN (SELECT a.KD_UNIT, COUNT(a.ID_PEG) AS JUMLAH FROM tb_pegawai a
				LEFT JOIN ref_jabatan b
				ON a.KD_JABATAN = b.KD_JABATAN
				LEFT JOIN ref_sekolah c
				ON a.KD_SEKOLAH = c.KD_SEKOLAH
        WHERE b.KEL_JABATAN IN $jabatan AND c.KD_SEKOLAH = '$sekolah'
				GROUP BY a.KD_UNIT
				) AS B
        ON A.KD_UNIT = B.KD_UNIT
        GROUP BY A.KD_UNIT
        ORDER BY A.KD_UNIT");
        $result = $query->getResultArray();
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

    public function getMasa_unit($param)
    {
        $kelompok = $param['kel'];
        $jabatan = $param['jab'];
        $builder = $this->db->table('v_masaunit');
        $builder->selectSum(strtoupper($jabatan), strtoupper($jabatan));
        $builder->where('KLP_UNIT',$kelompok);
        $builder->groupBy('KLP_UNIT');
        $result = $builder->get()->getResultArray();
        return $result;   
    }

    public function getMasa_unit_detail($param){
        $jabatan = $param['jab'];
        $kelompok = $param['kel'];
        $builder = $this->db->table('v_masaunit');
        $builder->selectSum($jabatan, $jabatan);
        $builder->where('KLP_UNIT',$kelompok);
        //$builder->where('KD_UNIT !=','110');
        $builder->groupBy('KD_UNIT');
        $result = $builder->get()->getResultArray();
        return $result;
    }

    public function getMasa_kanwil($param)
    {
        $kelompok = $param['kel'];
        $jabatan = $param['jab'];
        $builder = $this->db->table('v_masakanwil');
        $builder->selectSum(strtoupper($jabatan), strtoupper($jabatan));
        $builder->where('KLP_KANWIL',$kelompok);
        $builder->groupBy('KLP_KANWIL');
        $result = $builder->get()->getResultArray();
        return $result;  
    }

    public function getMasa_kanwil_detail($param){
        $jabatan = $param['jab'];
        $kelompok = $param['kel'];
        $builder = $this->db->table('v_masakanwil');
        $builder->selectSum($jabatan, $jabatan);
        $builder->where('KLP_KANWIL',$kelompok);
        $builder->groupBy('KD_UNIT');
        $result = $builder->get()->getResultArray();
        return $result;
    }

    public function getUsia_kanwil($jab2){
        $query = $this->db->query("SELECT DISTINCT A.KLP_UMUR, COALESCE(B.JUMLAH,0) AS JUMLAH
        FROM v_umurpegawai A
        LEFT JOIN (SELECT a.KLP_UMUR, COUNT(a.ID_PEG) AS JUMLAH FROM v_umurpegawai A
        LEFT JOIN tb_pegawai b ON a.ID_PEG = b.ID_PEG
        LEFT JOIN ref_jabatan c ON b.KD_JABATAN = c.KD_JABATAN
        WHERE c.KEL_JABATAN IN $jab2
        GROUP BY a.KLP_UMUR
        ) B ON A.KLP_UMUR = B.KLP_UMUR
        ORDER BY A.KLP_UMUR");
        $result = $query->getResultArray();
        return $result;
    }

    public function getUsia_kpp($param){
        $jabatan = $param['jab'];
        $kelompok = $param['kel'];
        $query = $this->db->query("SELECT A.KD_UNIT, COALESCE(B.JUMLAH,0) AS JUMLAH
        FROM ref_unit A
        LEFT JOIN (SELECT b.KD_UNIT, COUNT(a.ID_PEG) AS JUMLAH FROM v_umurpegawai A
        LEFT JOIN tb_pegawai b ON a.ID_PEG = b.ID_PEG
        LEFT JOIN ref_jabatan c ON b.KD_JABATAN = c.KD_JABATAN
        WHERE a.KLP_UMUR = '$kelompok' AND c.KEL_JABATAN IN $jabatan
        GROUP BY b.KD_UNIT
        ) B ON A.KD_UNIT = B.KD_UNIT
        ORDER BY A.KD_UNIT");
        $result = $query->getResultArray();
        return $result;
    }

    public function getJeniskelamin_kanwil($jab2){
        $query = $this->db->query("SELECT DISTINCT A.ID_JENIS_KELAMIN, COALESCE(B.JUMLAH,0) AS JUMLAH
        FROM ref_jenis_kelamin A
        LEFT JOIN (SELECT a.ID_JENIS_KELAMIN, COUNT(a.ID_PEG) AS JUMLAH FROM tb_pegawai a
				LEFT JOIN ref_jabatan b
				ON a.KD_JABATAN = b.KD_JABATAN
				LEFT JOIN ref_jenis_kelamin c
				ON a.ID_JENIS_KELAMIN = c.ID_JENIS_KELAMIN
        WHERE b.KEL_JABATAN IN $jab2
				GROUP BY a.ID_JENIS_KELAMIN
				) AS B
        ON A.ID_JENIS_KELAMIN = B.ID_JENIS_KELAMIN
        GROUP BY A.ID_JENIS_KELAMIN
        ORDER BY A.ID_JENIS_KELAMIN");
        $result = $query->getResultArray();
        return $result;
    }

    public function getJeniskelamin_kpp($param){
        $jabatan = $param['jab'];
        $kelompok = $param['kel'];
        $query = $this->db->query("SELECT A.KD_UNIT, COALESCE(B.JUMLAH,0) AS JUMLAH
        FROM ref_unit A
        LEFT JOIN (SELECT a.KD_UNIT, COUNT(a.ID_PEG) AS JUMLAH FROM tb_pegawai a
        LEFT JOIN ref_jabatan b ON a.KD_JABATAN = b.KD_JABATAN
        WHERE a.ID_JENIS_KELAMIN = '$kelompok' AND b.KEL_JABATAN IN $jabatan
        GROUP BY a.KD_UNIT
        ) B ON A.KD_UNIT = B.KD_UNIT
        ORDER BY A.KD_UNIT");
        $result = $query->getResultArray();
        return $result;
    }

    public function formasi_getLabel(){
        $query = $this->db->query("SELECT DISTINCT JABATAN FROM tb_formasi");
        $result = $query->getResultArray();
        return $result;
    }

    public function getFormasi($jab){
        $query = $this->db->query("SELECT DISTINCT KD_UNIT, JUMLAH
        FROM tb_formasi
        WHERE JABATAN = '$jab'
        GROUP BY KD_UNIT");
        $result = $query->getResultArray();
        return $result;
    }

    public function getJumlahpegawai($jab2){
        $query = $this->db->query("SELECT A.KD_UNIT, COALESCE(B.JUMLAH,0) AS JUMLAH
        FROM ref_unit A
        LEFT JOIN(
        SELECT a.KD_UNIT, COUNT(a.ID_PEG) aS JUMLAH
                FROM tb_pegawai a
                LEFT JOIN ref_jabatan b ON a.KD_JABATAN = b.KD_JABATAN
                WHERE b.KEL_JABATAN IN $jab2
                GROUP bY a.KD_UNIT
        ) B ON A.KD_UNIT = B.KD_UNIT");
        $result = $query->getResultArray();
        return $result;
    }
}