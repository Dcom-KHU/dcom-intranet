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
				<div class="panel-heading">비밀번호 재설정</div>

				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
						{{ csrf_field() }}

						<input type="hidden" name="token" value="{{ $token }}">

						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus placeholder="이메일">
								@if ($errors->has('email'))
									<div class="help-block">
										{{ $errors->first('email') }}
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

						<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
							
							<div class="col-md-12">
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="비밀번호 확인">

								@if ($errors->has('password_confirmation'))
									<div class="help-block">
										{{ $errors->first('password_confirmation') }}
									</div>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-success">
									비밀번호 재설정
								</button>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<a class="btn btn-cancel" href="{{ url('/') }}">취소</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
