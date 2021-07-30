@extends('layouts.app')

@section('content')
<div class="col-md-8 col-md-offset-2">

   <div class="panel panel-default" style="margin-bottom:5px;">
      <div class="panel-heading">
         <?php $group=App\Group::where('boardid',$board->id)->first()  ?>
         {{$board->title}}
         <span style="float:right;">
            <i class="fa fa-user" aria-hidden="true"></i>
            <span id="numberbox">{{$group->participation}}</span>
         </span>
      </div>
      <div class="panel-body">
         <div class="form-group">
            <div style="float:right;">
				<a href="/group/{{$group->type}}"><i class="fa fa-list-alt" aria-hidden="true"></i></i>목록</a>&nbsp;
				@if(Auth::user()->userid == $board->userid)
                  <a href="/group/{{$group->type}}/{{$group->boardid}}/modify"><i class="fa fa-pencil" aria-hidden="true"></i>수정</a>&nbsp;
                  <a href="#" onclick="removegroup('{{$group->type}}',{{$group->boardid}});return false"><i class="fa fa-scissors" aria-hidden="true"></i>삭제</a>
				@endif
			</div>
			<div class="clear"></div>
            <div class="col-md-12">
               {!! $board->content !!}
            </div>

         </div>
         <div class="form-group">
            <div class="col-md-12">
               <?php $users = App\GroupsUsers::where('groupid', $board->id)->get() ?>
               <span>
                  <i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;
                  @foreach ($users as $user)
                     <?php $id = App\User::where('userid', $user->userid)->first() ?>
                     <span id="numberbox">{{$id->admissionyear}}</span>{{$id->realname}}
                  @endforeach
               </span>
               <div style="margin-top: 10px;">
                  <?php $git = App\Group::where('boardid', $board->id)->first()->git ?>
                  <i class="fa fa-github" aria-hidden="true"></i>&nbsp;&nbsp;<a href="{{$git}}">{{$git}}</a>
               </div>
            </div>
         </div>

         @if(App\GroupsUsers::where('userid', Auth::user()->userid)->where('groupid', $board->id)->first())
            <div class="panel panel-default" style="margin-bottom:5px;">
               <div class="panel-heading">
                  기록 추가
               </div>
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

                     <div class="col-md-12">
                        <button type="submit" class="btn btn-success" style="float:right;">
                           기록 추가
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         @else
            <form class="form-horizontal" role="form" method="POST" action="/{{$board->id}}/participation">
               {{ csrf_field() }}
               <div class="form-group">
                  <div class="col-md-12">
                     <button type="submit" class="btn btn-success">
                        참여하기
                     </button>
                  </div>
               </div>
            </form>
         @endif
         
         @foreach ($comments->reverse() as $comment)
            @if($comment->frontcomment == 0)
               <div class="panel panel-success" style="margin-top:20px; margin-bottom:0px;">
                  <div class="panel-heading">
                     <?php $user = App\User::where('userid', $comment->userid)->first() ?>
                     <span id="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
                     <span style="float:right;">
                        작성 : {{$comment->created_at}}
                     </span>
                  </div>
                  <div class="panel-body">
                     <div style="float:right; height:30px;">
                        @if(App\GroupsUsers::where('userid', Auth::user()->userid)->where('groupid', $board->id)->first())
                           <a href="#" onclick="reply({{$comment->id}});return false"><i class="fa fa-reply" aria-hidden="true" id="rotate"></i>답글</a>
                           @if(Auth::user()->userid == $comment->userid)
                              &nbsp;
                              <a href="#" onclick="modify({{$comment->id}});return false"><i class="fa fa-pencil" aria-hidden="true"></i>수정</a>
                              &nbsp;
                              <a href="#" onclick="remove({{$comment->id}});return false"><i class="fa fa-scissors" aria-hidden="true"></i>삭제</a>
                           @endif
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
                  <?php $user = App\User::where('userid', $reply->userid)->first() ?>
                  <div class="panel panel-default" style="margin-left:50px; margin-bottom: 0px; margin-top:3px;">
                     <div class="panel-heading">
                     <?php $user = App\User::where('userid', $reply->userid)->first() ?>
                     <span id="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
                     <span style="float:right;">
                        작성 : {{$reply->created_at}}
                     </span>
                     </div>
                     <div class="panel-body">
                        <div style="float:right; height:30px;">
                           @if(Auth::user()->userid == $reply->userid)
                              &nbsp;
                              <a href="#" onclick="modify({{$reply->id}});return false"><i class="fa fa-pencil" aria-hidden="true"></i>수정</a>
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
