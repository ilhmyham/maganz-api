<svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#6366F1; stop-opacity:1" />
            <stop offset="100%" style="stop-color:#8B5CF6; stop-opacity:1" />
        </linearGradient>
        <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
            <feDropShadow dx="0" dy="3" stdDeviation="4" flood-color="#000" flood-opacity="0.2" />
        </filter>
        <style>
            .logo-bg {
                fill: url(#grad);
            }

            .logo-text {
                fill: #ffffff;
                font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
                font-size: 60px;
                font-weight: 700;
                filter: url(#shadow);
            }
        </style>
    </defs>

    <rect width="316" height="316" rx="40" class="logo-bg" />
    <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" class="logo-text">Ma.Ganz</text>
</svg>
