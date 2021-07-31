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
					@if (session('status'))
						<div class="form-group alert alert-success">
							{{ session('status') }}
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<a class="btn btn-success" href="{{ url('/') }}">돌아가기</a>
							</div>
						</div>
					@else					
					<div class="form-group">
						메일이 전송되는데 다소 시간이 소요될 수 있습니다.
					</div>

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="이메일">
								@if ($errors->has('email'))
									<div class="help-block">
										{{ $errors->first('email') }}
									</div>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-success">
									메일 전송
								</button>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<a class="btn btn-cancel" href="{{ url('/') }}">취소</a>
							</div>
						</div>
					</form>
					@endif
				</div>
			</div>
		</div>
	</div>
</body>
</html>