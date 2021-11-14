<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Email;
use App\LeadExport\All;
use App\LeadExport\Stage;
use App\LeadExport\Status;
use App\LeadExport\Term;
use App\LeadExport\StageStatus;
use App\LeadExport\StatusTerm;
use App\LeadExport\StageTerm;
use App\LeadExport\StageStatusTerm;
use Maatwebsite\Excel\Facades\Excel;
use App\LeadImport\LeadImport;
use Illuminate\Support\Facades\Mail;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{

    function addLead(Request $req)
    {

        $Lead = new Lead;
        $Lead->fullname = $req->input('fullname');
        $Lead->emailaddress = $req->input('emailaddress');
        $Lead->phonenumber = $req->input('phonenumber');
        $Lead->stage = $req->input('stage') == NULL ? "Marketing Acquired Lead" : $req->input('stage');
        $Lead->status = $req->input('status') == NULL ? "Open" : $req->input('status');
        $Lead->message = $req->input('message');
        $Lead->save();

        $this->scheduleEmail($req);

        return $Lead;
    }

    function listLead()
    {
        return Lead::paginate(30);
    }

    function deleteLead($id)
    {

        $Lead = Lead::find($id);

        if ($Lead != NULL) {
            $Lead->delete();
            return ['message' => 'Lead successfully deleted'];
        }

        return ['message' => 'Lead not found'];
        //return $Lead;

    }

    function getLead($id)
    {
        $Lead = Lead::find($id);
        if ($Lead != NULL) {
            return $Lead;
        }

        return ['message' => 'Lead not found'];
    }

    function getLeadForm(Request $req)
    {
        $Lead = DB::table('leads')
                ->where('emailaddress', '=', $req->input('emailaddress'))
                ->where('phonenumber', '=', $req->input('phonenumber'))
                ->limit(1)
                ->get();

        if ($Lead != NULL) {
            return $Lead;
        }

        return ['message' => 'Lead not found'];
    }

    function updateLead(Request $req)
    {
        $Lead = Lead::find($req->input('id'));

        $Lead->fullname = $req->input('fullname');
        $Lead->emailaddress = $req->input('emailaddress');
        $Lead->phonenumber = $req->input('phonenumber');
        $Lead->stage = $req->input('stage');
        $Lead->status = $req->input('status');
        $Lead->message = $req->input('message');
        $Lead->updated_at = date("Y-m-d H:i:s");

        $Lead->save();
        //return $Lead;
        return $req;
    }

    function searchLead(Request $req)
    {
        // Let ABC = 100
        //============================
        if ($req->input('filterstage') != "" && $req->input('filterstatus') == "" && $req->input('searchterm') == "") {
            $Leads = Lead::where('stage', $req->input('filterstage'))
                ->get();
        }

        // Let ABC = 010
        //============================
        else if ($req->input('filterstage') == "" && $req->input('filterstatus') != "" && $req->input('searchterm') == "") {
            $Leads = Lead::where('status', $req->input('filterstatus'))
                ->get();
        }

        // Let ABC = 001
        //============================
        else if ($req->input('filterstage') == "" && $req->input('filterstatus') == "" && $req->input('searchterm') != "") {
            $Leads = Lead::where('fullname', 'like', "%{$req->input('searchterm')}%")
                ->orwhere('emailaddress', 'like', "%{$req->input('searchterm')}%")
                ->get();
        }

        // Let ABC = 110
        //============================
        else if ($req->input('filterstage') != "" && $req->input('filterstatus') != "" && $req->input('searchterm') == "") {
            $Leads = Lead::where('stage', $req->input('filterstage'))
                ->where('status', $req->input('filterstatus'))
                ->get();
        }

        // Let ABC = 011
        //============================
        else if ($req->input('filterstage') == "" && $req->input('filterstatus') != "" && $req->input('searchterm') != "") {
            $Leads = Lead::where('status', $req->input('filterstatus'))
                ->where('fullname', 'like', "%{$req->input('searchterm')}%")
                ->orwhere('emailaddress', 'like', "%{$req->input('searchterm')}%")
                ->get();
        }

        // Let ABC = 101
        //============================
        else if ($req->input('filterstage') != "" && $req->input('filterstatus') == "" && $req->input('searchterm') != "") {
            $Leads = Lead::where('stage', $req->input('filterstage'))
                ->where('fullname', 'like', "%{$req->input('searchterm')}%")
                ->orwhere('emailaddress', 'like', "%{$req->input('searchterm')}%")
                ->get();
        }

        // Let ABC = 111
        //============================
        else if ($req->input('filterstage') != "" && $req->input('filterstatus') != "" && $req->input('searchterm') != "") {
            $Leads = Lead::where('stage', $req->input('filterstage'))
                ->where('status', $req->input('filterstatus'))
                ->where('fullname', 'like', "%{$req->input('searchterm')}%")
                ->orwhere('emailaddress', 'like', "%{$req->input('searchterm')}%")
                ->get();
        }

        // Let ABC = 000
        //============================
        else {
            $Leads = Lead::all();
        }
        return $Leads;
    }

    function listActiveLead()
    {
        $Leads = Lead::where('status', 'true')
            ->get();

        return $Leads;
    }

    function exportLead()
    {

        $fileName = 'leads.csv';
        $leads = Lead::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('FullName', 'Email Address', 'Phone Number', 'State', 'Status', 'Message');

        $callback = function () use ($leads, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($leads as $lead) {
                $row['FullName']  = $lead->fullname;
                $row['Email Address']    = $lead->emailaddress;
                $row['Phone Number']    = $lead->phonenumber;
                $row['State']  = $lead->state;
                $row['Status']  = $lead->status;
                $row['Message']  = $lead->message;

                fputcsv($file, array($row['FullName'], $row['Email Address'], $row['Phone Number'], $row['State'], $row['Status'], $row['Message']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    function downloadLead($stage, $status, $term)
    {
        // Let ABC = 100
        //============================
        if ($stage != " " && $status == " " && $term == " ") {
            return (new Stage)
                ->whereStage($stage)
                ->download('leads.xlsx');
        }

        // Let ABC = 010
        //============================
        else if ($stage == " " && $status != " " && $term == " ") {
            return (new Status)
                ->whereStatus($status)
                ->download('leads.xlsx');
        }

        // Let ABC = 001
        //============================
        else if ($stage == " " && $status == " " && $term != " ") {
            return (new Term)
                ->whereTerm($term)
                ->download('leads.xlsx');
        }

        // Let ABC = 110
        //============================
        else if ($stage != " " && $status != " " && $term == " ") {
            return (new StageStatus)
                ->whereStage($stage)
                ->whereStatus($status)
                ->download('leads.xlsx');
        }

        // Let ABC = 011
        //============================
        else if ($stage == " " && $status != " " && $term != " ") {
            return (new StatusTerm)
                ->whereStatus($status)
                ->whereTerm($term)
                ->download('leads.xlsx');
        }

        // Let ABC = 101
        //============================
        else if ($stage != " " && $status == " " && $term != " ") {
            return (new StageTerm)
                ->whereStage($stage)
                ->whereTerm($term)
                ->download('leads.xlsx');
        }

        // Let ABC = 111
        //============================
        else if ($stage != " " && $status != " " && $term != " ") {
            return (new StageStatusTerm)
                ->whereStage($stage)
                ->whereStatus($status)
                ->whereTerm($term)
                ->download('leads.xlsx');
        }

        // Let ABC = 000
        //============================
        else {
            return Excel::download(new All, 'leads.xlsx');
        }
    }

    function downloadLead2($stage, $status, $term)
    {
        //return [$stage,$status,$term];

        // return (new Stage)
        //     ->whereStage($stage)
        //     ->download('leads.xlsx');

        // return (new Status)
        //     ->whereStatus($status)
        //     ->download('leads.xlsx');

        return (new Term)
            ->whereTerm($term)
            ->download('leads.xlsx');

        return (new StatusTerm)
            ->whereStatus($status)
            ->whereTerm($term)
            ->download('leads.xlsx');
    }

    function importLead(Request $request)
    {

        //Excel::import(new LeadImport, $request->file('file'));
        // $LeadImport = new LeadImport;
        // $LeadImport->import($request->file('file'));
        // return 'OK Upload';


        try {
            $LeadImport = new LeadImport;
            $LeadImport->import($request->file('file'));
            return 'OK Upload';
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            // foreach ($failures as $failure) {
            //     $failure->row(); // row that went wrong
            //     $failure->attribute(); // either heading key (if using heading row concern) or column index
            //     $failure->errors(); // Actual error messages from Laravel validator
            //     $failure->values(); // The values of the row that has failed.
            // }

            return $failures;
        }
    }

    function scheduleEmail($user)
    {

        $EmailContents = [
            ["Lead Email Automation 01", "LeadEmailAuto01", " +5 minutes"],
            ["Lead Email Automation 02", "LeadEmailAuto02", " +10 minutes"],
            ["Lead Email Automation 03", "LeadEmailAuto03", " +15 minutes"],
        ];

        foreach ($EmailContents as list($subject, $body, $minuteinterval)) {
            $Email = new Email;
            $Email->feature = "Lead Email Automation";
            $Email->from = "no-reply@gmail.com";
            $Email->to = $user->input('emailaddress');
            $Email->subject = $subject;
            $Email->body = $body;
            $Email->is_subscribe = "1";
            $Email->status = "Not Sent";
            $Email->schedule = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . $minuteinterval));
            $Email->save();
        }
    }

    function sendScheduleEmail()
    {
        print_r("-- sendScheduleEmail -- " . date('Y-m-d H:i:s'));

        $Email = Email::where('feature', 'Lead Email Automation')
            ->where('status', 'Not Sent')
            ->where('is_subscribe', '1')
            ->where('schedule', '<=', date('Y-m-d H:i:s'))
            ->limit(2)
            ->get();

        $this->htmlEmail($Email);
        return $Email;
    }

    function htmlEmail($Emails)
    {
        print_r("-- htmlEmail -- " . date('Y-m-d H:i:s'));

        foreach ($Emails as $Email) {
            $data = array(
                'id' => $Email->id,
                'email' => $Email->from,
                'name' => "Virat Gandhi",
                'address' =>
                [
                    'street' => "Virat Gandhi",
                    'building' => "Virat Gandhi"
                ]
            );

            $to = $Email->to;
            $subject = $Email->subject;
            $from = $Email->from;

            Mail::send(
                $Email->body,
                $data,
                function ($message) use ($to, $from, $subject) {
                    $message
                        ->to($to, 'Tutorials Point')
                        ->subject($subject)
                        ->from($from, $from);
                }
            );

            $EmailDB = Email::find($Email->id);
            $EmailDB->status = 'Sent';
            $EmailDB->save();
        }

        //return "HTML Email Sent. Check your inbox.";
    }
}
