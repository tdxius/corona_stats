<?php

namespace App\View\Components;

use App\Models\Poll;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;

class PollResults extends Component
{
    public $polls;

    public function __construct(Poll $excludedPoll = null) {
        $this->polls = $this->getPolls($excludedPoll);
    }

    public function render() {
        return view('components.poll-results');
    }

    private function getPolls(Poll $excludedPoll = null) {
        return Poll::query()
            ->when($excludedPoll, function(Builder $query, Poll $poll) {
                $query->where('id', '!=', $poll->id);
            })
            ->has('results', '>=', 20)
            ->with('results')
            ->take(2)
            ->inRandomOrder()
            ->get()
            ->map(function (Poll $poll) {
                $resultsCountedByOptions = $poll->results->pluck('value')->countBy();
                $resultsCount = $poll->results->count();
                $poll->options_extended = collect($poll->options)
                    ->map(function ($option) use ($resultsCountedByOptions, $resultsCount) {
                        $optionCount = $resultsCountedByOptions[$option] ?? 0;
                        return (object)[
                            'value'      => $option,
                            'percentage' => round(($optionCount / $resultsCount) * 100, 1)
                        ];
                    })
                    ->sortBy('percentage')
                    ->reverse();

                return $poll;
            });
    }
}
