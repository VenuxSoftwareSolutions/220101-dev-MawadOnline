<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BuJob; // You'll need to create this model

class BulkJobController extends Controller
{
    const STAGE_MAP = [
        'VENT' => 'Pending',
        'VSUB' => 'Submitted',
        'AIPROC' => 'AI Processing',
        'AIDONE' => 'AI Completed',
        'COMP' => 'Completed'
    ];

    public function index(Request $request)
    {
        $jobs = BuJob::where('vendor_user_id', 337)
            ->when($request->search, function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('id', 'like', '%'.$request->search.'%')
                      ->orWhere('vendor_products_file', 'like', '%'.$request->search.'%')
                      ->orWhere('stage', 'like', '%'.$request->search.'%')
                      ->orWhere('error_msg', 'like', '%'.$request->search.'%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('seller.bulk-jobs.index', [
            'jobs' => $jobs,
            'stageMap' => self::STAGE_MAP
        ]);
    }


    public function destroy($id)
    {
        $job = BuJob::where('vendor_user_id', 336)->findOrFail($id);
        
        // Delete associated files
        foreach (['vendor_products_file', 'preprocessed_file', 'ai_processed_file', 'error_file'] as $field) {
            if ($job->$field && Storage::exists($job->$field)) {
                Storage::delete($job->$field);
            }
        }
        
        $job->delete();
        
        return redirect()->back()->with('success', 'Job deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('id', []);
        
        $jobs = BuJob::where('vendor_user_id', 336)
            ->whereIn('id', $ids)
            ->get();

        foreach ($jobs as $job) {
            foreach (['vendor_products_file', 'preprocessed_file', 'ai_processed_file', 'error_file'] as $field) {
                if ($job->$field && Storage::exists($job->$field)) {
                    Storage::delete($job->$field);
                }
            }
            $job->delete();
        }

        return redirect()->back()->with('success', 'Selected jobs deleted successfully');
    }

    public function downloadProductFile($id)
    {
        $job = BuJob::where('vendor_user_id', auth()->id())
                    ->findOrFail($id);

        $path = $job->vendor_products_file;
        if (! $path || ! Storage::disk('mwd_storage')->exists($path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('mwd_storage')->download($path);
    }


    public function downloadErrorFile($id)
    {
        $job = BuJob::where('vendor_user_id', auth()->id())
                    ->findOrFail($id);

        $path = $job->error_file;
        if (! $path || ! Storage::disk('mwd_storage')->exists($path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('mwd_storage')->download($path);
    }

}