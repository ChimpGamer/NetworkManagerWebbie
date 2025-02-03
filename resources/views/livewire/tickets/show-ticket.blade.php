<div>
    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="d-flex">
        <div x-data="{ open: false }" class="me-auto">
            <button @click="open = ! open" @keydown.escape="open = false" class="btn btn-outline-primary"
                    data-mdb-dropdown-initialized="true" aria-expanded="true">
                <i class="fa-solid fa-user-tie"></i> {{$ticket->assigned_to ?? __('ticket.unassigned')}}
            </button>
            <div x-cloak x-show="open" @click.away="open = false">
                <ul class="dropdown-menu">
                    @foreach($assignOptions as $username)
                        <li wire:key="{{$username}}">
                            <button @click="open = false" class="dropdown-item"
                                    wire:click="setAssigned('{{$username}}')">
                                {{ $username }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div x-data="{ open: false }">
            <button @click="open = ! open" @keydown.escape="open = false"
                    class="btn {{$ticket->priority->buttonStyleClass()}} btn-floating"
                    data-mdb-dropdown-initialized="true" aria-expanded="true">
                <i class="fa-solid fa-circle-exclamation"></i>
            </button>
            <div x-cloak x-show="open" @click.away="open = false">
                <ul class="dropdown-menu">
                    @foreach($this->ticketPriorityCases as $ticketPriority)
                        <li wire:key="{{$ticketPriority->value}}">
                            <button @click="open = false" class="dropdown-item"
                                    wire:click="setPriority({{$ticketPriority->value}})">
                                <i class="{{$ticketPriority->iconColorClass()}} fa-solid fa-circle-exclamation"></i>
                                {{ $ticketPriority->name() }}
                                @if($ticket->priority == $ticketPriority)
                                    <i class="fa-solid fa-check float-end"></i>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{--<div class="dropdown">
            <button wire:ignore.self type="button" class="btn {{$ticket->priority->buttonStyleClass()}} btn-floating"
                    id="priorityDropDownMenuButton" data-mdb-dropdown-init aria-expanded="false">
                <i class="fa-solid fa-circle-exclamation"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="priorityDropDownMenuButton">
                @foreach($this->ticketPriorityCases as $ticketPriority)
                    <li wire:key="{{$ticketPriority->value}}">
                        <button class="dropdown-item" wire:click="setPriority({{$ticketPriority->value}})"><i
                                class="{{$ticketPriority->iconColorClass()}} fa-solid fa-circle-exclamation"></i> {{$ticketPriority->name()}}
                            @if($ticket->priority == $ticketPriority)
                                <i class="fa-solid fa-check float-end"></i>
                            @endif
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>--}}
    </div>
    <hr class="hr">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <h2>{{$ticket->title}}</h2>
            </div>
            <div class="col-md-6">
                <h2 class="float-end">#{{$ticket->id}}</h2>
            </div>
        </div>
    </div>
    <hr class="hr">
    <div wire:ignore>
        <textarea id="editor" wire:model="message"></textarea>
    </div>

    <br/>
    <div class="d-flex">
        <div class="me-auto">
            <button type="submit" class="btn btn-primary" wire:click="sendMessage" onclick="tinyMCE.activeEditor.setContent('');">@lang('ticket.buttons.send-message')</button>
        </div>
        <div>
            <button type="submit" class="btn btn-danger" wire:click="closeTicket">@lang('ticket.buttons.close-ticket')</button>
        </div>
    </div>
    <hr class="hr">
    <div class="col-12">
        @foreach($ticket->ticketMessages->sortByDesc('id') as $ticketMessage)
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><b>{{$ticketMessage->getSenderName()}}</b> replied</h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="float-end">{{$ticketMessage->getTimeFormatted()}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $ticketMessage->message !!}
                </div>
            </div>
            <hr class="hr">
        @endforeach
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5><b>{{$ticket->getCreatorName()}}</b> asked</h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="float-end">{{$ticket->getCreationFormatted()}}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! $ticket->message !!}
            </div>
        </div>
    </div>
</div>

@script
<script>
    tinymce.init({
        selector: 'textarea#editor',
        skin: 'oxide-dark',
        content_css: 'dark',
        plugins: 'advlist link image lists autosave',
        autosave_restore_when_empty: true,
        promotion: false,
        branding: false,
        license_key: 'gpl',
        setup: (editor) => {
            editor.on('init', () => {
                editor.getContainer().style.transition = 'border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out';
                editor.save();
            });
            editor.on('focus', () => {
                editor.getContainer().style.boxShadow = '0 0 0 .2rem rgba(0, 123, 255, .25)';
                editor.getContainer().style.borderColor = '#424242';
            });
            editor.on('blur', () => {
                editor.getContainer().style.boxShadow = '';
                editor.getContainer().style.borderColor = '';
            });
            editor.on('change', () => {
                editor.save();
                $wire.$set('message', editor.getContent());
            });
        }
    });
</script>
@endscript
