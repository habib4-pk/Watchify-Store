{{-- 
    Stock Badge Partial
    Displays stock status indicator
    @param $stock - The stock quantity
    @param $position - Optional: 'absolute' (default) or 'inline' 
--}}

@php
    $position = $position ?? 'absolute';
    $badgeClass = $position === 'inline' ? 'stock-badge-inline' : 'stock-badge';
@endphp

@if($stock > 0 && $stock <= 5)
    <span class="{{ $badgeClass }} low-stock">Only {{ $stock }} left</span>
@elseif($stock > 0)
    <span class="{{ $badgeClass }} in-stock">In Stock</span>
@else
    <span class="{{ $badgeClass }} out-of-stock">Out of Stock</span>
@endif
