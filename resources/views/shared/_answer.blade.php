<answer v-bind:answer="{{ $answer }}" inline-template>
    <div class="media post">

        {{-- @include('shared._vote', [
        'model' => $answer
        ]) --}}
        <vote :model="{{ $answer }}" name="answer"></vote>

        <div class="media-body">
            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">
                    <textarea v-model="body" rows="10" class="form-control" required></textarea>
                </div>
                <button class="btn btn-primary" :disabled="isInvalid">Update</button>
                <button class="btn btn-secondary" v-on:click.prevent="cancel">Cancel </button>
            </form>
            <div v-else>
                {{-- {!! $answer->body_html !!} --}}
                <div v-html="bodyHtml"></div>
                <div class="row">
                    <div class="col-4">
                        <div class="ml-auto">
                            @if(Auth::user() && Auth::user()->can('update', $answer))
                                <a v-on:click.prevent="edit" class="btn btn-sm btn-outline-info">Edit</a>
                            @endif
                            @if(Auth::user() && Auth::user()->can('delete', $answer))
                                <button @click="destroy" class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                {{-- <form class="form-delete" method="POST"
                                    action="{{ route('questions.answers.destroy', [$question->id, $answer->id]) }}">
                                    @method('DELETE')
                                    @csrf
                                    
                                </form> --}}
                            @endif
                        </div>
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
    </div>
</answer>