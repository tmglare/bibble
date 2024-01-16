<?php

namespace App\Services\Loan;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\SentMessage;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Borrower;
use App\Models\InventoryItem;
use App\Mail\Overdue;

use Inertia\Inertia;
use Carbon\Carbon;

class OverdueService {
	public function sendEmail($id): ?SentMessage {
		$loan = Loan::find($id);
		$borrowerEmail = $loan->borrower()->value("email");
		$sentMessage = null;
		if (! empty($borrowerEmail)) {
			$sentMessage = Mail::to($borrowerEmail)->send(new Overdue($loan));
		}
		return $sentMessage;
	}

	public function sendAllEmails() {
		$today = Carbon::now()->startOfDay();
		$loans = Loan::whereNull("returned_on")->where("due_back","<",$today)->get();
		$loans->each(
			function ($loan) use ($today) {
				$loanId = $loan->id;
				$title = $loan->inventoryItem->book->title;
				$dueBack = (new Carbon($loan->due_back))->startOfDay();
				$interval = $dueBack->diffInDays($today);
				if ($interval % 7 == 1) {
					$borrower = $loan->borrower()->first();
					$borrowerEmail = $borrower->email;
					$borrowerName  = $borrower->name;
					$sentMessage = null;
					if (! empty($borrowerEmail)) {
						try {
							$sentMessage = Mail::to($borrowerEmail)->send(new Overdue($loan));
							\Illuminate\Support\Facades\Log::info("Reminder sent to $borrowerName $borrowerEmail $title $dueBack");
						} catch (\Exception $e) {
							\Illuminate\Support\Facades\Log::info("Reminder email to $borrowerName failed:{$e->getMessage()}");
						}
					}
					return $sentMessage;
				}
			}
		);
	}
}
