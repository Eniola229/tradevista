<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Newletter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class BlogController extends Controller
{
    public function contact(Request $request)
    {
        return view('contact');
    }
    public function blogs(Request $request)
    {
        return view('blogs');
    }

    public function about(Request $request)
    {
        return view('about');
    }
        // Store contact form submission
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'nullable|max:255',
            'message' => 'required|string',
        ]);

        Contact::create($validated);

        return redirect()->back()->with('message', 'Message sent successfully!');
    }

public function storeNewsletter(Request $request)
{
    // Custom validation for the email field
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|max:255|unique:newletters,email',
    ], [
        'email.unique' => 'This email has already been taken.',
    ]);

    // If validation fails, it will automatically redirect back
    try {
        // Attempt to validate and get the validated data
        $validatedData = $validator->validate();

        // If validation passes, create the new entry
        Newletter::create($validatedData);

        // Redirect with success message
        return redirect()->back()->with('submessage', 'Subscribed successfully!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, flash the error message and redirect back
        Session::flash('suberror', 'This email has already been taken.');
        return back()->withInput(); // Keep the input so the user doesn't have to re-enter it
    }
}

    // List all submissions for admin
    // public function index()
    // {
    //     $contacts = Contact::all();
    //     return view('contacts.index', compact('contacts'));
    // }

    // // Edit contact submission
    // public function edit($id)
    // {
    //     $contact = Contact::findOrFail($id);
    //     return view('contacts.edit', compact('contact'));
    // }

    // // Update contact submission
    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'website' => 'nullable|url',
    //         'message' => 'required|string',
    //     ]);

    //     $contact = Contact::findOrFail($id);
    //     $contact->update($validated);

    //     return redirect()->route('contacts.index')->with('success', 'Message updated successfully!');
    // }

    // // Delete contact submission
    // public function destroy($id)
    // {
    //     $contact = Contact::findOrFail($id);
    //     $contact->delete();

    //     return redirect()->route('contacts.index')->with('success', 'Message deleted successfully!');
    // }
}
