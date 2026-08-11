@php($__lang = $language_name ?? 'English')
Write every part of the output in {{ $__lang }}@if(!empty($language_native)) ({{ $language_native }})@endif. This is a strict requirement: the caption, every title, all slide and card text, and any reader-facing copy must be in {{ $__lang }}, even when these instructions or the user's request are written in another language. Only write in a different language if the user's own message explicitly asks you to.
@if($__lang === 'Arabic')
Write in natural Saudi white dialect (اللهجة البيضاء), the way modern Saudi brands write on social, not stiff formal MSA. Keep it clean and professional, and match the brand voice above.
@endif
