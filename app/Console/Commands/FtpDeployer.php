<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;
use Illuminate\Support\Facades\Http;
use File;

class FtpDeployer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ftp:deploy';
    public $host_url, $ftp_host, $ftp_user, $ftp_password, $ftp_port, $ftp_folder,$host;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It deploy code in the specified server and make backup files available';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $retry = 0;
        // try {
           $this->host_url = 'http://zstarter.dze-labs.xyz';
           $this->ftp_host = 'ftp.dze-labs.xyz';
           $this->ftp_user = 'zdeployer-zstarter@zstarter.dze-labs.xyz';
           $this->ftp_password = "uKA{)Ku9mhJ,";
           $this->ftp_port = 21;
           $this->ftp_folder = "/Home/usmlestu/zstarter.dze-labs.xyz/core";

           ini_set('maximum_execution_time', 600);
           echo "\n";
           echo "Starting... \n";
           echo "\n";

           echo "
            _____________      ___           __                      ___
            / __/_  __/ _ \____/ _ \___ ___  / /__  __ _____ _____  _<  /
            / _/  / / / ___/___/ // / -_) _ \/ / _ \/ // / -_) __/ |/ / / 
        /_/   /_/ /_/      /____/\__/ .__/_/\___/\_, /\__/_/  |___/_/  
                                    /_/          /___/                         
            ";

           echo "\n";
           echo "\n";
           echo "\n";

           echo "Setting up local folders.\n";
            
           $local_folders = [
               'app',
               //  'config',
               //  'database',
               //  "resources",
               //  "routes",
           ];
        
           echo "Establishing FTP connection. \n";
        
           $ftp_server = $this->ftp_host;
           $ftp_username = $this->ftp_user;
           $ftp_password = $this->ftp_password;
           $ftp_path =  $this->ftp_folder;
           $ftp_port =  $this->ftp_port;
        
           $ftp_connection = ftp_connect($ftp_server, $ftp_port);
           if (!$ftp_connection) {
               // Handle the error condition
               echo "Failed to connect to FTP server.";
               return;
           }
           $login_result = ftp_login($ftp_connection, $ftp_username, $ftp_password);
           if (!$login_result) {
               // Handle the error condition
               echo "Failed to login to FTP server.";
               ftp_close($ftp_connection);
               return;
           }
            
           ftp_set_option($ftp_connection, FTP_USEPASVADDRESS, false);
           ftp_set_option($ftp_connection, FTP_TIMEOUT_SEC, 600);


           if ($ftp_connection && $login_result) {
               echo "Local folders ready.\n";
            
               $zip_folder = 'deploy_zips';
               $backup_folder = 'deploy_backups';
            
               $zip_path = $this->makeZip($local_folders, $backup_folder);

               echo "FTP connection successful.\n";
                
               $remote_zip_path = basename($zip_path);
                
               echo "Preparing Upload\n";

               $local_file = fopen($zip_path, 'r');
               ftp_pasv($ftp_connection, true);
                
               ftp_set_option($ftp_connection, FTP_BINARY, true);

               $upload_result = ftp_nb_fput($ftp_connection, $remote_zip_path, $local_file, FTP_BINARY);
               ftp_pasv($ftp_connection, false);
               $this->uploadWithProgress($ftp_connection, $ftp_server, $ftp_username, $ftp_password, $upload_result, $remote_zip_path, $zip_path);
        
               if ($upload_result) {
                   echo "Uploaded ZIP file. \n";
                   echo "Creating backup payload. \n";
                    
                   $data['wildcard'] = "?";
                   $serverZipData = view('crudgenrator.deployer.server-zip', compact('local_folders', 'data'));
                   $file = 'zdeployer_backup_payload.php';
                   $destinationPath = base_path().'/';
                   if (!is_dir($destinationPath)) {
                       mkdir($destinationPath, 0777, true);
                   }

                   File::put($destinationPath.$file, $serverZipData);
                    
                   echo "Uploading backup payload. \n";

                   ftp_pasv($ftp_connection, true);
                   ftp_put($ftp_connection, $file, $destinationPath.$file, FTP_BINARY);

                   $serverBackupFilePath = $file;
                   echo "Backup payload uploaded!\n";
                   ftp_pasv($ftp_connection, false);
                    
                   echo "Creating unzip payload\n";
                   $serverUnzipData = view('crudgenrator.deployer.server-unzip', compact('remote_zip_path', 'local_folders', 'data'));
                   $file = 'zdeployer_unzip_payload.php';

                   // $destinationPath = storage_path()."/app/crud_output/view/".$data['name'].'/';
                   $destinationPath = base_path().'/';
                   if (!is_dir($destinationPath)) {
                       mkdir($destinationPath, 0777, true);
                   }
                   File::put($destinationPath.$file, $serverUnzipData);
        
                   echo "Uploading unzip payload \n";

                   ftp_pasv($ftp_connection, true);
                   ftp_put($ftp_connection, $file, $destinationPath.$file, FTP_BINARY);

                   ftp_pasv($ftp_connection, false);
                   echo "Unzip payload uploaded!\n";

                    
                   //  echo "Optimizing...\n";
                       // Wait for 5 seconds
                       sleep(5);
                        
                   Http::get($this->host_url.'/zdeployer_backup_payload.php');
                   echo "Backing up server files.\n";
                   // Wait for 4 second
                   sleep(4);

                   Http::get($this->host_url.'/zdeployer_unzip_payload.php');
                   echo "Extracting local ZIP file on server.\n";

                   // ftp_delete($ftp_connection, 'zdeployer_backup_payload.php');
                   // ftp_delete($ftp_connection, 'zdeployer_unzip_payload.php');
                   ftp_delete($ftp_connection, $remote_zip_path);
            
                   echo 'Removing unnecessary files.';
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
           return Command::SUCCESS;
           // } catch (\Throwable $th) {
           //     echo $th->getMessage();
           //     if($retry <= 3){
           //         ++$retry;
           //         echo "Retring $retry";
           //         return $this->handle();
           //     }
           // }
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

    private function uploadWithProgress($ftp_connection, $ftp_server, $ftp_username, $ftp_password, $upload_result, $remote_zip_path, $zip_path)
    {

        $local_file_size  = filesize($zip_path);
        echo "INFO: Size ".round((($local_file_size/1024))/1024, 2)." MB\n";
        while ($upload_result == FTP_MOREDATA) {
            // Establish a new connection to FTP server
            if (!isset($new_ftp_connection)) {
                $new_ftp_connection = ftp_connect($ftp_server);
                $login_result2 = ftp_login($new_ftp_connection, $ftp_username, $ftp_password);
            }

            // Retreive size of uploaded file.
            if (isset($new_ftp_connection)) {
                clearstatcache(); // <- this must be included!!
                $remote_file_size = ftp_size($new_ftp_connection, $remote_zip_path);
            }

            // Calculate upload progress
            if (isset($remote_file_size) && $remote_file_size > 0) {
                $progress = ($remote_file_size / $local_file_size) * 100;
                echo "Uploading: [";
                $barLength = 50;
                $bar = str_repeat("=", floor($progress / 100 * $barLength));
                $bar .= str_repeat(" ", $barLength - strlen($bar));
                echo $bar;
                echo "] " . round($progress) . "%\r";
                flush();
            }
            if (!isset($progress) || $progress == 100) {
                break;
            }
            ftp_nb_continue($ftp_connection);
        }
    }
}
