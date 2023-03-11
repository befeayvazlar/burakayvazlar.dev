<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        //dd($request->recaptchaToken);

        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-ZğüşöçıİĞÜÖÇ]+(?:\s[a-zA-ZğüşöçıĞÜŞÖÇ]+)+$/',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'required|email',
            'message' => 'required',
            'recaptchaToken' => ['required', new ReCaptcha($request->recaptchaToken)]
        ],  ['name.required' => 'Lütfen adınızı soyadınızı giriniz.',
            'phone.required' => 'Lütfen geçerli bir telefon numarası giriniz.',
            'email.required' => 'Lütfen geçerli bir mail adresi giriniz.',
            'message.required' => 'Lütfen bir mesaj yazınız.']);

        Mail::to('burakefeayvazlar@outlook.com')
            ->send(new ContactMail($validated['name'], $validated['phone'], $validated['email'], $validated['message']));

        return $request->all();
    }
}
