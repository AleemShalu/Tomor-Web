<?php

namespace App\Http\Controllers\Web\Admin\FeedbackAndComplaint;

use App\Http\Controllers\Controller;
use App\Models\PlatformRating;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeedbackAndComplaintController extends Controller
{
    /**
     * Display a listing of all feedback and complaints.
     */
    public function index()
    {
        $feedbacks = PlatformRating::all();

        $complaints = Report::all();

        $report_type_id = 2;
        $complaintsCounts = Report::with('report_subtype')
            ->whereHas('report_subtype', function ($query) use ($report_type_id) {
                $query->where('report_type_id', $report_type_id);
            })
            ->count();

        $report_type_id = 1;
        $feedbacksCounts = Report::with('report_subtype')
            ->whereHas('report_subtype', function ($query) use ($report_type_id) {
                $query->where('report_type_id', $report_type_id);
            })
            ->count();


        return view('admin.feedback-and-complaint.index', compact('feedbacks', 'complaints', 'feedbacksCounts', 'complaintsCounts'));
    }

    public function settings()
    {
        return view('admin.feedback-and-complaint.settings');
    }

    /**
     * Display a listing of feedback only.
     */
    public function indexOrdersFeedback(Request $request)
    {
        $query = PlatformRating::with('user');

        if ($request->has('platform') && $request->get('platform') != '') {
            $query->where('platform', $request->get('platform'));
        }

        if ($request->has('date_from') && $request->get('date_from') != '') {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->has('date_to') && $request->get('date_to') != '') {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $feedbacks = $query->paginate(10);


        return view('admin.feedback-and-complaint.feedback.index-orders', compact('feedbacks'));
    }

    public function indexUsersFeedback(Request $request)
    {
        $report_type_id = 1;

        $query = Report::whereHas('report_subtype', function ($query) use ($report_type_id) {
            $query->where('report_type_id', $report_type_id);
        });
        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('date_from') && $request->get('date_from') != '') {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->has('date_to') && $request->get('date_to') != '') {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $complaints = $query->paginate(10);

//        return response()->json($complaints);

        return view('admin.feedback-and-complaint.feedback.index', compact('complaints'));
    }


    /**
     * Display a listing of complaints only.
     */
    public function indexComplaint(Request $request)
    {
        $report_type_id = 2;

        $query = Report::whereHas('report_subtype', function ($query) use ($report_type_id) {
            $query->where('report_type_id', $report_type_id);
        });

        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('date_from') && $request->get('date_from') != '') {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->has('date_to') && $request->get('date_to') != '') {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $complaints = $query->paginate(10);

//        return response()->json($complaints);

        return view('admin.feedback-and-complaint.complaint.index', compact('complaints'));
    }

    public function showComplaint($id)
    {
        $report = Report::findOrFail($id);
        return view('admin.feedback-and-complaint.complaint.view', compact('report'));
    }

    public function editComplaint($id)
    {
        $report = Report::findOrFail($id);

        $report->update(['status' => 'In Progress']);
        return view('admin.feedback-and-complaint.complaint.replay', compact('report'));
    }


    public function updateComplaint(Request $request, $id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'body_reply' => 'nullable|string',
            'is_resolved' => 'required|boolean',
            'status' => 'required|string|in:Pending Review,In Progress,Resolved',
        ]);

        // Find the report
        $report = Report::findOrFail($id);

        // If the complaint is marked as resolved, set the resolved_time
        if ($validatedData['is_resolved'] && $validatedData['status'] == 'Resolved') {
            $validatedData['resolved_time'] = Carbon::now()->toDateTimeString();
        }

        // Update the report
        $report->update($validatedData);

        // Redirect or return as needed
        return back()->with('success', 'Complaint updated successfully!');
    }

}
