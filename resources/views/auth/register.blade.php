@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">
			회원가입 - <h6 style="display:inline;"><span style="color:green;">초록색 </span>입력란은 반드시 기입해주세요.</h6>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
				{{ csrf_field() }}

				<div class="form-group{{ $errors->has('userid') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="userid"><span style="color:green;">아이디</span></label>
						<input id="userid" type="userid" class="form-control" name="userid" value="{{ old('userid') }}" required autofocus>
						 @if ($errors->has('userid'))
							<span class="help-block">
								{{ $errors->first('userid') }}
							</span>
						@endif
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="email"><span style="color:green;">이메일</span><h6 style="display:inline;"> - 비밀번호 분실시 사용됩니다.</h6></label>
						<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
						 @if ($errors->has('email'))
							<span class="help-block">
								{{ $errors->first('email') }}
							</span>
						@endif
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="password"><span style="color:green;">비밀번호</span><h6 style="display:inline;"> - 비밀번호는 6자리 이상으로 설정되어야 합니다.</h6></label>
						<input id="password" type="password" class="form-control" name="password" required>
						 @if ($errors->has('password'))
							<span class="help-block">
								{{ $errors->first('password') }}
							</span>
						@endif
					</div>
				</div>

				<div class="form-group">		
					<div class="col-md-12">
						<label for="password-confirm"><span style="color:green;">비밀번호 확인</span></label>
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('realname') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="realname"><span style="color:green;">이름</span></label>
						<input id="realname" type="realname" class="form-control" name="realname" value="{{ old('realname') }}" required>
						 @if ($errors->has('realname'))
							<span class="help-block">
								{{ $errors->first('realname') }}
							</span>
						@endif
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="phone">전화번호</label>
						<input id="phone" type="phone" class="form-control" name="phone" value="{{ old('phone') }}">
						 @if ($errors->has('phone'))
							<span class="help-block">
								{{ $errors->first('phone') }}
							</span>
						@endif
					</div>
				</div>

				<div class="form-group{{ $errors->has('admissionyear') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="admissionyear"><span style="color:green;">입학년도 (2자리 숫자)</span></label>
						<input id="admissionyear" type="admissionyear" class="form-control" name="admissionyear" value="{{ old('admissionyear') }}" required>
						 @if ($errors->has('admissionyear'))
							<span class="help-block">
								{{ $errors->first('admissionyear') }}
							</span>
						@endif
					</div>
				</div>
								
				<div class="form-group">
					<div class="col-md-12">
						<button type="submit" class="btn btn-success">회원가입</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
