<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BuJob; 
use Illuminate\Http\JsonResponse;

class BulkJobController extends Controller
{
    const STAGE_MAP = [
        'VENT' => 'Pending',
        'VSUB' => 'Submitted',
        'AIPROC' => 'AI Processing',
        'AIDONE' => 'AI Completed',
        'COMP' => 'Completed'
    ];
    protected const DOWNLOAD_DISK = 'mwd_storage';

    public function index(Request $request)
    {
        if ($request->has('search') && trim($request->search) === '') {
            return redirect()->route('seller.bulk.jobs.history');
        }

        $jobs = BuJob::where('vendor_user_id', auth()->id())
            ->when($request->filled('search'), function($q) use ($request) {
                $keyword = '%'.trim($request->search).'%';
                $q->where(function($q2) use ($keyword) {
                    $q2->where('vendor_products_file',                'like', $keyword)
                    ->orWhere('stage',             'like', $keyword)
                    ->orWhere('error_msg',         'like', $keyword)
                    ->orWhere('total_rows',        'like', $keyword);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only('search'));

        return view('seller.bulk-jobs.index', [
            'jobs'     => $jobs,
            'stageMap' => self::STAGE_MAP,
        ]);
    }


    public function destroy($id)
    {

        $job = BuJob::where('vendor_user_id', auth()->id())->findOrFail($id);
    
        foreach (['vendor_products_file', 'preprocessed_file', 'ai_processed_file', 'error_file'] as $field) {
            if ($job->$field && Storage::disk('mwd_storage')->exists($job->$field)) {
                Storage::disk('mwd_storage')->delete($job->$field);
            }
        }
    
        $job->delete();
    
        return redirect()->route('seller.bulk.jobs.history')->with('success', 'Job deleted successfully!');
    }
    

    public function bulkDelete(Request $request)
    {

        $jobs = BuJob::where('vendor_user_id', 336)
                 ->whereIn('id', $request->job_ids)
                 ->get();

        foreach ($jobs as $job) {
            foreach (['vendor_products_file', 'preprocessed_file', 'ai_processed_file', 'error_file'] as $field) {
                if ($job->$field && Storage::disk('mwd_storage')->exists($job->$field)) {
                    Storage::disk('mwd_storage')->delete($job->$field);
                }
            }
            $job->delete();
        }

        return redirect()->route('seller.bulk.jobs.history')->with('success', 'Selected jobs deleted successfully!');
    
    }


    public function downloadProductFile($id)
    {
        $job = BuJob::where('vendor_user_id', auth()->id())
                ->findOrFail($id);

        $identifier = 'u' . $job->vendor_user_id . '-j' . $job->id;
        $diskFilename = "vendor-file-{$identifier}.csv";
        $path = "{$identifier}/{$diskFilename}";
        if (! Storage::disk(self::DOWNLOAD_DISK)->exists($path)) {
            abort(404, 'File not found.');
        }
        return Storage::disk(self::DOWNLOAD_DISK)
        ->download($path, 'vendor-file.csv');


    }


    public function downloadErrorFile($id)
    {
        $job = BuJob::where('vendor_user_id', auth()->id())
        ->findOrFail($id);

        $identifier = 'u' . $job->vendor_user_id . '-j' . $job->id;

        $diskFilename = "error-file-{$identifier}.csv";
        $path = "{$identifier}/{$diskFilename}";

        if (! Storage::disk(self::DOWNLOAD_DISK)->exists($path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk(self::DOWNLOAD_DISK)
                ->download($path, 'error-file.csv');

    }
        /**
     * Return just the progress % for a single job.
     */
    public function getProgress($id): JsonResponse
    {
        $job = BuJob::where('vendor_user_id', auth()->id())
                    ->findOrFail($id);

        return response()->json([
            'progress' => (int) $job->progress,
        ]);
    }


}