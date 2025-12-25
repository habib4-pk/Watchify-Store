<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Watchify</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    
    {{-- Global Alert Styles --}}
    <style>
        .global-alert-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            width: 100%;
        }

        .global-alert {
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            animation: slideInRight 0.4s ease-out;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        .global-alert.fade-out {
            animation: fadeOut 0.4s ease-out forwards;
        }

        .global-alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-left: 4px solid #1e7e34;
        }

        .global-alert-error {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border-left: 4px solid #bd2130;
        }

        .global-alert-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #212529;
            border-left: 4px solid #e0a800;
        }

        .global-alert-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border-left: 4px solid #117a8b;
        }

        .global-alert-content {
            flex: 1;
            margin-right: 10px;
        }

        .global-alert-content strong {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .global-alert-content ul {
            margin: 8px 0 0 20px;
            padding: 0;
        }

        .global-alert-content li {
            margin-bottom: 4px;
        }

        .global-alert-close {
            background: transparent;
            border: none;
            color: inherit;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.8;
            transition: opacity 0.2s;
            line-height: 1;
        }

        .global-alert-close:hover {
            opacity: 1;
        }

        .global-alert-icon {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .global-alert-container {
                top: 70px;
                right: 10px;
                left: 10px;
                max-width: none;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>

    @include('buyer.layouts.navbar')

    {{-- Global Alert Container --}}
    <div class="global-alert-container" id="globalAlertContainer">
        
        {{-- Success Messages --}}
        @if(session('success'))
            <div class="global-alert global-alert-success" id="success-alert">
                <div class="global-alert-content">
                    <span class="global-alert-icon">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('success-alert')">&times;</button>
            </div>
        @endif

        {{-- Error Messages --}}
        @if(session('error'))
            <div class="global-alert global-alert-error" id="error-alert">
                <div class="global-alert-content">
                    <span class="global-alert-icon">✕</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('error-alert')">&times;</button>
            </div>
        @endif

        {{-- Warning Messages --}}
        @if(session('warning'))
            <div class="global-alert global-alert-warning" id="warning-alert">
                <div class="global-alert-content">
                    <span class="global-alert-icon">⚠</span>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('warning-alert')">&times;</button>
            </div>
        @endif

        {{-- Info Messages --}}
        @if(session('info'))
            <div class="global-alert global-alert-info" id="info-alert">
                <div class="global-alert-content">
                    <span class="global-alert-icon">ℹ</span>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('info-alert')">&times;</button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="global-alert global-alert-error" id="validation-alert">
                <div class="global-alert-content">
                    <strong>⚠ Please fix the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('validation-alert')">&times;</button>
            </div>
        @endif

    </div>

    <main>
        @yield('content')
    </main>

    @include('buyer.layouts.footer')

    {{-- Global Alert JavaScript --}}
    <script>
        // Close alert function
        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.classList.add('fade-out');
                setTimeout(() => {
                    alert.remove();
                }, 400);
            }
        }

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.global-alert');
            
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && document.body.contains(alert)) {
                        alert.classList.add('fade-out');
                        setTimeout(() => {
                            if (alert && document.body.contains(alert)) {
                                alert.remove();
                            }
                        }, 400);
                    }
                }, 5000);
            });
        });

        // Prevent form double submission globally
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton && !submitButton.disabled) {
                        submitButton.disabled = true;
                        const originalText = submitButton.textContent;
                        submitButton.textContent = 'Processing...';
                        
                        // Re-enable after 3 seconds in case of validation errors
                        setTimeout(() => {
                            submitButton.disabled = false;
                            submitButton.textContent = originalText;
                        }, 3000);
                    }
                });
            });
        });
    </script>

    @yield('scripts')

</body>
</html>