<?php
    /**
     * @var object $status
    */
?>


@extends('admin_layout')

@section('title') Issues @endsection

@section('head_extra')
    <script>
        function setStatusAndRedirect(status) {
            var url = new URL(document.location.href);
            url.searchParams.set('status', status);
            document.location.href = url.toString();
        }
    </script>
@endsection

@section('content')
    <div>
        <div class="d-flex" style="padding-top:10px;padding-bottom:10px;">
            @if ($pointer)
                <h2>Relevant Issues</h2>
            @else
                <h2>All Issues</h2>
            @endif
            <div class="btn-group" style="padding-left:10px;">
                <button type="button"
                        @class(['btn', 'btn-sm', 'btn-secondary'=>('open'==$status), 'btn-outline-secondary'=>('open'!=$status)])
                        onclick="setStatusAndRedirect('open')"
                >Open</button>

                <button type="button"
                        @class(['btn', 'btn-sm', 'btn-secondary'=>('closed'==$status), 'btn-outline-secondary'=>('closed'!=$status)])
                        onclick="setStatusAndRedirect('closed')"
                >Closed</button>

                <button type="button"
                        @class(['btn', 'btn-sm', 'btn-secondary'=>('all'==$status), 'btn-outline-secondary'=>('all'!=$status)])
                        onclick="setStatusAndRedirect('all')"
                >All</button>
            </div>
        </div>
        <div class="issue_list">
        @forelse ($issues as $issue)
            <div class="issue">
                <div style="cursor:pointer;" class="card"
                    onmouseover="this.classList.add('border-primary')"
                    onmouseout="this.classList.remove('border-primary')"
                    onclick="document.location.href='/admin2/issues/{{$issue->id}}'"
                >
                    <div class="card-header">
                        <h5>
                    <span
                        @class(['badge', 'bg-success'=>($issue->status==='open'), 'bg-danger'=>($issue->status!=='open')])
                    >{{$issue->status}}</span>
                            #{{$issue->id}}:
                            {{$issue->name}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div style="font-weight:bold;">In: {{$issue->pointer_desc}}</div>
                        @php($last_comment = $issue->comments->last())
                        <div>{{$issue->comments->count()}} comments, last on {{explode(' ',$last_comment->created_at)[0]}} by {{$last_comment->user_logon}}</div>
                    </div>
                </div>
                <br>
            </div>
        @empty
            <div>No issues matching current search criteria.</div>
        @endforelse
        </div>
    </div>
@endsection
