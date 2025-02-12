<?php
/**
 *
 * @category Hq.ai
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <Hq.ai: 202309-V1.2>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use App\Models\Setting;
use Illuminate\Filesystem\Filesystem;

class GeneralController extends Controller
{

    public $label;

    function __construct()
    {
        $this->label = 'General Setting';
    }
    public function index()
    {
        $label = $this->label;
        return view('panel.admin.general.index', compact('label'));
    }
    
    public function storageLink()
    {
        try {
            shell_exec('pwd');
            shell_exec('cd '.base_path('public'));
            shell_exec('rm storage');
            // if(is_dir(public_path('storage')))
            // \File::deleteDirectory(public_path('storage'));

            // \Artisan::call('storage:link');

            \File::link(storage_path('app\public'), public_path('storage'));

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 1000);

            return back()->with('success', 'Storage linked Successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function OptimizeClear()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            return back()->with('success', 'Optimization Cleared!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function sessionClear()
    {
        try {
            $directory = config('session.files');
            $ignoreFiles = ['.gitignore', '.', '..'];

            $files = scandir($directory);

            foreach ($files as $file) {
                if (!in_array($file, $ignoreFiles)) {
                    unlink($directory . '/' . $file);
                }
            }
            return back()->with('success', 'Optimization Cleared!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function backup()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            return back()->with('success', 'Backup has been taken successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function single_env_key_update($key, $value)
    {
        $file = DotenvEditor::load();
            $file->setKey($key, $value);
        $file->save();
    }
}
