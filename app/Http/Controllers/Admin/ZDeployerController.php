<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\Http;
use File;

class ZDeployerController extends Controller
{
        public $host_url, $ftp_host, $ftp_user, $ftp_password, $ftp_port, $ftp_folder,$host;
        /**
         * Create a new controller instance.
         *
         * @return void
         */
    public function __construct()
    {
        $this->host_url = 'http://test-deployer.dze-labs.xyz';
        $this->ftp_host = 'ftp.dze-labs.xyz';
        $this->ftp_user = 'zdeployer@test-deployer.dze-labs.xyz';
        $this->ftp_password = "uKA{)Ku9mhJ,";
        $this->ftp_port = 21;
        $this->ftp_folder = "/Home/usmlestu/test-deployer.dze-labs.xyz";
    }

    public function handle()
    {
        ini_set('maximum_execution_time', 600);
        $local_folders = [
            'app',
            'config',
            'database',
            "resources",
            "routes",
        ];
        
        $zip_folder = 'deploy_zips';
        $backup_folder = 'deploy_backups';
        
        $zip_path = $this->makeZip($local_folders, $backup_folder);
        
        // echo 'Connecting to FTP server...';
        
        $ftp_server = $this->ftp_host;
        $ftp_username = $this->ftp_user;
        $ftp_password = $this->ftp_password;
        $ftp_path =  $this->ftp_folder;
        
        $ftp_connection = ftp_connect($ftp_server);
        $login_result = ftp_login($ftp_connection, $ftp_username, $ftp_password);
        ftp_pasv($ftp_connection, true);
        if ($ftp_connection && $login_result) {
            // echo "Connected to FTP server. \n";
        
            $remote_zip_path = basename($zip_path);

            $upload_result = ftp_put($ftp_connection, $remote_zip_path, $zip_path, FTP_BINARY);
        
            if ($upload_result) {
                echo "Zip file uploaded to FTP server. Now creating file for taking backup in server. \n";
                $data['wildcard'] = "?";
                $serverZipData = view('crudgenrator.deployer.server-zip', compact('local_folders', 'data'));
                $file = 'zdeployer_backup_payload.php';
                $destinationPath = base_path().'/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                File::put($destinationPath.$file, $serverZipData);

                ftp_put($ftp_connection, $file, $destinationPath.$file, FTP_BINARY);
                $serverBackupFilePath = $file;
                    
                    
                echo "Creating file for extracting local files in server.\n";

                $serverUnzipData = view('crudgenrator.deployer.server-unzip', compact('remote_zip_path', 'local_folders', 'data'));
                $file = 'zdeployer_unzip_payload.php';
                // $destinationPath = storage_path()."/app/crud_output/view/".$data['name'].'/';
                $destinationPath = base_path().'/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                File::put($destinationPath.$file, $serverUnzipData);
        
                ftp_put($ftp_connection, $file, $destinationPath.$file, FTP_BINARY);
                    
                    
                echo "Both files uploaded in server.\n";
                    // Wait for 5 seconds
                    sleep(5);
                        
                Http::get($this->host_url.'/zdeployer_backup_payload.php');
                echo "Taking server\'s backup .\n";
                // Wait for 4 second
                sleep(4);

                Http::get($this->host_url.'/zdeployer_unzip_payload.php');
                echo "Extracting local zip files to server.\n";

                ftp_delete($ftp_connection, 'zdeployer_backup_payload.php');
                ftp_delete($ftp_connection, 'zdeployer_unzip_payload.php');
                ftp_delete($ftp_connection, $remote_zip_path);
             
                echo 'Deployment complete.';
            } else {
                $this->error('Error uploading zip file to FTP server.');
            }
        
            ftp_close($ftp_connection);
        } else {
            $this->error('Failed to connect to FTP server.');
        }
        
        unlink(base_path('zdeployer_backup_payload.php'));
        unlink(base_path('zdeployer_unzip_payload.php'));
        // remove the local zip file after deployment
        unlink($zip_path);
    }





    private function addFolderToZip($zip, $folder, $relative_path = '')
    {
        $relative_path.'/'.$folder;
        // $relative_path.'\\'.$folder;
            
            
        $files = File::allFiles($relative_path.'/'.$folder);
        // $files = File::allFiles($relative_path.'\\'.$folder);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
        
            $file_path = $folder . '/' . $file;
            $relative_file_path = $relative_path . '/' . $file;
        
            if (is_dir($file_path)) {
                $this->addFolderToZip($zip, $file_path, $relative_file_path);
            } else {
                $relativePath = substr($file->getRealPath(), strlen(dirname($relative_path.'\\'.$folder)) + 1);
                $zip->addFile($file->getRealPath(), $relativePath);
                // $zip->addFile($file_path, $relative_file_path);
            }
        }
    }

    private function addRemoteFolderToZip($zip, $folder, $ftp_connection, $parent_folder = null)
    {
        $path = $parent_folder ? $parent_folder . '/' . $folder : $folder;
        $files = ftp_nlist($ftp_connection, $path);
        foreach ($files as $file) {
            if (ftp_size($ftp_connection, $file) == -1) {
                $this->addRemoteFolderToZip($zip, basename($file), $ftp_connection, $path);
            } else {
                $zip->addFromString(basename($file), ftp_get($ftp_connection, 'php://output', $file, FTP_BINARY));
            }
        }
    }
    private function deleteRemoteFolder($ftp_connection, $folder, $parent_folder = null)
    {
        $path = $parent_folder ? $parent_folder . '/' . $folder : $folder;
        $files = ftp_nlist($ftp_connection, $path);
        foreach ($files as $file) {
            if (ftp_size($ftp_connection, $file) == -1) {
                $this->deleteRemoteFolder($ftp_connection, basename($file), $path);
            } else {
                ftp_delete($ftp_connection, $file);
            }
        }
        ftp_rmdir($ftp_connection, $path);
    }
    private function makeZip($local_folders, $backup_folder)
    {
        $zip = new ZipArchive();
        $backup_path = storage_path('app/' . $backup_folder);
        
        if (!is_dir($backup_path)) {
            mkdir($backup_path, 0777, true);
        }
        
        $date = date('Ymd_His');
        $zip_name = 'deploy_backup_' . $date . '.zip';

        $zip_path = storage_path('app/deploy_backups/' . $zip_name);
        // $zip_path = storage_path('app\deploy_backups\\' . $zip_name);
        
        if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($local_folders as $local_folder) {
                $this->addFolderToZip($zip, $local_folder, base_path());
            }
            $zip->close();
        }
        
        return $zip_path;
    }
}
