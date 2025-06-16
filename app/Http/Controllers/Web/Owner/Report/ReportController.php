<?php

namespace App\Http\Controllers\Web\Owner\Report;

use App\Http\Controllers\Web\Owner\Controller;
use App\Mail\ComplaintSubmitted;
use App\Models\Report;
use App\Models\ReportSubtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexComplaint()
    {

    }

    public function indexInquire()
    {
        return view('owner.support.complaint.inquiries');
    }

    public function searchComplaint(Request $request)
    {
        $ticketNumber = $request->input('ticket_number');
        $attemptsKey = 'complaint_attempts_' . $request->ip();


        $report_type_id = 2;
        $complaint = Report::with('report_subtype')
            ->whereHas('report_subtype', function ($query) use ($report_type_id) {
                $query->where('report_type_id', $report_type_id);
            })
            ->where('ticket_id', $ticketNumber)
            ->first();

//        return response()->json($complaint);
        if (!$complaint) {
            $attempts = session()->get($attemptsKey, 0);
            $attempts++;

            if ($attempts >= 4) {
                $lockoutDuration = now()->addMinutes(30)->diffForHumans();
                session()->put($attemptsKey, $attempts);
                return redirect()->route('support.inquiries')->with('error', 'Complaint not found. Please enter a valid ticket number.')->with('lockoutDuration', $lockoutDuration);
            } else {
                session()->put($attemptsKey, $attempts);
                return redirect()->route('support.inquiries')->with('error', 'Complaint not found. Please enter a valid ticket number.');
            }
        }

        // Reset attempts on successful search
        session()->forget($attemptsKey);

        return view('owner.support.complaint.inquiries', ['complaint' => $complaint]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function createComplaint()
    {
        $report_type_id = 2;
        $options = ReportSubtype::all()->where('report_type_id', $report_type_id);
        return view('owner.support.complaint.complaint', compact('options'));
    }

    public function createFeedback()
    {
        $report_type_id = 1;
        $options = ReportSubtype::all()->where('report_type_id', $report_type_id);
        return view('owner.support.feedback.feedback', compact('options'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function storeFeedback(Request $request)
    {
        // Validate the form data
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'report_subtype' => 'required|exists:report_subtypes,id',
            'report_title' => 'nullable|string|max:255',
            'body_message' => 'nullable|string',
            //'remember-me' => 'accepted',
        ]);

        // Create a new report instance
        $report = new Report();
        $report->uuid = Str::uuid()->toString(); // Generate a new UUID
        $report->first_name = $request->input('firstname');
        $report->last_name = $request->input('lastname');
        $report->email = $request->input('email');
        $report->report_subtype_id = $request->input('report_subtype');
        $report->report_title = $request->input('report_title');
        $report->body_message = $request->input('body_message');

        // Save the report to the database
        $report->save();


        // Redirect back with a success message
        return redirect()->back()->with('success', 'Report submitted successfully. Thanks for your time.');
    }

    public function storeComplaint(Request $request)
    {
        // Validate the form data
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'report_subtype' => 'required|exists:report_subtypes,id',
            'report_title' => 'nullable|string|max:255',
            'body_message' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:1000', // Adjust the allowed mime types and max file size as needed
            //'remember-me' => 'accepted',
        ]);

        // Create a new report instance
        $report = new Report();
        $report->ticket_id = $this->generateTicketNumber();
        $report->first_name = $request->input('firstname');
        $report->last_name = $request->input('lastname');
        $report->email = $request->input('email');
        $report->report_subtype_id = $request->input('report_subtype');
        $report->report_title = $request->input('report_title');
        $report->body_message = $request->input('body_message');
        $report->submission_time = now(); // Set the submission_time field to the current timestamp
        $report->status = 'Pending Review';

        // Handle the file upload
        if ($request->hasFile('attachment')) {
            $complaintAttachment = $request->file('attachment');
            $AttachmentsFolder = 'complaint/' . $report->ticket_id . '/attachments';
            $AttachmentPath = $complaintAttachment->storeAs(
                $AttachmentsFolder,
                uniqid('ca-attachment-', true) . '.' . $complaintAttachment->getClientOriginalExtension(),
                getSecondaryStorageDisk()
            );
            $report->attachments = $AttachmentPath;
        }

        // Save the report to the database
        $report->save();

        // Send the email to the user
        Mail::to($report->email)->send(new ComplaintSubmitted($report->ticket_id));

        Session::flash('ticketNumber', $report->ticket_id);


        // Redirect back with a success message
        return redirect()->back()->with('success', 'Submitted successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function showComplaint($uuid)
    {
        $complaint = Report::with('report_subtype')->where('uuid', $uuid)->first(); // Use "first()" instead of "all()" to get a single record.

        if (!$complaint) {
            // Add some handling for when the complaint with the given UUID is not found.
            abort(404, 'Complaint not found.');
        }
//        return response()->json($complaint);
        return view('owner.support.complaint.show-replay-complaint', compact('complaint'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $reportBranch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $reportBranch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $reportBranch)
    {
        //
    }

    function generateTicketNumber()
    {
        return 'CA' . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
