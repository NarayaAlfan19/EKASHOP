<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = DB::getSchemaBuilder()->hasTable('inquiries')
            ? DB::table('inquiries')->latest()->paginate(15)
            : collect();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show($id)
    {
        $inquiry = DB::table('inquiries')->where('id', $id)->first();
        return view('admin.inquiries.show', compact('inquiry'));
    }
}