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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/id/find') }}">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="이름">
								@if ($errors->has('email'))
									<div class="help-block">
										{{ $errors->first('name') }}
									</div>
								@endif
							</div>
						</div>

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
									찾기
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
</body>
</html>