@extends('layouts.app')

<!-- Main Content -->
@section('content')

<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">비밀번호 재설정</div>
		<div class="panel-body">
			@if (session('status'))
				<div class="alert alert-success">
					{{ session('status') }}
				</div>
			@endif
			
			<span class="help-block">
				<strong style="color:rgb(112, 23, 23);">메일이 전송되는데 다소 시간이 소요될 수 있습니다.</strong>
			</span>

			<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
				{{ csrf_field() }}

				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="email">이메일</label>
						<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
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
			</form>
		</div>
	</div>
</div>
@endsection
