@if(!empty($own_posts))

These are the brand's own recent posts. This is how the brand actually sounds, so match this voice, tone, dialect, sentence length, and formatting closely when you write about the user's new topic. Do NOT copy them:

@foreach($own_posts as $__i => $__post)
Post {{ $__i + 1 }}:
{{ $__post }}

@endforeach
@endif
