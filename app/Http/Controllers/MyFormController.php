<?php

namespace App\Http\Controllers;

use App\Models\MyForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class MyFormController extends Controller
{
    //
    public function index()
    {
        return view('welcome');
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {

        $vaidation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if($vaidation->fails()) {
            // dd($vaidation->errors());
            return response()->json(['errors' => $vaidation->errors()]);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');

        try {
            Storage::put('profiles/' . $request->file('image')->getClientOriginalName(), file_get_contents($request->file('image')->getRealPath()));

            $myForm = new MyForm([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'image' => $request->file('image')->getClientOriginalName(),
            ]);
            $myForm->save();
            return response()->json(['success' => 'Successfully Saved']);
        }
        catch(\Exception $e) {
            return response()->json(['error' => 'There was an error saving your form. Please try again later.']);
        }
    }
}
