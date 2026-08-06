<script>
    window.getThemeColors = function() {
        const style = getComputedStyle(document.documentElement);
        const primary = style.getPropertyValue('--theme-primary').trim() || '#2563eb';
        const secondary = style.getPropertyValue('--theme-secondary').trim() || '#1d4ed8';
        const accent = style.getPropertyValue('--theme-accent').trim() || '#f59e0b';
        return { primary, secondary, accent };
    };

    window.getChartPalette = function(count, isGender = false) {
        const colors = window.getThemeColors();
        if (isGender) {
            return [colors.primary, '#db2777'];
        }
        const base = [
            colors.primary,
            colors.secondary,
            colors.accent,
            '#06b6d4', // cyan
            '#10b981', // emerald
            '#fbbf24', // amber
            '#ec4899', // pink
            '#8b5cf6', // violet
            '#f97316'  // orange
        ];
        const result = [];
        for (let i = 0; i < count; i++) {
            result.push(base[i % base.length]);
        }
        return result;
    };
</script>

<style>
    /* Prevent table headers from clipping by overriding overly rounded wrappers */
    .rounded-\[2\.5rem\], .rounded-\[2rem\] {
        border-radius: 1rem !important;
    }
</style>

@foreach($categories as $cat)
    <div x-show="activeTab === '{{ $cat->slug }}'" x-cloak class="transition-opacity duration-300">
        @php $viewPath = "frontend.desa.tabs." . $cat->slug; @endphp
        @if(view()->exists($viewPath))
            @include($viewPath, ['cat' => $cat, 'desa' => $desa, 'tahun' => $tahun])
        @else
            <div class="rounded-3xl bg-white border border-slate-100 p-12 text-center shadow-sm">
                <p class="text-sm font-semibold text-slate-400">Statistik belum tersedia untuk tab ini.</p>
            </div>
        @endif
    </div>
@endforeach
