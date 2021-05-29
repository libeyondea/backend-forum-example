<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://de4thzone.com/_next/image?url=http%3A%2F%2Fbackend-forum-example.herokuapp.com%2Fimages%2F6666666666.jpg&w=48&q=75" class="logo" alt="De4th Zone Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
