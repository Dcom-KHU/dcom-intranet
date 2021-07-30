@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">비밀번호 재설정</div>

		<div class="panel-body">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
				{{ csrf_field() }}

				<input type="hidden" name="token" value="{{ $token }}">

				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="email">이메일</label>
						<input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
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

				<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
					
					<div class="col-md-12">
						<label for="password-confirm">비밀번호 확인</label>
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

						@if ($errors->has('password_confirmation'))
							<span class="help-block">
								<strong>{{ $errors->first('password_confirmation') }}</strong>
							</span>
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
			</form>
		</div>
	</div>
</div>
@endsection
