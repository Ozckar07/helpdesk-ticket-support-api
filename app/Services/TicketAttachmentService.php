<?php
namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketMessage;
use App\Models\User;
use App\Repositories\Contracts\TicketAttachmentRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketAttachmentService
{
    public function __construct(
        private readonly TicketAttachmentRepositoryInterface $ticketAttachmentRepository
    ) {
    }

    /**
     * @param UploadedFile[] $files
     * @return TicketAttachment[]
     */
    public function uploadMany(
        Ticket $ticket,
        User $user,
        array $files,
        ?TicketMessage $ticketMessage = null,
        string $disk = 'local'
    ): array {
        $attachments = [];

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $attachments[] = $this->uploadOne(
                ticket: $ticket,
                user: $user,
                file: $file,
                ticketMessage: $ticketMessage,
                disk: $disk
            );
        }

        return $attachments;
    }

    public function uploadOne(
        Ticket $ticket,
        User $user,
        UploadedFile $file,
        ?TicketMessage $ticketMessage = null,
        string $disk = 'local'
    ): TicketAttachment {
        $originalName = $file->getClientOriginalName();
        $extension    = $file->getClientOriginalExtension();
        $storedName   = Str::uuid() . ($extension ? ".{$extension}" : '');
        $directory    = "tickets/{$ticket->uuid}";

        if ($ticketMessage) {
            $directory .= "/messages/{$ticketMessage->uuid}";
        }

        $path  = Storage::disk($disk)->putFileAs($directory, $file, $storedName);

        return $this->ticketAttachmentRepository->create([
            'ticket_id'         => $ticket->id,
            'ticket_message_id' => $ticketMessage?->id,
            'uploaded_by_id'    => $user->id,
            'original_name'     => $originalName,
            'stored_name'       => $storedName,
            'disk'              => $disk,
            'path'              => $path,
            'mime_type'         => $file->getMimeType(),
            'extension'         => $extension ?: null,
            'size'              => $file->getSize(),
        ]);
    }
}
