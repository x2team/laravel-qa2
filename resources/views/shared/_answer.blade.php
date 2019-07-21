<div class="media post">

    @include('shared._vote', [
        'model' => $answer 
    ])

    <div class="media-body">
        {!! $answer->body_html !!}
        <div class="row">
            <div class="col-4">
                @if(Auth::user() && Auth::user()->can('update', $answer))
                <a href="{{ route('questions.answers.edit', [$question->id, $answer->id]) }}"
                    class="btn btn-sm btn-outline-info">Edit</a>
                @endif
                @if(Auth::user() && Auth::user()->can('delete', $answer))
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
                {{-- @include('shared._author', [
                    'model' => $answer,
                    'label' => 'answered'
                ]) --}}
                <user-info v-bind:model="{{ $answer }}" label="Answered"></user-info>
            </div>
        </div>

    </div>
</div>