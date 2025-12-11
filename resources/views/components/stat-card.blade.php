@props(['label'=>'','value'=>0,'hint'=>null])

<div class="rounded-2xl border border-gray-200 bg-white p-4">
  <div class="text-[14px] text-gray-600 font-light">{{ $label }}</div>
  <div class="mt-1 text-2xl font-bold">{{ number_format($value) }}</div>
  @if($hint)
    <div class="mt-2 text-xs text-gray-500">{{ $hint }}</div>
  @endif
</div>
