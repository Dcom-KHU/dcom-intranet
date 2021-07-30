<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.layout-sub')
	<link rel="stylesheet" href="/css/new/before-login.css">
</head>
<body>
	<div class="container with-image">
		<div class="panel panel-default panel-login">
			<div class="form-side">
				<img src="/image/login.jpg">
			</div>
			<div class="form-main">
				<div class="panel-heading">로그인</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('userid') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="userid" type="text" class="form-control" name="userid" value="{{ old('userid') }}" required autofocus placeholder="아이디 또는 이메일">
								@if ($errors->has('userid'))
									<div class="help-block">
										{{ $errors->first('userid') }}
									</div>
								@endif
							</div>
						</div>
						
						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="password" type="password" class="form-control" name="password" required placeholder="비밀번호">
								@if ($errors->has('password'))
									<div class="help-block">
										{{ $errors->first('password') }}
									</div>
								@endif
							</div>
						</div>

							<div class="form-group">
								<div class="col-md-12"> 
									<div class="checkbox checkbox-custom">
										<input type="checkbox" id="remember" name="remember">
										<label for="remember">자동 로그인
										</label>
									</div>
								</div>
							</div>

						<div class="form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-success">
									로그인
								</button>
							</div>
						</div>
						<div class="links">
							<a href="{{ url('/register') }}">
								회원가입
							</a>
                            <div>
                                <a href="{{ url('/id/find') }}">아이디</a>
                                /
                                <a href="{{ url('/password/reset') }}">비밀번호 찾기</a>
                            </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>