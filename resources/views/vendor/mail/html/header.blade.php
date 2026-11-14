@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block; color: #19539d;">
            {!! $slot !!}
        </a>
    </td>
</tr>