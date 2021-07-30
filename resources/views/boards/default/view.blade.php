@extends('layouts.layout')

@section('content')
<script>
   $(function() {
      $(".btn-cancel-modify").click(function() {
         if(confirm("정말 댓글 수정을 취소하시겠습니까?"))
            $(this).parents(".comment").removeClass("modify");
      });
      $(".btn-do-modify").click(function() {
         $(this).parents(".comment").addClass("modify");
      });
   })
</script>
<div class="card-section board">
   <div class="card-header">
      {{$board->title}}
   </div>
   <div class="card-content">
      <div class="content-options">
         <div class="author">
            @if($board->anonymous == 1)
               <span class="numberbox">??</span>익명
            @elseif($board->anonymous == 0)
               <?php $user=App\User::where('userid', $board->userid)->first()?>
               <span class="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
            @endif
         </div>
         <div class="date">
            {{$board->created_at}}
         </div>
      </div>
      <div class="buttons">
         @if(Auth::user()->admin == 1 || Auth::user()->userid == $board->userid)
            <button class="btn btn-danger" onclick="return remove2('{{$board->boardid}}','{{$board->id}}')">
               삭제
            </button>
            <a class="btn btn-primary" href="/{{$board->boardid}}/{{$board->id}}/modify">
               수정
            </a>
         @endif
         <a class="btn btn-success" href="/{{$board->boardid}}">
            목록
         </a>
      </div>
		<div class="board-content">
			{!! $board->content !!}
		</div>
		<form class="write-form" id="postForm" role="form" method="POST" action="/{{$board->id}}/comment/create" enctype="multipart/form-data" nsubmit="return postForm('main')">
			{{ csrf_field() }}
			<div class="main summernote-wrapper">
				<textarea class="summernote" name="content"></textarea>
			</div>
			 
			<div class="dropzone" id="dropzone"></div>

			<div class="options">
				<div class="ananymous">
					<label>
						<input type="checkbox" name="anonymous" value="1">
						익명
					</label>
				</div>
				<div class="buttons">
					<button type="submit" class="btn btn-primary">
						댓글 작성
					</button>
				</div>
			</div>
		</form>
      <div class="comment-wrapper">
         @foreach ($comments->reverse() as $comment)
            @if($comment->frontcomment == 0)
               <div class="comment">
                  <div class="content-options">
                     <div class="author">
                        @if(!preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/",  $comment->userid))
                           @if($comment->anonymous == 1)
                              <span class="numberbox">??</span>익명
                           @elseif($comment->anonymous == 0)
                              <?php $user=App\User::where('userid', $comment->userid)->first()?>
                              <span class="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
                           @endif
                        @else
                           {{$comment->userid}}
                        @endif
                     </div>
                     <div class="date">
                        {{$comment->created_at}}
                     </div>
                  </div>
                  @if(Auth::user()->admin == 1 || Auth::user()->userid == $comment->userid)
                  <div class="buttons">
                     <button class="btn btn-primary btn-do-modify">
                        수정
                     </button>
                     <button class="btn btn-danger" onclick="remove({{$comment->id}});">
                        삭제
                     </button>
                  </div>
                  @endif
                  <div class="board-content comment{{$comment->id}} modify">
                     {!! $comment->content !!}
                  </div>
                  <form class="write-form" id="postForm" role="form" method="POST" action="/comment/{{$comment->id}}/modify" enctype="multipart/form-data" nsubmit="return postForm('main')">
                     {{ csrf_field() }}
                     <div class="comment-{{$comment->id}} summernote-wrapper">
                        <textarea class="summernote" name="content">
                           {!! $comment->content !!}
                        </textarea>
                     </div>
                     
                     <div class="dropzone"></div>

                     <script>
                        // main Dropzone
                        Dropzone.autoDiscover = false;
                        var myDropzone = new Dropzone(".comment-{{$comment->id}} ~ .dropzone", {
                           url: '/uploadfile',
                           params: {
                              _token: "{{ Session::getToken() }}",
                           },
                           success: function(file, response) {
                              var link = '<a href="/download/'+response+'">'+file.name+'</a></br>';
                              $('.comment-{{$comment->id}} textarea[name="content"]').summernote('pasteHTML', link);
                           }
                        });
                     </script>

                     <div class="options">
                        <div class="ananymous">
                           <label>
                              <input type="checkbox" name="anonymous" value="1" {{$comment->anonymous ? "checked" : ""}} >
                              익명
                           </label>
                        </div>
                        <div class="buttons">
                           <button type="button" class="btn btn-danger btn-cancel-modify">
                              취소
                           </button>
                           <button type="submit" class="btn btn-primary">
                              수정
                           </button>
                        </div>
                     </div>
                  </form>
               </div>
            @endif
         @endforeach
      </div>
   </div>
</div>
<script>
   // main Dropzone
   Dropzone.autoDiscover = false;
   var myDropzone = new Dropzone(".main ~ .dropzone", {
      url: '/uploadfile',
      params: {
         _token: "{{ Session::getToken() }}",
      },
      success: function(file, response) {
         var link = '<a href="/download/'+response+'">'+file.name+'</a></br>';
         $('.main textarea[name="content"]').summernote('pasteHTML', link);
      }
   });
</script>
@endsection