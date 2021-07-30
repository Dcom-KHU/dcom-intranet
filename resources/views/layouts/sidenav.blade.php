@section('sidenav')

        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
			<a href="/" class="sidebar-header">
				<i class="fa fa-desktop"></i>
				<span>D.COM</span>
			</a>
            <ul class="nav sidebar-nav">
				<!--li><a href="/members"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;회원목록</a></li-->
				<li>
					<a href="/">
						<i class="fa fa-home" aria-hidden="true"></i>
						<span>Home</span>
					</a>
				</li>
				@if(Auth::user()->admin > 0)
				<?php
				$waiting_count = APP\User::where("confirm",0)->get()->count();
				?>
				<li>
					<a href="/auth">
						<i class="fa fa-check" aria-hidden="true"></i>
						<span>가입승인</span>
						@if($waiting_count > 0)
						<span class="waiting-count">{{$waiting_count}}</span>
						@endif
					</a>
				</li>
				<li>
					<a href="/users">
						<i class="fa fa-user" aria-hidden="true"></i>
						<span>회원목록</span>
					</a>
				</li>
				@endif
				<li>
					<a href="/notice">
						<i class="fa fa-microphone" ria-hidden="true"></i>
						<span>공지사항</span>
					</a>
				</li>
				<li>
					<a href="/free">
						<i class="fa fa-globe" ria-hidden="true"></i>
						<span>자유게시판</span>
					</a>
				</li>
				<li>
					<a href="/jokbo">
						<i class="fa fa-fw fa-folder" ria-hidden="true"></i>
						<span>족보</span>
					</a>
				</li>
            </ul>
        </nav>
        <!-- /#sidebar-wrapper -->

@show