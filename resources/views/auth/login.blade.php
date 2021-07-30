@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">로그인</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
				{{ csrf_field() }}

				<div class="form-group{{ $errors->has('userid') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="userid">아이디 또는 이메일</label>
						<input id="userid" type="userid" class="form-control" name="userid" value="{{ old('userid') }}" required autofocus>
						 @if ($errors->has('userid'))
							<span class="help-block">
								<strong>{{ $errors->first('userid') }}</strong>
							</span>
						@endif
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="password">비밀번호</label>
						<input id="password" type="password" class="form-control" name="password" required>
						@if ($errors->has('password'))
							<span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
						@endif
					</div>
				</div>

					<div class="form-group">
						<div class="col-md-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember"> 자동 로그인
								</label>
							</div>
						</div>
					</div>

				<div class="form-group">
					<div class="col-md-12">
						<button type="submit" class="btn btn-success">
							로그인
						</button>
						<a class="btn btn-warning" href="{{ url('/register') }}">
							회원가입
						</a>
						<a class="btn btn-link" href="{{ url('/password/reset') }}">
							비밀번호 재설정
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
