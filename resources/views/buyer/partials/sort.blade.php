<form action="{{ url()->current() }}" method="GET" style="display: inline-block; margin-right: 20px;">
    @if(isset($query))
        <input type="hidden" name="query" value="{{ $query }}">
    @endif

    <select name="sort" onchange="this.form.submit()" style="background: none; border: none; border-bottom: 1px solid #000; padding: 5px 0; font-size: 11px; font-family: 'Inter', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; cursor: pointer; outline: none;">
        <option value="">Sort By</option>
<option value="price_asc" {{ ($sort == 'price_asc') ? 'selected' : '' }}>Price: Low-High</option>
<option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : '' }}>Price: High-Low</option>
<option value="name_az" {{ ($sort == 'name_az') ? 'selected' : '' }}>Name: A-Z</option>
<option value="newest" {{ ($sort == 'newest') ? 'selected' : '' }}>Newest</option>

    </select>
</form>