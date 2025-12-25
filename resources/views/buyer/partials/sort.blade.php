<style>
    .sort-form {
        display: inline-block;
        margin-right: 20px;
    }
    
    .sort-select {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 35px 10px 12px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        cursor: pointer;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        min-width: 140px;
        color: #333;
    }
    
    .sort-select:focus {
        border-color: #1a1a1a;
        box-shadow: 0 0 0 2px rgba(0,0,0,0.05);
    }
    
    @media (max-width: 576px) {
        .sort-form {
            display: block;
            margin: 0;
            width: 100%;
        }
        
        .sort-select {
            width: 100%;
            padding: 12px 40px 12px 15px;
            font-size: 14px;
            border-radius: 8px;
        }
    }
</style>

<form action="{{ url()->current() }}" method="GET" class="sort-form">
    @if(isset($query))
        <input type="hidden" name="query" value="{{ $query }}">
    @endif

    <select name="sort" onchange="this.form.submit()" class="sort-select">
        <option value="">Sort By</option>
        <option value="price_asc" {{ ($sort == 'price_asc') ? 'selected' : '' }}>Price: Low-High</option>
        <option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : '' }}>Price: High-Low</option>
        <option value="name_az" {{ ($sort == 'name_az') ? 'selected' : '' }}>Name: A-Z</option>
        <option value="newest" {{ ($sort == 'newest') ? 'selected' : '' }}>Newest</option>
    </select>
</form>