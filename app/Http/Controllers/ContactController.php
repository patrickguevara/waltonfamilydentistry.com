<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Contact');
    }

    public function store(ContactFormRequest $request): RedirectResponse
    {
        ContactSubmission::create($request->validated());

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
