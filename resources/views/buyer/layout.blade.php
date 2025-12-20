<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchify | Premium Timepieces</title>
    <style>
        /* Modern Reset & Typography */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', -apple-system, sans-serif; 
            background-color: #fff; 
            color: #1a1a1a;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Smooth Scrolling */
        html { scroll-behavior: smooth; }

        /* Main Content Container */
        main { min-height: 80vh; }
        .container { 
            max-width: 1300px; 
            margin: 0 auto; 
            padding: 0 40px; 
        }

        /* Standardized Premium Buttons */
        .btn-luxury {
            display: inline-block;
            padding: 14px 32px;
            background: #1a1a1a;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid #1a1a1a;
        }

        .btn-luxury:hover {
            background: transparent;
            color: #1a1a1a;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container { padding: 0 20px; }
        }
    </style>
    @yield('styles')
</head>
<body>

    @include('buyer.layouts.navbar')

    <main>
        @yield('content')
    </main>

    @include('buyer.layouts.footer')

</body>
</html>