@extends('layouts.app')

@section('content')
<div class="col-md-8 col-md-offset-2">

   <div class="panel panel-default" style="margin-bottom:5px;">
      <div class="panel-heading">
         {{$board->title}}
		 &nbsp;&nbsp;<i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;{{$board->viewer}}
         <span style="float:right;">
		@if('dcom' != $board->userid)
			@if($board->anonymous == 1)
				<span id="numberbox">??</span>익명
			@elseif($board->anonymous == 0)
				<?php $user=App\User::where('userid', $board->userid)->first()?>
				<span id="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
			@endif
		@endif
		</span>
      </div>
      <div class="panel-body">
         <div class="form-group">
            <div style="float:right; height:30px;">
			    <a href="/{{$board->boardid}}"><i class="fa fa-list-alt" aria-hidden="true"></i></i>목록</a>
				@if(Auth::user()->userid == $board->userid)
                  &nbsp;&nbsp;<a href="/{{$board->boardid}}/{{$board->id}}/modify"><i class="fa fa-pencil" aria-hidden="true"></i>수정</a>
                  &nbsp;&nbsp;<a href="#" onclick="return remove2('{{$board->boardid}}','{{$board->id}}')"><i class="fa fa-scissors" aria-hidden="true"></i>삭제</a>
				@endif
			</div>
            
			<div class="clear"></div>
            <div class="col-md-12">
				<div id="htmlviewer">
					{!! $board->content !!}
				</div>
            </div>

         </div>
        

            <div class="panel panel-default" style="margin-bottom:5px;">
               <div class="panel-body">
                  <form class="form-horizontal" id="postForm" role="form" method="POST" action="/{{$board->id}}/comment/create" enctype="multipart/form-data" onsubmit="return postForm('main')">
                     {{ csrf_field() }}
                     <input type="hidden" name="anonymous" value="0">
                     <fieldset>
                        <div class="main" id="standard">
                           <textarea id="summernote" name="content"></textarea>
                        </div>
                     </fieldset>
                     
                     <div class="dropzone" id="dropzone"></div>
                     
                     <script>
                        // main Dropzone
                        Dropzone.autoDiscover = false;
                        var myDropzone = new Dropzone("div#dropzone", {
                           url: '/uploadfile',
                           params: {
                              _token: "{{ Session::getToken() }}",
                           },
                           success: function(file, response) {
                              var link = '<a href="http://dcomstudy.com/download/'+response+'">'+file.name+'</a></br>';
                              $('.main textarea[name="content"]').summernote('pasteHTML', link);
                           }
                        });
                     </script>
					 <div class="row">
						 <div class="col-xs-6">
							 <span id="toggle">
								<input type="checkbox" checked name="toggle" id="anonymous" data-onstyle="success" data-offstyle="danger">
								<input type="hidden" name="anonymous" value="0">
								
								<script>
									$(function() {
										$('#anonymous').bootstrapToggle({
											on: '실명',
											off: '익명'
										})

										$( "#anonymous" ).change(function() {
											if($('#anonymous').is(":checked")) {
												$('input[name=anonymous]').val('0');
											}else{
												$('input[name=anonymous]').val('1');
											}
										});
									})
								</script>
							</span>
						 
						 </div>
						 <div class="col-xs-6 text-right">
					 		<button class="btn btn-success" class="submit">작성</button>
						 </div>
					</div>
                  </form>
               </div>
            </div>
        
         
         @foreach ($comments->reverse() as $comment)
            @if($comment->frontcomment == 0)
               <div class="panel panel-success" style="margin-top:20px; margin-bottom:0px;">
                  <div class="panel-heading">
					@if(!preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/",  $comment->userid))
						@if($comment->anonymous == 1)
							<span id="numberbox">??</span>익명
						@elseif($comment->anonymous == 0)
							<?php $user=App\User::where('userid', $comment->userid)->first()?>
							<span id="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
						@endif
                     <span style="float:right;">
                        작성 : {{$comment->created_at}}
                     </span>
					@else
						{{$comment->userid}}
					@endif
                  </div>
                  <div class="panel-body">
                     <div style="float:right; height:30px;">
						@if(Auth::user()->userid == $comment->userid)
						  &nbsp;
						  <a href="#" onclick="modify2({{$comment->id}}, {{$comment->anonymous}});return false"><i class="fa fa-pencil" aria-hidden="true"></i>수정</a>
						  &nbsp;
						  <a href="#" onclick="remove({{$comment->id}});return false"><i class="fa fa-scissors" aria-hidden="true"></i>삭제</a>
						@endif
                     </div>
                     
                     <form class="form-horizontal"id="postForm" role="form" method="POST" action="/comment/{{$comment->id}}/modify" enctype="multipart/form-data"  onsubmit="return postForm('comment{{$comment->id}}')">
                        {{ csrf_field() }}
                        <input type="hidden" name="anonymous" value="0">
                        <div id="htmlviewer" class="comment{{$comment->id}} modify">
                           {!! $comment->content !!}
                        </div>
                     </form>
                  </div>
               </div>
               
               <form class="form-horizontal"id="postForm" role="form" method="POST" action="/{{$board->id}}/comment/{{$comment->id}}/reply" enctype="multipart/form-data" onsubmit="return postForm('reply{{$comment->id}}')">
                  {{ csrf_field() }}
                  <input type="hidden" name="anonymous" value="0">
                  <div class="reply{{$comment->id}} reply"></div>
               </form>
               
               <?php $replys = App\Comment::where('frontcomment', $comment->id)->get()?>
               @foreach ($replys->reverse() as $reply)
                  <div class="panel panel-default" style="margin-left:50px; margin-bottom: 0px; margin-top:3px;">
                     <div class="panel-heading">
						@if($reply->anonymous == 1)
							<span id="numberbox">??</span>익명
						@elseif($reply->anonymous == 0)
							<?php $user=App\User::where('userid', $reply->userid)->first()?>
							<span id="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
						@endif
                     <span style="float:right;">
                        작성 : {{$reply->created_at}}
                     </span>
                     </div>
                     <div class="panel-body">
                        <div style="float:right; height:30px;">
                           @if(Auth::user()->userid == $reply->userid)
                              &nbsp;
                              <a href="#" onclick="modify2({{$reply->id}}, {{$reply->anonymous}});return false"><i class="fa fa-pencil" aria-hidden="true"></i>수정</a>
                              &nbsp;
                              <a href="#" onclick="remove({{$reply->id}});return false"><i class="fa fa-scissors" aria-hidden="true"></i>삭제</a>
                           @endif
                        </div>
                        
                        <form class="form-horizontal"id="postForm" role="form" method="POST" action="/comment/{{$reply->id}}/modify" enctype="multipart/form-data"  onsubmit="return postForm('comment{{$reply->id}}')">
                           {{ csrf_field() }}
                           <input type="hidden" name="anonymous" value="0">
                           <div id="htmlviewer" class="comment{{$reply->id}} modify">
                              {!! $reply->content !!}
                           </div>
                        </form>
                     </div>
                  </div>
               @endforeach
            @endif
         @endforeach
      </div>
   </div>
</div>
@endsection
