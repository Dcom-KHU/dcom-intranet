@extends('layouts.layout')

@section('content')
<script>
	function validate(f) {
		if(!f.userid.value || !f.email.value || !f.realname.value || !f.admissionyear.value) {
			alert("빠짐 없이 입력해주세요.");
			return false;
		}

		if(!/^\w+@\w+(\.\w+)+$/.test(f.email.value)) {
			alert("이메일 형식을 확인해주세요.");
			return false;
		}

		if(f.password.value != f.password_confirmation.value) {
			alert("비밀번호를 확인해주세요.");
			return false;
		}

		if(f.userid.value.length > 255) {
			alert("아이디는 255자까지만 입력 가능합니다.");
			return false;
		}

		if(f.email.value.length > 255) {
			alert("이메일은 255자까지만 입력 가능합니다.");
			return false;
		}

		if(!!f.password.value && f.password.value.length < 6) {
			alert("비밀번호는 6자리 이상 가능합니다.");
			return false;
		}

		if(f.realname.value.length > 255) {
			alert("이름은 255자까지만 입력 가능합니다.");
			return false;
		}

		if(f.phone.value.length > 255) {
			alert("전화번호는 255자까지만 입력 가능합니다.");
			return false;
		}

		if(f.admissionyear.value.length != 2) {
			alert("입학년도는 YY 형태로 맞춰주세요.");
			return false;
		}

		return true;
	}
</script>

	<div class="card-section half-card">
		<div class="card-header">
			회원정보 수정
		</div>
		<div class="card-content">
			<form class="round-form" role="form" method="POST" action="{{ url('/user/modify') }}" onsubmit="return validate(this);">
				{{ csrf_field() }}

				<div class="form-group{{ $errors->has('userid') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<input id="userid" type="text" class="form-control" value="{{ Auth::user()->userid }}" placeholder="아이디" disabled>
						 @if ($errors->has('userid'))
							<span class="help-block">
								{{ $errors->first('userid') }}
							</span>
						@endif
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<input id="email" type="email" class="form-control" name="email" value="{{  Auth::user()->email }}" placeholder="이메일">
						 @if ($errors->has('email'))
							<span class="help-block">
								{{ $errors->first('email') }}
							</span>
						@endif
					</div>
				</div>
				
				<div style="display:flex;">
					<div class="form-group{{ $errors->has('admissionyear') ? ' has-error' : '' }}">
						<div class="col-md-12">
							<input id="admissionyear" name="admissionyear" type="number" class="form-control"  value="{{ Auth::user()->admissionyear }}" placeholder="입학년도 (ex 22)">
							@if ($errors->has('admissionyear'))
								<span class="help-block">
									{{ $errors->first('admissionyear') }}
								</span>
							@endif
						</div>
					</div>
					
					<div class="form-group{{ $errors->has('realname') ? ' has-error' : '' }}">
						<div class="col-md-12">
							<input id="realname" name="realname" type="text" class="form-control" value="{{ Auth::user()->realname }}" placeholder="이름">
							@if ($errors->has('realname'))
								<span class="help-block">
									{{ $errors->first('realname') }}
								</span>
							@endif
						</div>
					</div>
				</div>
				
				<div style="display:flex;">
					<div class="form-group{{ $errors->has('admissionyear') ? ' has-error' : '' }}">
						<div class="col-md-12">
							<input id="password" name="password" type="password" class="form-control" placeholder="비밀번호">
							@if ($errors->has('admissionyear'))
								<span class="help-block">
									{{ $errors->first('password') }}
								</span>
							@endif
						</div>
					</div>
					
					<div class="form-group{{ $errors->has('realname') ? ' has-error' : '' }}">
						<div class="col-md-12">
							<input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="비밀번호 확인">
						</div>
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<input id="phone" type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}" placeholder="전화번호">
						 @if ($errors->has('phone'))
							<span class="help-block">
								{{ $errors->first('phone') }}
							</span>
						@endif
					</div>
				</div>	
				
				<div class="form-group">
					<div class="col-md-12">
						<button type="submit" class="btn btn-success">완료</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
