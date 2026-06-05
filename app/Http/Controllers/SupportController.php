<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportController extends Controller
{
    /**
     * Show the support request creation form.
     */
    public function create(): View
    {
        return view('support.create');
    }

    /**
     * Store a newly created support request.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ];

        if (! $request->user()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'email', 'max:255'];
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'subject.required' => 'Vui lòng nhập tiêu đề hỗ trợ.',
            'subject.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'message.required' => 'Vui lòng nhập nội dung yêu cầu hỗ trợ.',
        ]);

        if ($request->user()) {
            $validated['name'] = $request->user()->name;
            $validated['email'] = $request->user()->email;
        }

        SupportTicket::create(array_merge($validated, [
            'user_id' => $request->user()?->id,
            'status' => 'open',
        ]));

        return redirect()
            ->route('support.create')
            ->with('success', 'Yêu cầu hỗ trợ của bạn đã được gửi thành công. Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}
