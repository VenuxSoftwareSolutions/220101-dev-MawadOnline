<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use Response;
use Auth;
use Storage;
use Image;
use enshrined\svgSanitize\Sanitizer;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\GD\Imagick;
use App\Services\UploadService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AizUploadController extends Controller
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }


    public function index(Request $request)
    {
        $user = Auth::user();
        $all_uploads = (auth()->user()->user_type == 'seller')
            ? Upload::where('user_id', auth()->user()->id)
            : Upload::query();

        $search = null;
        $sort_by = null;

        if ($request->search != null) {
            $search = $request->search;
            $all_uploads->where('file_original_name', 'like', '%' . $request->search . '%');
        }

        $sort_by = $request->sort;

        switch ($request->sort) {
            case 'newest':
                $all_uploads->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $all_uploads->orderBy('created_at', 'asc');
                break;
            case 'smallest':
                $all_uploads->orderBy('file_size', 'asc');
                break;
            case 'largest':
                $all_uploads->orderBy('file_size', 'desc');
                break;
            default:
                $all_uploads->orderBy('created_at', 'desc');
                break;
        }

        $all_uploads = $all_uploads->paginate(60)->appends(request()->query());

        return (auth()->user()->user_type == 'seller')
            ? view('seller.uploads.index', compact('all_uploads', 'search', 'sort_by'))
            : view('backend.uploaded_files.index', compact('all_uploads', 'search', 'sort_by'));
    }

    public function create()
    {
        return (auth()->user()->user_type == 'seller')
            ? view('seller.uploads.create')
            : view('backend.uploaded_files.create');
    }


    public function show_uploader(Request $request)
    {
        return view('uploader.aiz-uploader');
    }
    public function upload(Request $request)
    {
        
        $allowedImages = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif', 'bmp', 'tiff', 'heic'];
        $allowedVideos = ['mp4', 'mpg', 'mpeg', 'webm', 'ogg', 'avi', 'mov', 'flv', 'mkv', 'wmv'];
        $allowedAudios = ['wma', 'aac', 'wav', 'mp3'];
        $allowedArchives = ['zip', 'rar', '7z'];
        $allowedDocuments = ['doc', 'txt', 'docx', 'pdf', 'csv', 'xml', 'ods', 'xlr', 'xls', 'xlsx'];
    
        $allowedExtensions = array_merge($allowedImages, $allowedVideos, $allowedAudios, $allowedArchives, $allowedDocuments);
    
        if ($request->hasFile('aiz_file')) {
            $file = $request->file('aiz_file');
            $extension = strtolower($file->getClientOriginalExtension());
    
            if ($extension === 'svg') {
                return response()->json(['error' => 'SVG files are not allowed for upload.'], 400);
            }
    
            if (!in_array($extension, $allowedExtensions)) {
                return response()->json(['error' => 'File type not allowed.'], 400);
            }
            /*clamav scan
            $validator = Validator::make($request->all(), [
                'aiz_file' => 'clamav',
            ]);
            
            if ($validator->fails()) {
                Log::warning('Virus detected in file upload by user ID: ' . Auth::id());

                return response()->json(['error' => 'Virus detected in uploaded file.'], 400);
            }*/
            
    
            $upload = new Upload;
            $upload->file_original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $path = 'uploads/all/';
            $filename = preg_replace('/[^A-Za-z0-9\-]/', '', $upload->file_original_name) . '.' . $extension;
    
            try {
                if (in_array($extension, $allowedImages)) {
                    $maxDimension = 1280;
                    $quality = 90;
                    $tempPath = $this->uploadService->processImage($file, $maxDimension, $quality);
                    $extension = 'jpg';
                    $filename = preg_replace('/[^A-Za-z0-9\-]/', '', $upload->file_original_name) . '.' . $extension;

                    $tempFile = new \Symfony\Component\HttpFoundation\File\File($tempPath);
                    $uploadedFile = new \Illuminate\Http\UploadedFile(
                        $tempFile->getPathname(),
                        $filename,
                        $tempFile->getMimeType(),
                        null,
                        true
                    );
    
                    $uploadedFile->storeAs($path, $filename, 'local');
                } else {
                    $filename = $file->storeAs($path, $file->getClientOriginalName(), 'local');
                }
    
                $size = Storage::disk('local')->size($path . $filename);
    
                $upload->extension = $extension;
                $upload->file_name = 'public/' . $path . $filename;
                $upload->user_id = Auth::id();
                $upload->type = $this->getFileType($extension, $allowedImages, $allowedVideos, $allowedAudios, $allowedArchives, $allowedDocuments);
                $upload->file_size = $size;
                $upload->save();
    
                return response()->json(['success' => 'File uploaded successfully.'], 200);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
            }
        }
    
        return response()->json(['error' => 'No file provided.'], 400);
    }
    
    private function getFileType($extension, $images, $videos, $audios, $archives, $documents)
    {
            if (in_array($extension, $images)) {
                return 'image';
            }
            if (in_array($extension, $videos)) {
                return 'video';
            }
            if (in_array($extension, $audios)) {
                return 'audio';
            }
            if (in_array($extension, $archives)) {
                return 'archive';
            }
            if (in_array($extension, $documents)) {
                return 'document';
            }
            return 'unknown';
    }

    public function get_uploaded_files(Request $request)
    {
        $uploads = Upload::where('user_id', Auth::user()->id);
        if ($request->search != null) {
            $uploads->where('file_original_name', 'like', '%' . $request->search . '%');
        }
        if ($request->sort != null) {
            switch ($request->sort) {
                case 'newest':
                    $uploads->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $uploads->orderBy('created_at', 'asc');
                    break;
                case 'smallest':
                    $uploads->orderBy('file_size', 'asc');
                    break;
                case 'largest':
                    $uploads->orderBy('file_size', 'desc');
                    break;
                default:
                    $uploads->orderBy('created_at', 'desc');
                    break;
            }
        }
        return $uploads->paginate(60)->appends(request()->query());
    }

    public function destroy($id)
    {
        $upload = Upload::findOrFail($id);

        if (auth()->user()->user_type == 'seller' && $upload->user_id != auth()->user()->id) {
            flash(translate("You don't have permission for deleting this!"))->error();
            return back();
        }
        try {
            if (env('FILESYSTEM_DRIVER') != 'local') {
                Storage::disk(env('FILESYSTEM_DRIVER'))->delete($upload->file_name);
                if (file_exists(public_path() . '/' . $upload->file_name)) {
                    unlink(public_path() . '/' . $upload->file_name);
                }
            } else {
                unlink(public_path() . '/' . $upload->file_name);
            }
            $upload->delete();
            flash(translate('File deleted successfully'))->success();
        } catch (\Exception $e) {
            $upload->delete();
            flash(translate('File deleted successfully'))->success();
        }
        return back();
    }

    public function bulk_uploaded_files_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $file_id) {
                $this->destroy($file_id);
            }
            return 1;
        } else {
            return 0;
        }
    }

    public function get_preview_files(Request $request)
    {
        $ids = explode(',', $request->ids);
        $files = Upload::whereIn('id', $ids)->get();
        $new_file_array = [];
        foreach ($files as $file) {
            $file['file_name'] = my_asset($file->file_name);
            if ($file->external_link) {
                $file['file_name'] = $file->external_link;
            }
            $new_file_array[] = $file;
        }
        // dd($new_file_array);
        return $new_file_array;
        // return $files;
    }

    public function all_file()
    {
        $uploads = Upload::all();
        foreach ($uploads as $upload) {
            try {
                if (env('FILESYSTEM_DRIVER') != 'local') {
                    Storage::disk(env('FILESYSTEM_DRIVER'))->delete($upload->file_name);
                    if (file_exists(public_path() . '/' . $upload->file_name)) {
                        unlink(public_path() . '/' . $upload->file_name);
                    }
                } else {
                    unlink(public_path() . '/' . $upload->file_name);
                }
                $upload->delete();
                flash(translate('File deleted successfully'))->success();
            } catch (\Exception $e) {
                $upload->delete();
                flash(translate('File deleted successfully'))->success();
            }
        }

        Upload::query()->truncate();

        return back();
    }

    //Download project attachment
    public function attachment_download($id)
    {
        $project_attachment = Upload::find($id);
        try {
            $file_path = public_path($project_attachment->file_name);
            return Response::download($file_path);
        } catch (\Exception $e) {
            flash(translate('File does not exist!'))->error();
            return back();
        }
    }
    //Download project attachment
    public function file_info(Request $request)
    {
        $file = Upload::findOrFail($request['id']);

        return (auth()->user()->user_type == 'seller')
            ? view('seller.uploads.info', compact('file'))
            : view('backend.uploaded_files.info', compact('file'));
    }
}
