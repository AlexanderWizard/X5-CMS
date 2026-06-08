<x-filament-panels::page>
    <style>
        .nf-wrap {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            text-align: center;
            overflow: hidden;
            padding: 2rem 1rem;
        }

        .nf-code {
            position: relative;
            z-index: 1;
            font-size: clamp(7rem, 22vw, 13rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.05em;
            background: linear-gradient(135deg, #fdba74 0%, #ea580c 50%, #9a3412 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 20px 50px rgba(234, 88, 12, 0.15);
            animation: nf-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        .nf-title {
            position: relative;
            z-index: 1;
            margin-top: 0.5rem;
            font-size: 1.6rem;
            font-weight: 700;
            color: #1f2937;
            animation: nf-rise 0.6s ease 0.15s both;
        }

        .nf-text {
            position: relative;
            z-index: 1;
            margin-top: 0.6rem;
            max-width: 30rem;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #6b7280;
            animation: nf-rise 0.6s ease 0.25s both;
        }

        .nf-btn {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            background: linear-gradient(135deg, #f97316, #ea580c);
            box-shadow: 0 10px 25px -5px rgba(234, 88, 12, 0.45);
            transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
            animation: nf-rise 0.6s ease 0.35s both;
        }
        .nf-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px -6px rgba(234, 88, 12, 0.55);
            filter: brightness(1.05);
        }
        .nf-btn:active { transform: translateY(0); }
        .nf-btn svg { width: 1.05rem; height: 1.05rem; }

        @keyframes nf-pop {
            from { opacity: 0; transform: scale(0.8); }
            to   { opacity: 1; transform: scale(1); }
        }
        @keyframes nf-rise {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .dark .nf-title { color: #f3f4f6; }
        .dark .nf-text  { color: #9ca3af; }
    </style>

    <div class="nf-wrap">
        <div class="nf-code">404</div>
        <h1 class="nf-title">{{ __('admin.notfound.title') }}</h1>
        <p class="nf-text">{{ __('admin.notfound.text') }}</p>

        <a href="{{ filament()->getHomeUrl() }}" class="nf-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            {{ __('admin.notfound.home') }}
        </a>
    </div>
</x-filament-panels::page>
