<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.layout-sub')
	<link rel="stylesheet" href="/css/new/before-login.css">
</head>
<body>
	<div class="container">
		<div class="panel panel-default">
			<div class="form-main">
				<div class="panel-heading">아이디 찾기</div>
				<div class="panel-body">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif

                    <div style="margin-bottom:1rem;">
                        @if($id === null)
                            아이디를 찾을 수 없습니다.
                        @else
                            아이디 : {{$id}}
                        @endif
                    </div>
                    
					<div class="form-group">
						<div class="col-md-12">
                            @if($id === null)
							    <a href="/id/find" class="btn btn-success">
                                    돌아가기
                                </a>
                            @else
							    <a href="/" class="btn btn-success">
                                    로그인
                                </a>
                            @endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>