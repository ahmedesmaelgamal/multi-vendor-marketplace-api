<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactUsController extends Controller
{
    public function index(request $request)
    {
        if ($request->ajax()) {
            $contact = ContactUs::orderBy('id', 'DESC')->get();
            return Datatables::of($contact)
                ->addColumn('action', function ($contact) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $contact->id . '" data-title="' . $contact->name . '"><i class="fas fa-trash"></i></button>
                       ';
                })
                ->editColumn('created_at', function ($contact) {
                    return $contact->created_at->diffForHumans();
                })
                ->editColumn('email', function ($contact) {
                    return '<a target="_blank" href="mailto:' . $contact->email . '">' . $contact->email . '</a>';
                })->addColumn('message', function ($contact) {
                    $shortText = \Illuminate\Support\Str::limit($contact->message, 30, '...');
                    return '
        <span class="showMessageLink text-primary"
              style="cursor:pointer"
              data-message="' . e($contact->message) . '">
              ' . e($shortText) . '
        </span>
    ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.contact.index');
        }
    }

    public function delete(request $request)
    {
        ContactUs::where('id', $request->id)->delete();
        return response(['message' => 'تمت عملية الحذف بنجاح', 'status' => 200], 200);
    }
}
