<?php namespace App\Models;
 
use CodeIgniter\Database\ConnectionInterface;

class PeringkatModel
{
    protected $db; 

    public function __construct(ConnectionInterface &$db) {
        $this->db = &$db;
        
    }

    public function getPeringkat($jab2){
        $query = $this->db->query("SELECT A.NIP,A.NAMA,CONCAT(B.PANGKAT,'/',B.GOLONGAN,B.RUANG) AS PANGGOL,
        C.NM_ES4, D.NM_UNIT, A.PERINGKAT
        FROM tb_pegawai A
        LEFT JOIN ref_panggol B ON A.KD_PANGGOL = B.KD_PANGGOL
        LEFT JOIN ref_es4 C ON A.KD_ES4 = C.KD_ES4
        LEFT JOIN ref_unit D ON A.KD_UNIT = D.KD_UNIT
        LEFT JOIN ref_jabatan E ON A.KD_JABATAN = E.KD_JABATAN
        LEFT JOIN ref_peringkat F ON A.PERINGKAT = F.PERINGKAT
        WHERE E.KEL_JABATAN IN $jab2
        ORDER BY F.KD_PERINGKAT ASC");
        $result = $query->getResultArray();
        return $result;
    }

    public function getBakat($jab2){
        $query = $this->db->query("SELECT A.NAMA, B.NM_UNIT_PENDEK, A.PERINGKAT, C.FG_ALK, C.FG_TP, C.FG_OLAHDATA, C.FG_FORENSIK, (E.NILAI_PERINGKAT+E.NILAI_ALK+E.NILAI_TP+E.NILAI_OLAHDATA+E.NILAI_FORENSIK) AS NILAI_TOTAL
        FROM tb_pegawai A
        LEFT JOIN ref_unit B ON A.KD_UNIT = B.KD_UNIT
        LEFT JOIN tb_bakat C ON A.ID_PEG = C.ID_PEG
        LEFT JOIN ref_jabatan D ON A.KD_JABATAN = D.KD_JABATAN
        LEFT JOIN v_nilaipegawai E ON A.ID_PEG = E.ID_PEG
        WHERE D.KEL_JABATAN IN $jab2
        ORDER BY NILAI_TOTAL DESC");
        $result = $query->getResultArray();
        return $result;
    }

    public function getMutasi($jab2){
        $query = $this->db->query("SELECT A.NAMA, B.NM_UNIT_PENDEK, C.MASA_UNIT, C.MASA_KANWIL, A.PERINGKAT, F.FG_STRUKTURAL, F.FG_FUNGSIONAL
        FROM tb_pegawai A
        LEFT JOIN ref_unit B ON A.KD_UNIT = B.KD_UNIT
        LEFT JOIN v_masapenempatan C ON A.ID_PEG = C.ID_PEG
        LEFT JOIN ref_jabatan D ON A.KD_JABATAN = D.KD_JABATAN
        LEFT JOIN ref_peringkat E ON A.PERINGKAT = E.PERINGKAT
        LEFT JOIN tb_minat F ON A.ID_PEG = F.ID_PEG
        WHERE D.KEL_JABATAN IN $jab2 AND (C.MASA_UNIT > 3 AND C.MASA_KANWIL > 3)
        ORDER BY  E.KD_PERINGKAT ASC,C.MASA_UNIT DESC, C.MASA_KANWIL DESC");
        $result = $query->getResultArray();
        return $result;
    }
}