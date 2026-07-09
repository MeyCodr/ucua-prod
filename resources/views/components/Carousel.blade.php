<div class="fotorama" data-nav="thumbs" data-width="100%" data-allowfullscreen="true"
    data-arrows="true">
    @forelse ($attachments as $item)
        @if ($item->level == 1)
            <img src="{{ asset('storage/ticket/ticket_' . $item->ticket_id . '/1/' . $item->file_rand_name) }}"
                alt="ok" class="picture">
        @elseif ($item->level == 2)
            <img src="{{ asset('storage/ticket/ticket_' . $item->ticket_id . '/2/' . $item->file_rand_name) }}"
                alt="ok" class="picture">
        @else
            <img src="{{ asset('storage/ticket/ticket_' . $item->ticket_id . '/3/' . $item->file_rand_name) }}"
                alt="ok" class="picture">
        @endif
    @empty
        {{-- <div>No attachments</div> --}}
    @endforelse
</div>
