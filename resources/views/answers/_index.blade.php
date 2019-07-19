<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h2>{{ $answersCount ." ". str_plural("Answer", $answersCount) }}</h2>
                </div>
                <hr>
                @include('layouts._messages')

                @foreach($answers as $answer)
                <div class="media">

                    <div class="d-flex flex-column vote-controls">
                        <a href="" title="This answer  is useful" class="vote-up">
                            <i class="fas fa-caret-up fa-3x"></i>
                        </a>
                        <span class="votes-count">1234</span>
                        <a href="" title="This answer  is not useful" class="vote-down off">
                            <i class="fas fa-caret-down fa-3x"></i>
                        </a>

                        @can('accept', $answer)
                            <a href="" title="Mark this answer as best answer" 
                                class="{{ $answer->status}} mt-2"
                                onclick="event.preventDefault(); document.getElementById('accept-answer-{{ $answer->id }}').submit()"
                                >
                                <i class="fas fa-check  fa-3x"></i>
                            </a>
                            <form action="{{ route('answers.accept', $answer->id) }}" id="accept-answer-{{ $answer->id }}" method="POST" style="display:none;">
                                @csrf
                            </form>
                        @else
                            @if($answer->is_best)
                                <a href="" title="The question owner accepted this answer as best answer" 
                                    class="{{ $answer->status}} mt-2">
                                    <i class="fas fa-check  fa-3x"></i>
                                </a>
                            @endif
                        @endcan
                    </div>

                    <div class="media-body">
                        {!! $answer->body_html !!}
                        <div class="row">
                            <div class="col-4">
                                @if(Auth::user()->can('update', $answer))
                                <a href="{{ route('questions.answers.edit', [$question->id, $answer->id]) }}"
                                    class="btn btn-sm btn-outline-info">Edit</a>
                                @endif
                                @if(Auth::user()->can('delete', $answer))
                                <form class="form-delete" method="POST"
                                    action="{{ route('questions.answers.destroy', [$question->id, $answer->id]) }}">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger" type="submit"
                                        onclick="return confirm('Are u sure?')">Delete</button>
                                </form>
                                @endif
                            </div>
                            <div class="col-4"></div>

                            <div class="col-4 ">
                                <span class="text-muted">Answered {{ $answer->created_date }}</span>
                                <div class="media mt-2">
                                    <a href="{{ $answer->user->url }}" class="pr-2">
                                        <img src="{{ $answer->user->avatar }}" alt="">
                                    </a>
                                    <div class="media-body mt-1">
                                        <a href="{{ $answer->user->url }}">{{ $answer->user->name }}</a>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        
                    </div>
                </div>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>