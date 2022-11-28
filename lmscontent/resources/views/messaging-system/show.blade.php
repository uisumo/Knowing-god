@extends($layout)
@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
                            <li class="breadcrumb-item"><a href="{{URL_MESSAGES}}">{{getPhrase('Messages')}}</a> </li>
                            <li class="breadcrumb-item active"> {{ $title }} </li>
                        </ol>
                    </div>
                </div>

                 <div class="row mt-2">
                   <div class="col-sm-12">

                    <div class="heading-right">
                        <div class="pull-right messages-buttons">
                               <a class="btn btn-primary button" href="{{URL_MESSAGES}}"> {{getPhrase('inbox')}} </a>
							<a class="btn btn-primary button" href="{{URL_MESSAGES}}/unread"> {{getPhrase('unread').' ('.$count = Auth::user()->newThreadsCount().')'}} </a>
                            <a class="btn btn-primary button" href="{{URL_MESSAGES_CREATE}}">
                            {{getPhrase('compose')}}</a>
                           </div>
                        
                    </div>
                   </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12 expand-card card-normal">
                        <!-- Chat Card -->
                        <div class="card">
                            <!-- <div class="card-header">{{$title}}             </div> -->
                            <div class="card-body">
                                <div class="ag-chat-list" id="historybox">
                                    <?php $current_user = Auth::user()->id; ?>
                                    @foreach($thread->messages as $message)
                                    <?php $class='d-flex flex-row-reverse';
                                    if($message->user_id == $current_user)
                                    {
                                        $class = 'd-flex flex-row';
                                    }

                                    ?>
                                    <div class="{{$class}}">
                                        <div class="media chat-media">
                                            @if($message->user_id == $current_user)
                                            <img class="d-flex mr-3 icn-size" src="{{getProfilePath($message->user->image)}}" alt="img">
                                            @endif
                                            <div class="media-body">
                                                <h5>
                                                <?php
                                                if ( $message->user ) {
                                                    echo $message->user->name;
                                                } else {
                                                    echo getPhrase('user_deleted');
                                                }
                                                ?>
                                                <span class="d-flex float-md-right">{!! $message->created_at->diffForHumans() !!}</span></h5>
                                                <p>{!! $message->body !!}</p>
                                            </div>
                                            @if($message->user_id != $current_user && $message->user )
                                            <img class="d-flex ml-3 icn-size" src="{{getProfilePath($message->user->image)}}" alt="img">
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @if ( $message->user )
                            <div class="ag-chat-typing">
                                {!! Form::open(['route' => ['messages.update', $thread->id], 'method' => 'PUT']) !!}
                                <div class="input-group">

                                    <input type="text" class="form-control" placeholder="{{getPhrase('Reply')}}" aria-label="Recipient's username" aria-describedby="basic-addon2" name="message">

                                    <button class="btn btn-secondary btn-compose btn-send-msg" value="Submit" type="submit">
                                    <span class="input-group-addon" id="basic-addon2"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> &nbsp; {{getPhrase('Send')}}</span>
                                    </button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            @endif
                        </div>
                        <!-- /Chat Card -->

                    </div>
                </div>
</div>
</div>

@stop

@section('footer_scripts')
<script>
 $('#historybox').scrollTop($('#historybox')[0].scrollHeight);
</script>
@stop
