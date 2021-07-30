@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">
			회원정보 수정
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/user/modify') }}">
				{{ csrf_field() }}

				<div class="form-group{{ $errors->has('userid') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="userid"><span style="color:green;">아이디</span></label>
						<input id="userid" type="userid" class="form-control" value="{{ Auth::user()->userid }}" disabled>
						 @if ($errors->has('userid'))
							<span class="help-block">
								{{ $errors->first('userid') }}
							</span>
						@endif
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="email"><span style="color:green;">이메일</span></label>
						<input id="email" type="email" class="form-control" name="email" value="{{  Auth::user()->email }}">
						 @if ($errors->has('email'))
							<span class="help-block">
								{{ $errors->first('email') }}
							</span>
						@endif
					</div>
				</div>
				
				
				<div class="form-group{{ $errors->has('realname') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="realname"><span style="color:green;">이름</span></label>
						<input id="realname" type="realname" class="form-control" value="{{ Auth::user()->realname }}" disabled>
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
						<input id="phone" type="phone" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
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
						<input id="admissionyear" type="admissionyear" class="form-control"  value="{{ Auth::user()->admissionyear }}" disabled>
						 @if ($errors->has('admissionyear'))
							<span class="help-block">
								{{ $errors->first('admissionyear') }}
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
</div>
@endsection
