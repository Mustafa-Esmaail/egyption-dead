<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Traits\NotificationFirebaseTrait;
use App\Http\Traits\Upload_Files;
use App\Http\Traits\ResponseTrait;
use App\Models\Admin;

use App\Models\Booking;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Queue;
class TranslationController extends Controller
{
    use Upload_Files,ResponseTrait;

    private $view_path = 'Admin.CRUDS.translation.';

    protected $permission = 'translation';

    public function __construct()
    {
        $this->middleware("permission:show $this->permission")->only('index');
        $this->middleware("permission:add $this->permission")->only(['create', 'store']);
        $this->middleware("permission:edit $this->permission")->only(['edit', 'update']);
        $this->middleware("permission:delete $this->permission")->only('destroy');
    }

    public function index(Request $request)
    {       
       
        $locale = session_lang();
        if($request->query('transLang')){
            $locale = $request->query('transLang');
        }

        $data['lang']=$locale;
        
        
        $translationsPath = resource_path("lang/{$locale}");
        $translationKeys = [];

        if (!File::exists($translationsPath)) {
            return $this->updateResponse('this translation file not found');
        }
        
        // Get all PHP files in the language directory
        $files = File::allFiles($translationsPath);

        $translations = []; // Initialize an empty array to store merged translations

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME); // Get the filename
            $transFile = include $file; // Include the translation file
        
            if (is_array($transFile)) { // Ensure the file returns an array
                $translations = array_merge($translations, $transFile); // Merge the current file's translations
            }
        }

        $collection = collect($translations);

        // dd($collection['login successfully']);
        if ($searchValue = $request->searchValue) { 


            $collection = $collection->filter(function ($value, $key) use ($searchValue) {

                return (stripos($key, $searchValue) !== false || stripos($value, $searchValue) !== false);
            });

        }

        $perPage = 50; 
        $currentPage = request()->get('page', 1); 

        $paginatedData = new LengthAwarePaginator(
                                        $collection->forPage($currentPage, $perPage),
                                        $collection->count(), 
                                        $perPage, 
                                        $currentPage,
                                        ['path' => request()->url(), 'query' => request()->query()] // Add query parameters
                        );

        $data['translations'] = $paginatedData;
        $data['code'] ='200';

        return view("$this->view_path.index",compact('data'));
    }

    // ****************************************************************************
    // ****************************************************************************
    public function update(Request $request){

        $locale = $request->transLang ?? session_lang();
        $translationsPath = resource_path("lang/{$locale}");
        
        if (!File::exists($translationsPath)) {
            return response()->json(['error' => 'Translation files not found.'], 404);
        }
    
        // Get all PHP files in the translations directory
        $files = File::allFiles($translationsPath);
    
        foreach ($files as $file) {
            $filePath = $file->getPathname(); // Full file path
            $translations = include $filePath; // Load current translations
    
            if (!is_array($translations)) {
                continue; // Skip non-array files
            }
    
            $values = $request->values;
    
            // Merge new values into translations
            $translations = array_merge($translations, $values);
    
            // Only write back the file if there were changes
            if ($translations !== include $filePath) {
                $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

                // dd($filePath);
                File::put($filePath, $content); // Write updated translations
            }
        }


        // return re
        return $this->updateResponse('updated successfuly');
        // return redirect()->back()->with(['message'=>'success']);
    }

   

}//end clas
