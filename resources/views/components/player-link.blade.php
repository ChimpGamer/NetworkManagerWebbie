@props(['uuid', 'username' => null])

<img alt="player head" draggable="false" src="https://minotar.net/avatar/{{$uuid}}/20"> @if($username == null)
    {{ $uuid }}
@else
    <a wire:navigate href="{{route('players.show', $uuid)}}">{{ $username }}</a>
@endif
