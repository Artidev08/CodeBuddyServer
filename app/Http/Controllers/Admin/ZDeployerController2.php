<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use function Spatie\Snapshots\assertMatchesSnapshot;
// use Spatie\Ssh\Ssh;
use phpseclib\Net\SSH2;

// use Symfony\Component\Process\Process;
use ZipArchive;
use File;

class ZDeployerController extends Controller
{
        public $ftp_server, $ftp_username, $ftp_password, $ftp_port, $ftp_folder;
        /**
         * Create a new controller instance.
         *
         * @return void
         */
    public function __construct()
    {
        $this->ftp_host = 'ftp.dze-labs.xyz';
        $this->ftp_user = 'zdeployer@test-deployer.dze-labs.xyz';
        $this->ftp_password = "uKA{)Ku9mhJ,";
        $this->ftp_port = 21;
        $this->ftp_folder = "/Home/usmlestu/test-deployer.dze-labs.xyz";
    }
    public function index()
    {
        // return 's';
        $localDir = base_path();
        $serverDir = $this->ftp_folder;
        $localFolders = ['app','resources','routes'];
        $zip = new ZipArchive;
        $zipFileName = '/backup-' . date('Y-m-d-H-i-s') . '.zip';
        $localZipFile = $localDir.$zipFileName;

        // Open the local zip file for writing
        $zip->open($localZipFile, ZipArchive::CREATE);
        foreach ($localFolders as $folder) {
            $folderPath = base_path($folder);
            $files = File::allFiles($folderPath);
            foreach ($files as $file) {
                $relativePath = substr($file->getRealPath(), strlen(dirname($folderPath)) + 1);
                $zip->addFile($file->getRealPath(), $relativePath);
            }
        }
        $zip->close();
        storage_path($localZipFile);
         
        $ssh = new SSH2($this->ftp_host);
        if (!$ssh->login($this->ftp_user, $this->ftp_port)) {
            return 'Login Failed';
        }
        // $ssh = new Ssh($this->ftp_user, $this->ftp_host,$this->ftp_port);
        // $command = $ssh->usePrivateKey($this->ftp_password)->getExecuteCommand('whoami');
        
        //         // ->getExecuteCommand('whoami');
        // // Upload the local zip file to the server
        // $ssh->upload($localZipFile, $serverDir.$zipFileName);

        // // Connect to the server and create a unique filename for the server backup zip file
        // $ssh->execute([
        //     'cd ' . $this->ftp_folder,
        //     'backupZipFile="backup-$(date +%Y-%m-%d-%H-%M-%S).zip"',
        //     'zip -r $backupZipFile .',
        // ]);

        // // Extract the local zip file on the server and delete it
        // $ssh->execute([
        //     'cd ' . $serverDir,
        //     'unzip ' . $zipFileName,
        //     'rm ' . $zipFileName,
        // ]);
        return 's';
    }
}
