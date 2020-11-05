<?php namespace App\Controllers;

class Pegawai extends BaseController
{
	public function masakerja()
	{
        echo view('header');
        echo view('pegawai/masa');
        echo view('footer');
    }
    public function pendidikan()
	{
        echo view('header');
        echo view('pegawai/pendidikan');
        echo view('footer');
	}
    public function usia()
	{
        echo view('header');
        echo view('pegawai/usia');
        echo view('footer');
    }
    public function jabatan()
	{
        echo view('header');
        echo view('pegawai/jabatan');
        echo view('footer');
	}

	//--------------------------------------------------------------------

}
