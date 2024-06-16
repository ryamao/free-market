<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\DirectMailsStoreRequest;
use App\Mail\DirectMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

final class SendDirectMail
{
    public function __invoke(DirectMailsStoreRequest $request, User $user): void
    {
        $mail = new DirectMail(
            title: $request->title,
            content: $request->content,
        );
        Mail::to($user->email)->send($mail);
    }
}
