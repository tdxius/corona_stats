<div class="card card-widget">
    <div class="card-header">
        <div class="user-block">
            <i class="fas fa-question-circle fa-2x"></i>
            <span class="username text-lg">
                <a href="{{ route('questions.show', $question) }}">
                    {{ $question->content }}
                </a>
            </span>
            <span class="description">
                zadnji odgovor
                @if ($question->answers()->count())
                    {{ $question->answers()->latest('created_at')->first()->created_at->diffForHumans() }}
                @else
                    {{ $question->created_at->diffForHumans() }}
                @endif
            </span>
        </div>
        <div class="card-tools mobile-hidden">
            <a href="{{ route('questions.show', $question) }}" class="btn btn-primary">
                <i class="fas fa-reply mr-1"></i>
                Sodeluj
            </a>
        </div>
    </div>
</div>
