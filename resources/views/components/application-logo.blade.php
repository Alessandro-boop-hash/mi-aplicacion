<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="marteGrad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#f97316"/>
            <stop offset="100%" stop-color="#ea580c"/>
        </linearGradient>
        <linearGradient id="marsRing" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#ffffff" />
            <stop offset="100%" stop-color="#ffedd5" />
        </linearGradient>
    </defs>
    <!-- Background stylized Mars circle -->
    <circle cx="32" cy="32" r="22" fill="url(#marteGrad)" />
    <!-- Dynamic sportswear overlay: An abstract M resembling energy and velocity -->
    <path d="M20 40L28 22L32 29L36 22L44 40" stroke="white" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" />
    <!-- Orbital speed line suggesting movement -->
    <path d="M12 36C18 43 46 43 52 36" stroke="url(#marsRing)" stroke-width="2.5" stroke-linecap="round" opacity="0.85" />
</svg>
