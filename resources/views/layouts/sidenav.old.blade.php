@section('sidenav')

        <div class="overlay"></div>
    
        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
				<a href="/">
					<li style="height:70px;">
						<div id="logo" style="margin-left:20px !important;">
							
						</div>
					</li>
				</a>
				@if (Auth::guest())
					<li><a href="/login">로그인</a></li>
					<li><a href="/register">회원가입</a></li>
					<li><a href="/introduce">동아리 소개</a></li>
				@else
					<li class="sidebar-brand" id="user">
						<a href="/user" style="display:inline; padding-right:0px; line-height:60px;">
							<span id="numberbox">{{Auth::user()->admissionyear}}</span>
							<span id="name">{{Auth::user()->realname}}</span>
						</a>
					</li>
					
					<li><a href="/logout"><i class="fa fa-sign-out" aria-hidden="true"></i>로그아웃</a></li>

						<li><a href="/auth"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;가입승락</a></li>

						<li><a href="/members"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;회원목록</a></li>
					
					<li><a href="/introduce"><i class="fa fa-fw fa-file-o"></i>&nbsp;&nbsp;동아리 소개</a></li>
					
					<li><a href="/group/project"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;&nbsp;프로젝트</a></li>
					<li><a href="/group/study"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbsp;스터디</a></li>
					<li><a href="/free"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;&nbsp;자유게시판</a></li>
					<li><a href="/photo"><i class="fa fa-picture-o" aria-hidden="true"></i>&nbsp;&nbsp;사진게시판</a></li>
					<li><a href="/notice"><i class="fa fa-microphone" aria-hidden="true"></i>&nbsp;&nbsp;공지사항</a></li>
					

					<li><a href="/jokbo"><i class="fa fa-fw fa-folder" aria-hidden="true"></i>&nbsp;&nbsp;과제/족보/솔루션</a></li>
					
					<li><a href="#"><i class="fa fa-fw fa-folder" aria-hidden="true"></i>&nbsp;&nbsp;회비 사용내역</a></li>
					

				@endif
				
             
            </ul>
        </nav>
        <!-- /#sidebar-wrapper -->

@show