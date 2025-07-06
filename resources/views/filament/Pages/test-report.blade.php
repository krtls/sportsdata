<x-filament::page>
    {{ $this->table }}

    <div class="mt-4 text-right text-lg font-semibold">
        En iyi servis ortalaması: {{ number_format($this->getAverageOfHighestScores(), 2) }}
    </div>
</x-filament::page>
